// Shared Notification Data & Logic
const dummyNotifs = {
    notifications: [
        { id: 1, title: 'New Seeker Registration', body: 'Maria Santos uploaded ID for verification.', time: '2 mins ago', unread: true },
        { id: 2, title: 'System Alert', body: 'Server load reached 85% in Manila node.', time: '1 hour ago', unread: true },
        { id: 3, title: 'New Employer', body: 'Logistics Co registered in Malate.', time: '3 hours ago', unread: true },
        { id: 4, title: 'Verification Approved', body: 'Intramuros Tour is now active.', time: 'Yesterday', unread: false }
    ]
};

window.renderNotifications = function() {
    const list = document.getElementById('notification-list');
    const modalList = document.getElementById('modal-alerts-list');
    const countBadge = document.getElementById('notif-count');
    
    if (!list || !countBadge) return;

    const unreadCount = dummyNotifs.notifications.filter(n => n.unread).length;
    
    countBadge.innerText = unreadCount;
    countBadge.style.display = unreadCount > 0 ? 'block' : 'none';

    const notificationHTML = dummyNotifs.notifications.map(n => `
        <div class="notification-item p-3 ${n.unread ? 'unread' : ''}" onclick="markAsRead(${n.id})" style="cursor:pointer; border-bottom: 1px solid #f1f3f9;">
            <div class="d-flex align-items-start">
                <div class="flex-grow-1">
                    <p class="small fw-bold mb-1" style="color: #1B3E9C;">${n.title}</p>
                    <p class="extra-small text-muted mb-1">${n.body}</p>
                    <span class="extra-small text-secondary">${n.time}</span>
                </div>
                ${n.unread ? '<div class="bg-primary rounded-circle" style="width: 8px; height: 8px; margin-top: 5px;"></div>' : ''}
            </div>
        </div>
    `).join('');

    list.innerHTML = notificationHTML;
    if(modalList) modalList.innerHTML = notificationHTML;
};

window.markAsRead = function(id) {
    const notif = dummyNotifs.notifications.find(n => n.id === id);
    if (notif) notif.unread = false;
    renderNotifications();
};

window.clearNotifications = function() {
    dummyNotifs.notifications.forEach(n => n.unread = false);
    renderNotifications();
};