document.addEventListener('DOMContentLoaded', function () {

    if (typeof renderNotifications === 'function') {
        renderNotifications();
    }

    const pending     = window.pendingVerifications ?? [];
    const csrfToken   = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const rejectModal = new bootstrap.Modal(document.getElementById('rejectReasonModal'));

    let pendingUid   = null;
    let pendingType  = null;
    let pendingName  = null;
    let pendingCount = pending.length;

    // ── Badge ────────────────────────────────────────────────────────────
    function updateBadge(count) {
        const badge = document.getElementById('verif-badge');
        if (!badge) return;
        badge.innerText = count;
        badge.classList.toggle('d-none', count <= 0);
    }

    updateBadge(pendingCount);

    // ── Remove card ──────────────────────────────────────────────────────
    function removeCard(uid) {
        const card = document.getElementById('verif-card-' + uid);
        if (!card) return;

        card.style.transition = 'opacity 0.3s, transform 0.3s';
        card.style.opacity    = '0';
        card.style.transform  = 'scale(0.95)';

        setTimeout(function () {
            card.remove();
            pendingCount = Math.max(0, pendingCount - 1);
            updateBadge(pendingCount);

            const countText = document.getElementById('queue-count-text');
            if (countText) countText.innerText = pendingCount;

            if (pendingCount === 0) {
                const grid = document.getElementById('verif-card-grid');
                if (grid) {
                    grid.innerHTML =
                        '<div class="col-12">' +
                        '<div class="text-center py-5 text-muted">' +
                        '<span class="material-symbols-outlined fs-1 d-block mb-2">task_alt</span>' +
                        '<p class="fw-bold mb-0">No pending verifications.</p>' +
                        '<p class="small">All users are verified or the queue is clear.</p>' +
                        '</div></div>';
                }
            }
        }, 300);
    }

    // ── API helper ───────────────────────────────────────────────────────
    function callApi(url) {
        return fetch(url, {
            method:  'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
        });
    }

    // ── Success alert ────────────────────────────────────────────────────
    function showSuccessAlert(message) {
        const alertBox = document.getElementById('js-success-alert');
        const alertMsg = document.getElementById('alert-message');
        if (!alertBox || !alertMsg) return;
        alertMsg.innerText = message;
        alertBox.classList.remove('d-none');
        setTimeout(function () { alertBox.classList.add('d-none'); }, 5000);
    }

    // ── Approve ──────────────────────────────────────────────────────────
    window.approveUser = function (btn) {
        const uid  = btn.getAttribute('data-uid');
        const type = btn.getAttribute('data-type');
        const name = btn.getAttribute('data-name');
        const url  = '/admin/' + type + 's/' + uid + '/verify';

        btn.disabled  = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Approving...';

        callApi(url)
            .then(function (res) { return res.json(); })
            .then(function (data) {
                if (data.success) {
                    showSuccessAlert(name + ' has been approved and verified.');
                    removeCard(uid);
                } else {
                    btn.disabled  = false;
                    btn.innerHTML = '<span class="material-symbols-outlined fs-6 align-middle me-1">check_circle</span>Approve';
                }
            })
            .catch(function () {
                btn.disabled  = false;
                btn.innerHTML = '<span class="material-symbols-outlined fs-6 align-middle me-1">check_circle</span>Approve';
            });
    };

    // ── Open reject modal ────────────────────────────────────────────────
    window.openRejectModal = function (btn) {
        pendingUid  = btn.getAttribute('data-uid');
        pendingType = btn.getAttribute('data-type');
        pendingName = btn.getAttribute('data-name');

        document.getElementById('reject-target-name').innerText     = pendingName;
        document.getElementById('reject-reason-select').value        = 'Expired Documents';
        document.getElementById('other-reason-text').classList.add('d-none');
        document.getElementById('other-reason-text').value           = '';

        rejectModal.show();
    };

    // ── "Other" textarea toggle ──────────────────────────────────────────
    var reasonSelect = document.getElementById('reject-reason-select');
    var otherText    = document.getElementById('other-reason-text');

    if (reasonSelect) {
        reasonSelect.addEventListener('change', function () {
            otherText.classList.toggle('d-none', this.value !== 'other');
        });
    }

    // ── Confirm rejection ────────────────────────────────────────────────
    var btnConfirmReject = document.getElementById('btn-confirm-reject');

    if (btnConfirmReject) {
        btnConfirmReject.addEventListener('click', function () {
            var reason = reasonSelect.value === 'other'
                ? otherText.value.trim()
                : reasonSelect.value;

            if (reasonSelect.value === 'other' && !reason) {
                otherText.classList.add('is-invalid');
                return;
            }

            var url = '/admin/' + pendingType + 's/' + pendingUid + '/reject';

            btnConfirmReject.disabled    = true;
            btnConfirmReject.textContent = 'Rejecting...';

            callApi(url)
                .then(function (res) { return res.json(); })
                .then(function (data) {
                    rejectModal.hide();
                    btnConfirmReject.disabled    = false;
                    btnConfirmReject.textContent = 'Confirm Rejection';

                    if (data.success) {
                        showSuccessAlert(pendingName + '\'s verification has been rejected: ' + reason);
                        removeCard(pendingUid);
                    }
                })
                .catch(function () {
                    btnConfirmReject.disabled    = false;
                    btnConfirmReject.textContent = 'Confirm Rejection';
                });
        });
    }

});
