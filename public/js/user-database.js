document.addEventListener('DOMContentLoaded', function() {
    // We changed the ID to dbSearchInput to break the browser's memory of the old field
    const searchInput = document.getElementById('dbSearchInput');
    
    /** * TRIPLE-ACTION AUTOFILL KILLER
     */
    if (searchInput) {
        // 1. Clear immediately
        searchInput.value = '';
        
        // 2. Clear after a short delay (catches late browser injection)
        setTimeout(() => {
            searchInput.value = '';
        }, 60);

        // 3. Clear on first interaction
        searchInput.addEventListener('focus', function() {
            if (this.value !== '') this.value = '';
        }, { once: true });
    }

    // 1. Data Store
    const usersData = {
        1: { id: 1, name: 'Josh Wayne', email: 'josh.wayne@email.com', phone: '+63 912 345 6789', joined: 'March 10, 2026', type: 'Seeker', status: 'Pending', skills: 'Construction, Carpentry', history: 'Formerly worked as a site helper in QC. Looking for temporary labor roles.', img: 'https://i.pravatar.cc/150?u=josh', suspensionReason: '', needs: 'Valid ID, NBI Clearance' },
        2: { id: 2, name: 'SM Manila (HR)', email: 'hr@sm-manila.com.ph', phone: '+63 2 8523 7044', joined: 'January 05, 2026', type: 'Employer', status: 'Verified', skills: 'Retail, Customer Service', history: 'Official HR account for SM City Manila. Recruiting for seasonal sales associates.', img: 'https://via.placeholder.com/150', suspensionReason: '', needs: 'Complete' },
        3: { id: 3, name: 'Mark Rivera', email: 'm.rivera@email.com', phone: '+63 998 765 4321', joined: 'February 20, 2026', type: 'Seeker', status: 'Suspended', skills: 'Delivery, Logistics', history: 'Experienced motorcycle rider. Account suspended due to frequent cancellations.', img: 'https://i.pravatar.cc/150?u=mark', suspensionReason: 'Policy Violation: Excessive job cancellations (3 occurrences in 24 hours).', needs: 'Reason: Policy Violation' }
    };

    let activeAction = null; 
    let activeUserId = null;

    // 2. Render Main Table
    window.renderUserTable = function(filterTerm = '') {
        const tableBody = document.getElementById('user-table-body');
        if (!tableBody) return;
        
        tableBody.innerHTML = '';
        let count = 0;

        Object.values(usersData).forEach(user => {
            if (user.name.toLowerCase().includes(filterTerm) || user.email.toLowerCase().includes(filterTerm)) {
                count++;
                let statusBadge = '';
                let needsClass = 'small text-muted italic';
                
                if(user.status === 'Verified') {
                    statusBadge = '<span class="badge bg-success-subtle text-success border border-success px-3">Verified</span>';
                    needsClass = 'small text-success fw-bold';
                } else if(user.status === 'Suspended') {
                    statusBadge = '<span class="badge bg-danger-subtle text-danger border border-danger px-3">Suspended</span>';
                } else {
                    statusBadge = '<span class="badge bg-warning-subtle text-warning border border-warning px-3">Pending</span>';
                }

                const row = `
                    <tr>
                        <td class="fw-bold">${user.id}</td>
                        <td class="fw-bold user-name">${user.name}</td>
                        <td class="text-muted">${user.email}</td>
                        <td><span class="badge rounded-pill ${user.type === 'Seeker' ? 'text-info border-info' : 'text-primary border-primary'}" style="background-color: ${user.type === 'Seeker' ? '#f0faff' : '#eef2ff'}; border: 1px solid;">${user.type}</span></td>
                        <td class="status-cell">${statusBadge}</td>
                        <td class="${needsClass}">${user.needs}</td>
                        <td class="text-center">
                            <button class="btn btn-outline-dark btn-sm rounded-3 px-3" onclick="viewUserDetails(${user.id})">View Details</button>
                        </td>
                    </tr>
                `;
                tableBody.insertAdjacentHTML('beforeend', row);
            }
        });
        document.getElementById('user-count-text').innerText = `Showing ${count} users in database`;
    };

    // 3. View User Details
    window.viewUserDetails = function(id) {
        const user = usersData[id];
        activeUserId = id;
        
        document.getElementById('modal-user-name').innerText = user.name;
        document.getElementById('modal-user-email').innerText = user.email;
        document.getElementById('modal-user-phone').innerText = user.phone;
        document.getElementById('modal-user-joined').innerText = user.joined;
        document.getElementById('modal-user-type').innerText = user.type;
        document.getElementById('modal-user-skills').innerText = user.skills;
        document.getElementById('modal-user-history').innerText = user.history;
        document.getElementById('modal-user-img').src = user.img;

        const statusEl = document.getElementById('modal-user-status');
        const reasonBox = document.getElementById('suspension-reason-box');
        const reasonText = document.getElementById('modal-suspension-text');
        const btnContainer = document.getElementById('modal-action-buttons');

        btnContainer.innerHTML = '';
        
        if(user.status === 'Suspended') {
            statusEl.innerHTML = '<span class="badge bg-danger-subtle text-danger border border-danger px-3">Suspended</span>';
            reasonBox.classList.remove('d-none');
            reasonText.innerText = user.suspensionReason || 'No reason specified.';
            
            btnContainer.innerHTML = `
                <button class="btn btn-success btn-sm px-4 rounded-pill fw-bold" onclick="openActionModal('unsuspend')">Unsuspend Account</button>
                <button class="btn btn-danger btn-sm px-4 rounded-pill fw-bold" onclick="openActionModal('delete')">Delete User Data</button>
            `;
        } else {
            if(user.status === 'Verified') {
                statusEl.innerHTML = '<span class="badge bg-success-subtle text-success border border-success px-3">Verified</span>';
            } else {
                statusEl.innerHTML = '<span class="badge bg-warning-subtle text-warning border border-warning px-3">Pending</span>';
            }
            reasonBox.classList.add('d-none');
            
            btnContainer.innerHTML = `
                <button class="btn btn-warning btn-sm px-4 rounded-pill fw-bold text-white" onclick="openActionModal('suspend')">Suspend Account</button>
                <button class="btn btn-danger btn-sm px-4 rounded-pill fw-bold" onclick="openActionModal('delete')">Delete User Data</button>
            `;
        }

        const detailsModal = new bootstrap.Modal(document.getElementById('userDetailsModal'));
        detailsModal.show();
    };

    // 4. Admin Action Trigger
    window.openActionModal = function(type) {
        activeAction = type;
        const title = document.getElementById('actionModalTitle');
        const suspendFields = document.getElementById('suspend-fields');
        const warningBox = document.getElementById('action-warning-box');
        const confirmBtn = document.getElementById('confirm-action-btn');
        
        suspendFields.classList.add('d-none');
        warningBox.className = "alert alert-danger border-0 small mb-4";
        confirmBtn.className = "btn btn-danger rounded-pill px-4";

        if(type === 'suspend') {
            title.innerText = "Suspend User Account";
            title.className = "modal-title fw-bold text-warning";
            suspendFields.classList.remove('d-none');
            confirmBtn.className = "btn btn-warning rounded-pill px-4 text-white";
        } else if (type === 'unsuspend') {
            title.innerText = "Lift Account Suspension";
            title.className = "modal-title fw-bold text-success";
            warningBox.className = "alert alert-success border-0 small mb-4";
            warningBox.innerHTML = "<strong>Notice:</strong> This will restore full access to the user immediately.";
            confirmBtn.className = "btn btn-success rounded-pill px-4";
        } else {
            title.innerText = "Delete User Permanently";
            title.className = "modal-title fw-bold text-danger";
            warningBox.innerHTML = "<strong>Warning:</strong> This action is permanent. All user data will be purged.";
        }

        const actionModal = new bootstrap.Modal(document.getElementById('adminActionModal'));
        actionModal.show();
    };

    // 5. Execute Action
    window.executeAdminAction = function() {
        const password = document.getElementById('admin-pass-verify').value;
        if(password === "") {
            alert("Please enter admin password to verify identity.");
            return;
        }

        const alertBox = document.getElementById('js-success-alert');
        const message = document.getElementById('alert-message');
        const user = usersData[activeUserId];
        
        if(activeAction === 'delete') {
            message.innerText = `User ID ${activeUserId} (${user.name}) has been purged.`;
            delete usersData[activeUserId];
        } else if(activeAction === 'unsuspend') {
            user.status = 'Verified';
            user.suspensionReason = '';
            user.needs = 'Complete';
            message.innerText = `Suspension lifted for ${user.name}.`;
        } else {
            const days = document.getElementById('suspend-days').value;
            const reasonInput = document.getElementById('suspend-reason').value;
            user.status = 'Suspended';
            user.suspensionReason = reasonInput;
            user.needs = `Reason: Policy Violation (${days === 'permanent' ? 'Indefinite' : days + ' days'})`;
            message.innerText = `Account suspended for ${days} days.`;
        }

        renderUserTable();
        alertBox.classList.remove('d-none');
        setTimeout(() => alertBox.classList.add('d-none'), 3000);

        bootstrap.Modal.getInstance(document.getElementById('adminActionModal')).hide();
        bootstrap.Modal.getInstance(document.getElementById('userDetailsModal')).hide();
        document.getElementById('admin-pass-verify').value = "";
    };

    // 6. Search Listener
    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            renderUserTable(e.target.value.toLowerCase());
        });
    }

    // Initial Table Render
    renderUserTable();
    
    // Initial Notifications Render
    if (typeof renderNotifications === 'function') {
        renderNotifications();
    }
});