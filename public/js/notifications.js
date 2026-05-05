(function () {
    'use strict';

    var STORAGE_KEY = 'manila_linkup_read_notifs';
    var API_URL     = '/admin/notifications';

    function getReadSet() {
        try { return JSON.parse(localStorage.getItem(STORAGE_KEY) || '[]'); }
        catch (e) { return []; }
    }

    function saveReadSet(arr) {
        try { localStorage.setItem(STORAGE_KEY, JSON.stringify(arr)); }
        catch (e) {}
    }

    function isRead(uid) { return getReadSet().indexOf(uid) !== -1; }

    function markUidRead(uid) {
        var set = getReadSet();
        if (set.indexOf(uid) === -1) { set.push(uid); saveReadSet(set); }
    }

    // Prune stale UIDs — remove any stored UID no longer in the current pending list
    function pruneReadSet(currentUids) {
        var pruned = getReadSet().filter(function (uid) {
            return currentUids.indexOf(uid) !== -1;
        });
        saveReadSet(pruned);
    }

    function relativeTime(iso) {
        if (!iso) return 'Date unknown';
        var diff  = new Date() - new Date(iso);
        if (isNaN(diff) || diff < 0) return 'Just now';
        var secs  = Math.floor(diff / 1000);
        var mins  = Math.floor(secs  / 60);
        var hours = Math.floor(mins  / 60);
        var days  = Math.floor(hours / 24);
        if (secs  < 60) return 'Just now';
        if (mins  < 60) return mins  + (mins  === 1 ? ' min'  : ' mins')  + ' ago';
        if (hours < 24) return hours + (hours === 1 ? ' hour' : ' hours') + ' ago';
        if (days  < 7)  return days  + (days  === 1 ? ' day'  : ' days')  + ' ago';
        return new Date(iso).toLocaleDateString('en-PH', { month: 'short', day: 'numeric', year: 'numeric' });
    }

    var _cached = [];

    function render(notifications) {
        var list      = document.getElementById('notification-list');
        var modalList = document.getElementById('modal-alerts-list');
        var badge     = document.getElementById('notif-count');
        if (!list || !badge) return;

        var unread = notifications.filter(function (n) { return !isRead(n.uid); }).length;
        badge.innerText     = unread;
        badge.style.display = unread > 0 ? 'block' : 'none';

        if (notifications.length === 0) {
            var empty = '<div class="p-3 text-center text-muted extra-small">No pending verifications.</div>';
            list.innerHTML = empty;
            if (modalList) modalList.innerHTML = empty;
            return;
        }

        var html = notifications.map(function (n) {
            var read  = isRead(n.uid);
            var label = n.userType === 'employer' ? 'Employer' : 'Seeker';
            var dot   = read ? '' : '<div class="bg-primary rounded-circle flex-shrink-0" style="width:8px;height:8px;margin-top:5px;"></div>';
            return (
                '<div class="notification-item p-3 ' + (read ? '' : 'unread') + '" ' +
                     'data-uid="' + n.uid + '" style="cursor:pointer;border-bottom:1px solid #f1f3f9;">' +
                    '<div class="d-flex align-items-start">' +
                        '<div class="flex-grow-1">' +
                            '<p class="small fw-bold mb-1" style="color:#1B3E9C;">New Verification Request</p>' +
                            '<p class="extra-small text-muted mb-1">' + n.name + ' (' + label + ') submitted ID &amp; clearance for review</p>' +
                            '<span class="extra-small text-secondary">' + relativeTime(n.updatedAt) + '</span>' +
                        '</div>' +
                        dot +
                    '</div>' +
                '</div>'
            );
        }).join('');

        list.innerHTML = html;
        if (modalList) modalList.innerHTML = html;

        function attachClicks(container) {
            container.querySelectorAll('.notification-item[data-uid]').forEach(function (el) {
                el.addEventListener('click', function () {
                    markUidRead(el.getAttribute('data-uid'));
                    render(notifications);
                });
            });
        }
        attachClicks(list);
        if (modalList) attachClicks(modalList);
    }

    // Kept as no-ops for backward compat — page-specific JS files call these on DOMContentLoaded
    window.renderNotifications = function () {};

    window.clearNotifications = function () {
        saveReadSet(_cached.map(function (n) { return n.uid; }));
        render(_cached);
    };

    document.addEventListener('DOMContentLoaded', function () {
        fetch(API_URL, {
            method: 'GET',
            credentials: 'same-origin',
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(function (res) {
            if (!res.ok) throw new Error('HTTP ' + res.status);
            return res.json();
        })
        .then(function (data) {
            _cached = Array.isArray(data) ? data : [];
            pruneReadSet(_cached.map(function (n) { return n.uid; }));
            render(_cached);
        })
        .catch(function () {
            render([]);
        });
    });
}());
