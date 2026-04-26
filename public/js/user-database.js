document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('dbSearchInput');
    
    /** * REINFORCED AUTOFILL KILLER
     */
    if (searchInput) {
        searchInput.value = '';
        searchInput.setAttribute('readonly', 'readonly');
        
        setTimeout(() => {
            searchInput.value = '';
            searchInput.removeAttribute('readonly');
        }, 100);

        window.addEventListener('pageshow', () => {
            searchInput.value = '';
        });
    }

    // 1. Data Store
    const usersData = {
        1: { 
            id: 1, 
            code: 'SKR-2026-0001',
            name: 'Josh Wayne', 
            email: 'josh.wayne@email.com', 
            phone: '+63 912 345 6789', 
            joined: 'March 10, 2026', 
            dob: 'May 12, 1998',
            type: 'Seeker', 
            status: 'Pending', 
            rating: '4.8',
            district: 'Quiapo, Manila',
            address: '123 Hidalgo St., Brgy 306, Quiapo, Manila City',
            img: 'https://i.pravatar.cc/150?u=josh', 
            suspensionReason: '', 
            needs: 'Valid ID, NBI Clearance' 
        },
        2: { 
            id: 2, 
            code: 'EMP-MNL-8821',
            name: 'SM Manila (HR)', 
            email: 'hr@sm-manila.com.ph', 
            phone: '+63 2 8523 7044', 
            joined: 'January 05, 2026', 
            dob: 'N/A (Corporate)',
            type: 'Employer', 
            status: 'Verified', 
            rating: '5.0',
            district: 'Ermita, Manila',
            address: 'San Marcelino St. cor. Doña Julia Vargas Ave, Ermita, Manila',
            img: 'https://via.placeholder.com/150', 
            suspensionReason: '', 
            needs: 'Complete' 
        },
        3: { 
            id: 3, 
            code: 'SKR-2026-0412',
            name: 'Mark Rivera', 
            email: 'm.rivera@email.com', 
            phone: '+63 998 765 4321', 
            joined: 'February 20, 2026', 
            dob: 'October 22, 1995',
            type: 'Seeker', 
            status: 'Suspended', 
            rating: '3.2',
            district: 'Binondo, Manila',
            address: '456 Quintin Paredes St., Binondo, Manila City',
            img: 'https://i.pravatar.cc/150?u=mark', 
            suspensionReason: 'Policy Violation: Excessive job cancellations.', 
            needs: 'Reason: Policy Violation' 
        }
    };

    let activeAction = null; 
    let activeUserId = null;

    // 2. Render Main Table
    window.renderUserTable = function(filterTerm = '') {
        const tableBody = document.getElementById('user-table-body');
        if (!tableBody) return;
        
        tableBody.innerHTML = '';
        let displayCount = 0;
        let rowIndex = 1; // Counter for the # column

        Object.values(usersData).forEach(user => {
            if (user.name.toLowerCase().includes(filterTerm) || 
                user.email.toLowerCase().includes(filterTerm) ||
                user.code.toLowerCase().includes(filterTerm)) {
                
                displayCount++;
                let statusBadge = '';
                let needsClass = 'small text-muted italic';
                
                if(user.status === 'Verified') {
                    statusBadge = '<span class="badge bg-success-subtle text-success border border-success px-3">Verified</span>';
                    needsClass = 'small text-success fw-bold';
                } else if(user.status === 'Suspended') {
                    statusBadge = '<span class="badge bg-danger-subtle text-danger border border-danger px-3">Suspended</span>';
                } else if(user.status === 'Rejected') {
                    statusBadge = '<span class="badge bg-secondary-subtle text-secondary border border-secondary px-3">Rejected</span>';
                } else {
                    statusBadge = '<span class="badge bg-warning-subtle text-warning border border-warning px-3">Pending</span>';
                }

                const row = `
                    <tr>
                        <td class="text-muted fw-bold small">${rowIndex++}</td>
                        <td class="small fw-bold text-primary">${user.code}</td>
                        <td class="fw-bold user-name">${user.name}</td>
                        <td class="small text-muted">${user.district}</td>
                        <td><span class="badge rounded-pill ${user.type === 'Seeker' ? 'text-info border-info' : 'text-primary border-primary'}" 
                        style="background-color: ${user.type === 'Seeker' ? '#f0faff' : '#eef2ff'}; border: 1px solid;">${user.type}</span></td>
                        <td class="status-cell">${statusBadge}</td>
                        <td class="${needsClass}">${user.needs}</td>
                        <td class="text-center">
                            <button class="btn btn-outline-dark btn-sm rounded-3 px-3" onclick="viewUserDetails(${user.id})">
                            <span class="material-symbols-outlined fs-6 align-middle me-1">description</span>View Details</button>
                        </td>
                    </tr>
                `;
                tableBody.insertAdjacentHTML('beforeend', row);
            }
        });
        document.getElementById('user-count-text').innerText = `Showing ${displayCount} users in database`;
    };

    // 3. View User Details
    window.viewUserDetails = function(id) {
        const user = usersData[id];
        activeUserId = id;
        
        document.getElementById('modal-user-name').innerText = user.name;
        document.getElementById('modal-user-code').innerText = user.code;
        document.getElementById('modal-user-email').innerText = user.email;
        document.getElementById('modal-user-phone').innerText = user.phone;
        document.getElementById('modal-user-dob').innerText = user.dob;
        document.getElementById('modal-user-joined').innerText = user.joined;
        document.getElementById('modal-user-type').innerText = user.type;
        document.getElementById('modal-user-district').innerText = user.district;
        document.getElementById('modal-user-address').innerText = user.address;
        document.getElementById('modal-user-img').src = user.img;
        
        document.getElementById('modal-user-rating').innerHTML = `${user.rating} <span class="rating-star">★</span>`;

        const verifyLink = document.getElementById('verify-docs-link');
        verifyLink.href = user.type === 'Seeker' ? '/admin/seekers' : '/admin/employers';

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
            } else if(user.status === 'Rejected') {
                statusEl.innerHTML = '<span class="badge bg-secondary-subtle text-secondary border border-secondary px-3">Rejected</span>';
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
            message.innerText = `User ${user.code} (${user.name}) has been purged.`;
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

    renderUserTable();

    // Call shared notification renderer
    if (typeof renderNotifications === 'function') {
        renderNotifications();
    }
});