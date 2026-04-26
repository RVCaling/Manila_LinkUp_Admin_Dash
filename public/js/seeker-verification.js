document.addEventListener('DOMContentLoaded', function() {
    // 1. Data Store
    const seekersData = {
        1: {
            id: 1,
            code: 'SKR-2026-0127',
            name: 'Gojo Satoru',
            email: 'gojo.s@jujutsu.edu',
            location: 'Malate, Manila',
            status: 'Pending',
            documents: [
                { 
                    type: 'National ID', 
                    status: 'Pending', 
                    img: 'https://upload.wikimedia.org/wikipedia/en/thumb/9/96/SatoruGojomanga.png/250px-SatoruGojomanga.png',
                    meta: { "Full Name": "GOJO SATORU", "ID Number": "123-4567-890", "Expiry": "Dec 2030" }
                },
                { 
                    type: 'NBI Clearance', 
                    status: 'Pending', 
                    img: 'https://i.pinimg.com/originals/a4/d7/e5/a4d7e559545cae5b2e5af5ad3e9edbd0.jpg',
                    meta: { "Control No.": "NBI-9921-X", "Issued Date": "Jan 2026", "Remarks": "NO RECORD ON FILE" }
                }
            ]
        }
    };

    let activeSeekerId = null;
    let activeDocIndex = 0;

    // 2. Search Functionality & Dynamic Count
    const searchInput = document.getElementById('seekerSearchInput');
    const userCountText = document.getElementById('user-count-text');

    if(searchInput) {
        searchInput.addEventListener('input', function(e) {
            const query = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('#seekerTable tbody tr');
            let visibleCount = 0;

            rows.forEach(row => {
                const name = row.querySelector('.seeker-name').innerText.toLowerCase();
                const code = row.querySelector('.text-primary').innerText.toLowerCase();
                const isMatch = name.includes(query) || code.includes(query);
                
                row.style.display = isMatch ? '' : 'none';
                if(isMatch) visibleCount++;
            });

            // Update footer text
            userCountText.innerText = `Showing ${visibleCount} seekers in database`;
        });
    }

    // 3. Open Verification Modal
    window.openVerificationModal = function(id) {
        activeSeekerId = id;
        activeDocIndex = 0;
        const seeker = seekersData[id];

        document.getElementById('modalSeekerName').innerText = seeker.name;
        document.getElementById('modalSeekerCode').innerText = seeker.code;
        
        renderDocumentList();
        loadDocument(0);

        const vModal = new bootstrap.Modal(document.getElementById('verificationModal'));
        vModal.show();
    };

    // 4. Render Sidebar Document List
    function renderDocumentList() {
        const listContainer = document.getElementById('documentList');
        listContainer.innerHTML = '';
        
        seekersData[activeSeekerId].documents.forEach((doc, index) => {
            const isActive = index === activeDocIndex ? 'active bg-primary-subtle border-primary' : '';
            const statusIcon = doc.status === 'Verified' ? 'check_circle' : (doc.status === 'Rejected' ? 'cancel' : 'pending');
            const statusClass = doc.status === 'Verified' ? 'text-success' : (doc.status === 'Rejected' ? 'text-danger' : 'text-warning');

            const item = `
                <button onclick="loadDocument(${index})" class="list-group-item list-group-item-action border-0 rounded-3 mb-2 d-flex justify-content-between align-items-center ${isActive}">
                    <div class="small fw-bold">${doc.type}</div>
                    <span class="material-symbols-outlined fs-6 ${statusClass}">${statusIcon}</span>
                </button>
            `;
            listContainer.insertAdjacentHTML('beforeend', item);
        });
    }

    // 5. Load Specific Document Preview
    window.loadDocument = function(index) {
        activeDocIndex = index;
        const doc = seekersData[activeSeekerId].documents[index];
        
        document.getElementById('docImagePreview').src = doc.img;
        
        const metaContainer = document.getElementById('metadataFields');
        metaContainer.innerHTML = '';
        
        for (const [key, value] of Object.entries(doc.meta)) {
            metaContainer.innerHTML += `
                <div class="d-flex justify-content-between mb-2 pb-1 border-bottom">
                    <span class="extra-small text-muted text-uppercase">${key}</span>
                    <span class="small fw-bold">${value}</span>
                </div>
            `;
        }
        renderDocumentList();
    };

    // 6. Process Verification/Rejection
    window.processDoc = function(action) {
        if(action === 'verify') {
            seekersData[activeSeekerId].documents[activeDocIndex].status = 'Verified';
            showSuccess(`Document "${seekersData[activeSeekerId].documents[activeDocIndex].type}" approved.`);
            renderDocumentList();
            checkOverallStatus();
        } else {
            const rModal = new bootstrap.Modal(document.getElementById('rejectReasonModal'));
            rModal.show();
        }
    };

    window.confirmRejection = function() {
        const reason = document.getElementById('rejectReasonSelect').value;
        
        seekersData[activeSeekerId].documents[activeDocIndex].status = 'Rejected';
        seekersData[activeSeekerId].status = 'Rejected';
        
        bootstrap.Modal.getInstance(document.getElementById('rejectReasonModal')).hide();
        showSuccess(`Document rejected: ${reason}`);
        
        updateTableStatus('Rejected');
        renderDocumentList();
    };

    function checkOverallStatus() {
        const allVerified = seekersData[activeSeekerId].documents.every(d => d.status === 'Verified');
        if(allVerified) {
            seekersData[activeSeekerId].status = 'Verified';
            updateTableStatus('Verified');
            showSuccess(`All documents verified. Seeker is now Verified.`);
        }
    }

    function updateTableStatus(status) {
        const badge = document.querySelector('.seeker-status');
        badge.innerText = status;
        if(status === 'Verified') badge.className = "badge bg-success-subtle text-success border border-success px-3 seeker-status";
        if(status === 'Rejected') badge.className = "badge bg-danger-subtle text-danger border border-danger px-3 seeker-status";
    }

    function showSuccess(msg) {
        const alertBox = document.getElementById('js-success-alert');
        document.getElementById('alert-message').innerText = msg;
        alertBox.classList.remove('d-none');
        setTimeout(() => alertBox.classList.add('d-none'), 3000);
    }

    // Call shared notification renderer
    if (typeof renderNotifications === 'function') {
        renderNotifications();
    }
});