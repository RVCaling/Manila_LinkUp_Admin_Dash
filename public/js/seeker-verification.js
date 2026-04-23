document.addEventListener('DOMContentLoaded', function() {
    // 1. SEARCH Logic
    const searchInput = document.getElementById('seekerSearchInput');
    const tableBody = document.querySelector('#seekerTable tbody');

    function filterTable(query) {
        if(!tableBody) return;
        const rows = tableBody.querySelectorAll('tr:not(.no-result-row)');
        let visibleCount = 0;

        rows.forEach(row => {
            const nameCell = row.querySelector('.seeker-name');
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

        const existingNoResult = tableBody.querySelector('.no-result-row');
        if (visibleCount === 0) {
            if (!existingNoResult) {
                const noResultRow = document.createElement('tr');
                noResultRow.className = 'no-result-row';
                noResultRow.innerHTML = `<td colspan="9" class="text-center py-5 text-muted">
                    <span class="material-symbols-outlined fs-1 d-block mb-2">person_search</span>
                    User not found in database
                </td>`;
                tableBody.appendChild(noResultRow);
            }
        } else if (existingNoResult) {
            existingNoResult.remove();
        }
    }

    if(searchInput) {
        searchInput.addEventListener('input', (e) => filterTable(e.target.value));
    }

    // 2. Action Logic
    let selectedSeeker = "";
    let currentAction = "";
    const confirmModalEl = document.getElementById('confirmActionModal');
    const verificationModalEl = document.getElementById('verificationModal');
    
    const confirmModal = confirmModalEl ? new bootstrap.Modal(confirmModalEl) : null;
    const verificationModal = verificationModalEl ? new bootstrap.Modal(verificationModalEl) : null;

    function triggerConfirm(name, action) {
        selectedSeeker = name;
        currentAction = action;
        const title = document.getElementById('confirm-title');
        const text = document.getElementById('confirm-text');
        const iconBox = document.getElementById('confirm-icon-container');
        const confirmBtn = document.getElementById('btn-confirm-submit');

        if(action === 'Approve' || action === 'Verify') {
            title.innerText = "Confirm Verification";
            text.innerText = `Verify and approve account for ${name}?`;
            iconBox.innerHTML = '<span class="material-symbols-outlined text-success fs-1">verified_user</span>';
            confirmBtn.className = "btn btn-success rounded-3";
        } else if(action === 'Reject') {
            title.innerText = "Confirm Rejection";
            text.innerText = `Are you sure you want to reject ${name}?`;
            iconBox.innerHTML = '<span class="material-symbols-outlined text-danger fs-1">cancel</span>';
            confirmBtn.className = "btn btn-danger rounded-3";
        } else if(action === 'Clarification') {
            title.innerText = "Request Clarification";
            text.innerText = `Send clarification request to ${name}?`;
            iconBox.innerHTML = '<span class="material-symbols-outlined text-warning fs-1">error</span>';
            confirmBtn.className = "btn btn-warning rounded-3";
        }
        confirmModal.show();
    }

    document.querySelectorAll('.action-approve-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            const name = e.target.closest('tr').querySelector('.seeker-name').innerText;
            triggerConfirm(name, 'Approve');
        });
    });

    document.querySelectorAll('.action-reject-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            const name = e.target.closest('tr').querySelector('.seeker-name').innerText;
            triggerConfirm(name, 'Reject');
        });
    });

    const verifyBtn = document.getElementById('btn-verify-seeker');
    if(verifyBtn) {
        verifyBtn.addEventListener('click', function() {
            const name = document.getElementById('modalSeekerName').innerText;
            if(verificationModal) verificationModal.hide();
            triggerConfirm(name, 'Verify');
        });
    }

    const clarifyBtn = document.getElementById('btn-request-clarification');
    if(clarifyBtn) {
        clarifyBtn.addEventListener('click', function() {
            const notes = document.getElementById('adminNotesText').value.trim();
            const name = document.getElementById('modalSeekerName').innerText;
            if (notes === "") {
                alert("Please provide clarification details in the Verification Notes.");
                document.getElementById('adminNotesText').focus();
                return;
            }
            if(verificationModal) verificationModal.hide();
            triggerConfirm(name, 'Clarification');
        });
    }

    const finalSubmit = document.getElementById('btn-confirm-submit');
    if(finalSubmit) {
        finalSubmit.addEventListener('click', function() {
            confirmModal.hide();
            const alertBox = document.getElementById('js-success-alert');
            const alertMsg = document.getElementById('alert-message');
            alertBox.classList.remove('d-none');
            
            if(currentAction === 'Approve' || currentAction === 'Verify') {
                alertMsg.innerText = `Seeker ${selectedSeeker} is now verified successfully.`;
                document.querySelectorAll('tr').forEach(row => {
                    const nameCell = row.querySelector('.seeker-name');
                    if(nameCell && nameCell.innerText === selectedSeeker) {
                        const statusBadge = row.querySelector('.seeker-status');
                        statusBadge.innerText = "Verified";
                        statusBadge.className = "badge rounded-pill bg-success text-white seeker-status";
                    }
                });
            } else if(currentAction === 'Clarification') {
                alertMsg.innerText = `Clarification request sent to ${selectedSeeker}.`;
            }
            setTimeout(() => { alertBox.classList.add('d-none'); }, 4000);
        });
    }

    // Call shared notification renderer
    if (typeof renderNotifications === 'function') {
        renderNotifications();
    }
});