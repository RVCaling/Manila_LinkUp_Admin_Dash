document.addEventListener('DOMContentLoaded', function() {
    let selectedEntity = "";
    let currentAction = "";
    
    const confirmModal = new bootstrap.Modal(document.getElementById('confirmActionModal'));
    const viewModal = new bootstrap.Modal(document.getElementById('verificationModal'));

    // Function to trigger confirmation
    window.triggerConfirm = function(name, action) {
        selectedEntity = name;
        currentAction = action;
        
        const title = document.getElementById('confirm-title');
        const text = document.getElementById('confirm-text');
        const iconBox = document.getElementById('confirm-icon-container');
        
        if(action === 'Approve' || action === 'Verify') {
            title.innerText = "Confirm Verification";
            text.innerText = `Verify ${name}? This will allow them to post jobs immediately.`;
            iconBox.innerHTML = '<span class="material-symbols-outlined text-success fs-1">verified_user</span>';
        } else if(action === 'Reject') {
            title.innerText = "Confirm Rejection";
            text.innerText = `Are you sure you want to reject ${name}'s application?`;
            iconBox.innerHTML = '<span class="material-symbols-outlined text-danger fs-1">block</span>';
        }

        confirmModal.show();
    }

    // Event Listeners for Table Buttons
    document.querySelectorAll('.action-approve-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            const name = e.target.closest('tr').querySelector('.company-name').innerText;
            triggerConfirm(name, 'Approve');
        });
    });

    // Event Listeners for Modal Buttons
    document.getElementById('btn-verify-employer').addEventListener('click', () => {
        const name = document.getElementById('modalCompanyName').innerText;
        viewModal.hide();
        triggerConfirm(name, 'Verify');
    });

    // Final Process
    document.getElementById('btn-confirm-submit').addEventListener('click', function() {
        confirmModal.hide();
        const alert = document.getElementById('js-success-alert');
        alert.classList.remove('d-none');
        document.getElementById('alert-message').innerText = `Success: ${selectedEntity} has been ${currentAction.toLowerCase()}ed.`;
        
        setTimeout(() => { alert.classList.add('d-none'); }, 4000);
    });
});