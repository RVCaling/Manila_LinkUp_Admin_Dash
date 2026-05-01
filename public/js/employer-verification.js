document.addEventListener('DOMContentLoaded', function() {
    // 1. Initialize Notifications
    if (typeof renderNotifications === 'function') {
        renderNotifications();
    }

    // 2. SEARCH & FILTER Logic (Updated for Document Type and User Count)
    const searchInput = document.getElementById('employerSearchInput');
    const tableBody = document.querySelector('#employerTable tbody');
    const userCountText = document.getElementById('user-count-text');
    
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

        // Update User Count Text
        if (visibleCount === 0) {
            userCountText.innerText = "Showing 0 users in database";
        } else {
            userCountText.innerText = `Showing ${visibleCount} employers in database`;
        }

        // Handle No Result Row
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

    // 3. Document Selector Logic
    const docButtons = document.querySelectorAll('#documentList .list-group-item');
    const previewImg = document.getElementById('docImagePreview');

    docButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            docButtons.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            previewImg.src = this.getAttribute('data-doc-src');
        });
    });

    // 4. Verification & Rejection Logic
    const viewModal = new bootstrap.Modal(document.getElementById('verificationModal'));
    const rejectModal = new bootstrap.Modal(document.getElementById('rejectReasonModal'));
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    let currentEmployerName = "";
    let currentEmployerUid  = "";

    function callVerificationApi(url) {
        return fetch(url, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
        });
    }

    // Open Main Modal
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.view-docs-btn');
        if (btn) {
            currentEmployerUid  = btn.getAttribute('data-uid');
            currentEmployerName = btn.getAttribute('data-name');
            document.getElementById('modalEntityName').innerText = currentEmployerName;
        }
    });

    // Handle Approval
    document.getElementById('btn-verify-entity').addEventListener('click', () => {
        callVerificationApi(`/admin/employers/${currentEmployerUid}/verify`);
        showSuccessAlert(`${currentEmployerName} has been successfully verified.`);
        viewModal.hide();
    });

    // Show Reject Reason Modal
    document.getElementById('btn-show-reject-reason').addEventListener('click', () => {
        viewModal.hide();
        setTimeout(() => rejectModal.show(), 400);
    });

    // Toggle "Other" Reason Textarea
    const reasonSelect = document.getElementById('reject-reason-select');
    const otherText = document.getElementById('other-reason-text');
    reasonSelect.addEventListener('change', () => {
        if (reasonSelect.value === 'other') {
            otherText.classList.remove('d-none');
        } else {
            otherText.classList.add('d-none');
        }
    });

    // Confirm Rejection
    document.getElementById('btn-confirm-reject').addEventListener('click', () => {
        const reason = reasonSelect.value === 'other' ? otherText.value : reasonSelect.value;
        if(!reason) { alert("Please provide a reason"); return; }

        callVerificationApi(`/admin/employers/${currentEmployerUid}/reject`);
        rejectModal.hide();
        showSuccessAlert(`${currentEmployerName} verification rejected: ${reason}`);
    });

    function showSuccessAlert(message) {
        const alert = document.getElementById('js-success-alert');
        const alertMsg = document.getElementById('alert-message');
        if (alert && alertMsg) {
            alert.classList.remove('d-none');
            alertMsg.innerText = message;
            setTimeout(() => { alert.classList.add('d-none'); }, 5000);
        }
    }
});