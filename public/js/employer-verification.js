document.addEventListener('DOMContentLoaded', function() {
    // 1. Initialize Notifications from the shared notifications.js
    if (typeof renderNotifications === 'function') {
        renderNotifications();
    }

    // 2. SEARCH & FILTER Logic
    const searchInput = document.getElementById('employerSearchInput');
    const tableBody = document.querySelector('#employerTable tbody');
    
    function filterTable(query) {
        const rows = tableBody.querySelectorAll('tr:not(.no-result-row)');
        let visibleCount = 0;

        rows.forEach(row => {
            const nameCell = row.querySelector('.entity-name');
            if (nameCell) {
                const name = nameCell.innerText.toLowerCase();
                if (name.includes(query.toLowerCase())) {
                    row.style.display = "";
                    visibleCount++;
                } else {
                    row.style.display = "none";
                }
            }
        });

        // "Not Found" message handling
        const existingNoResult = tableBody.querySelector('.no-result-row');
        if (visibleCount === 0) {
            if (!existingNoResult) {
                const noResultRow = document.createElement('tr');
                noResultRow.className = 'no-result-row';
                noResultRow.innerHTML = `<td colspan="8" class="text-center py-5 text-muted">
                    <span class="material-symbols-outlined fs-1 d-block mb-2">search_off</span>
                    Employer not found in database
                </td>`;
                tableBody.appendChild(noResultRow);
            }
        } else if (existingNoResult) {
            existingNoResult.remove();
        }
    }

    if (searchInput) {
        searchInput.addEventListener('input', (e) => filterTable(e.target.value));
    }

    // 3. Action Logic (Approve/Reject)
    let selectedName = "";
    let currentAction = "";
    const confirmModalEl = document.getElementById('confirmActionModal');
    const viewModalEl = document.getElementById('verificationModal');
    
    const confirmModal = confirmModalEl ? new bootstrap.Modal(confirmModalEl) : null;
    const viewModal = viewModalEl ? new bootstrap.Modal(viewModalEl) : null;

    function triggerConfirm(name, action) {
        selectedName = name;
        currentAction = action;
        
        const titleEl = document.getElementById('confirm-title');
        const textEl = document.getElementById('confirm-text');
        const iconBox = document.getElementById('confirm-icon-container');

        if (titleEl) titleEl.innerText = `Confirm ${action}`;
        if (textEl) textEl.innerText = `Apply ${action} to ${name}?`;
        
        if (iconBox) {
            iconBox.innerHTML = action === 'Approve' ? 
                '<span class="material-symbols-outlined text-success fs-1">verified_user</span>' : 
                '<span class="material-symbols-outlined text-danger fs-1">block</span>';
        }
        
        if (confirmModal) confirmModal.show();
    }

    // Event Delegation for Table Buttons
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('action-approve-btn')) {
            const name = e.target.closest('tr').querySelector('.entity-name').innerText;
            triggerConfirm(name, 'Approve');
        }
        
        if (e.target.classList.contains('action-reject-btn')) {
            const name = e.target.closest('tr').querySelector('.entity-name').innerText;
            triggerConfirm(name, 'Reject');
        }
    });

    // Modal Verify Button
    const verifyEntityBtn = document.getElementById('btn-verify-entity');
    if (verifyEntityBtn) {
        verifyEntityBtn.addEventListener('click', () => {
            const name = document.getElementById('modalEntityName').innerText;
            if (viewModal) viewModal.hide();
            // Small timeout to allow first modal to close before showing second
            setTimeout(() => triggerConfirm(name, 'Approve'), 300);
        });
    }

    // Confirm Submission
    const confirmSubmitBtn = document.getElementById('btn-confirm-submit');
    if (confirmSubmitBtn) {
        confirmSubmitBtn.addEventListener('click', () => {
            if (confirmModal) confirmModal.hide();
            
            const alert = document.getElementById('js-success-alert');
            const alertMsg = document.getElementById('alert-message');
            
            if (alert && alertMsg) {
                alert.classList.remove('d-none');
                alertMsg.innerText = `Employer ${selectedName} has been ${currentAction.toLowerCase()}d.`;
                setTimeout(() => { alert.classList.add('d-none'); }, 4000);
            }
        });
    }

    // 4. Initial URL Search check
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('search') && searchInput) {
        searchInput.value = urlParams.get('search');
        filterTable(urlParams.get('search'));
    }
});