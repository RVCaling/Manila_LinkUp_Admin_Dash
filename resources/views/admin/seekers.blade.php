<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manila LinkUp | Seeker Verification</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-verification.css') }}">
</head>
<body class="bg-light d-flex">

    @include('admin.sidebar')

    <div class="main-content w-100 p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="fw-bold mb-0" style="color: #1B3E9C;">Seeker Verification</h1>
                <p class="text-muted small">Manage and verify job seeker accounts for Manila City.</p>
            </div>
            <div class="notification-wrapper position-relative">
                <span class="material-symbols-outlined fs-2 text-muted" style="cursor: pointer;">notifications</span>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-circle bg-danger border border-light" style="padding: 5px; font-size: 10px;">3</span>
            </div>
        </div>

        <div id="js-success-alert" class="alert alert-success border-0 shadow-sm rounded-3 mb-4 d-none" role="alert">
            <span id="alert-message">Action processed successfully.</span>
        </div>

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-body p-4">
                <div class="d-flex mb-4">
                    <div class="input-group w-auto">
                        <input type="text" id="seekerSearchInput" class="form-control border-light bg-light rounded-start-pill px-4" placeholder="Search seeker..." style="min-width: 300px;">
                        <button class="btn btn-primary rounded-end-pill px-4" style="background-color: #1B3E9C; border: none;">Search</button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle custom-verification-table" id="seekerTable">
                        <thead>
                            <tr>
                                <th class="text-muted small fw-bold">#</th>
                                <th class="text-muted small fw-bold">Seeker Code</th>
                                <th class="text-muted small fw-bold">Name</th>
                                <th class="text-muted small fw-bold">Email</th>
                                <th class="text-muted small fw-bold">Document Type</th>
                                <th class="text-muted small fw-bold">Skills/Role</th>
                                <th class="text-muted small fw-bold">Location</th>
                                <th class="text-muted small fw-bold text-center">View</th>
                                <th class="text-muted small fw-bold text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="fw-bold">1</td>
                                <td><span class="badge bg-light text-dark border">MLU-2026-0127</span></td>
                                <td class="fw-bold seeker-name">Gojo Satoru</td>
                                <td class="text-muted">gojo.s@jujutsu.edu</td>
                                <td><span class="text-primary fw-bold small"><span class="material-symbols-outlined align-middle fs-6">badge</span> National ID</span></td>
                                <td><span class="badge rounded-pill text-primary" style="background-color: #e7f0ff;">Special Grade Sorcerer</span></td>
                                <td>Malate, Manila</td>
                                <td class="text-center">
                                    <button class="btn btn-dark btn-sm rounded-3 px-3" data-bs-toggle="modal" data-bs-target="#verificationModal"><span class="material-symbols-outlined fs-6 align-middle">visibility</span> View</button>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <button class="btn btn-success btn-sm rounded-3 px-3 action-approve-btn">Approve</button>
                                        <button class="btn btn-danger btn-sm rounded-3 px-3 action-reject-btn">Reject</button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="verificationModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 shadow">
                <div class="modal-header border-0 px-4 pt-4">
                    <div class="d-flex align-items-center">
                        <div class="bg-dark text-white rounded-3 p-2 me-3">
                            <span class="material-symbols-outlined align-middle">assignment_ind</span>
                        </div>
                        <div>
                            <h5 class="modal-title fw-bold mb-0">Identity Verification - <span id="modalSeekerName">Gojo Satoru</span></h5>
                            <small class="text-warning fw-bold"><span class="dot bg-warning"></span> Pending Review: Submission ID</small>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-4">
                        <div class="col-md-7">
                            <div class="bg-black rounded-4 p-2 d-flex align-items-center justify-content-center" style="min-height: 300px;">
                                <img src="https://upload.wikimedia.org/wikipedia/en/thumb/9/96/SatoruGojomanga.png/250px-SatoruGojomanga.png" class="img-fluid rounded-3" alt="ID Document Preview">
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="verification-checks mb-4">
                                <div class="check-item d-flex justify-content-between align-items-center bg-success-subtle p-2 rounded-3 mb-2 px-3">
                                    <span class="small fw-bold text-success"><span class="material-symbols-outlined fs-6 align-middle me-1">check_circle</span> ID Number Match</span>
                                    <span class="small text-success fw-bold">98%</span>
                                </div>
                            </div>

                            <div class="extracted-metadata">
                                <h6 class="text-muted small fw-bold text-uppercase mb-3">Extracted Metadata</h6>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="small text-muted">FULL NAME</span>
                                    <span class="small fw-bold">Gojo Satoru</span>
                                </div>
                                <div class="admin-notes">
                                    <label class="small text-muted fw-bold mb-2">VERIFICATION NOTES</label>
                                    <textarea class="form-control bg-light border-0 small" rows="3" placeholder="Add administrative notes here..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4 justify-content-end">
                    <button type="button" id="btn-request-clarification" class="btn btn-link text-danger text-decoration-none fw-bold me-3">Request Clarification</button>
                    <button type="button" id="btn-verify-seeker" class="btn btn-primary px-4 py-2" style="background-color: #1B3E9C; border-radius: 8px;">Verify Seeker</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="confirmActionModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 shadow">
                <div class="modal-body p-4 text-center">
                    <div id="confirm-icon-container" class="mb-3"></div>
                    <h5 class="fw-bold" id="confirm-title">Are you sure?</h5>
                    <p class="text-muted small" id="confirm-text">Do you want to process this seeker?</p>
                    <div class="d-grid gap-2">
                        <button type="button" id="btn-confirm-submit" class="btn btn-primary rounded-3">Confirm</button>
                        <button type="button" class="btn btn-light rounded-3" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // NEW: URL Redirection Logic
            const urlParams = new URLSearchParams(window.location.search);
            const searchQuery = urlParams.get('search');
            
            if (searchQuery) {
                const searchInput = document.getElementById('seekerSearchInput');
                searchInput.value = searchQuery;
                filterTable(searchQuery);
            }

            function filterTable(query) {
                const rows = document.querySelectorAll('#seekerTable tbody tr');
                rows.forEach(row => {
                    const name = row.querySelector('.seeker-name').innerText.toLowerCase();
                    if (name.includes(query.toLowerCase())) {
                        row.style.display = "";
                    } else {
                        row.style.display = "none";
                    }
                });
            }

            // Existing logic below
            let selectedSeeker = "";
            let currentAction = "";
            const confirmModal = new bootstrap.Modal(document.getElementById('confirmActionModal'));
            const viewModal = new bootstrap.Modal(document.getElementById('verificationModal'));

            function triggerConfirm(name, action) {
                selectedSeeker = name;
                currentAction = action;
                
                const title = document.getElementById('confirm-title');
                const text = document.getElementById('confirm-text');
                const iconBox = document.getElementById('confirm-icon-container');
                const confirmBtn = document.getElementById('btn-confirm-submit');

                if(action === 'Approve' || action === 'Verify') {
                    title.innerText = "Confirm Approval";
                    text.innerText = `Verify and approve account for ${name}?`;
                    iconBox.innerHTML = '<span class="material-symbols-outlined text-success fs-1">check_circle</span>';
                    confirmBtn.className = "btn btn-success rounded-3";
                } else if(action === 'Reject') {
                    title.innerText = "Confirm Rejection";
                    text.innerText = `Are you sure you want to reject ${name}?`;
                    iconBox.innerHTML = '<span class="material-symbols-outlined text-danger fs-1">cancel</span>';
                    confirmBtn.className = "btn btn-danger rounded-3";
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

            document.getElementById('btn-confirm-submit').addEventListener('click', function() {
                confirmModal.hide();
                const alert = document.getElementById('js-success-alert');
                alert.classList.remove('d-none');
                document.getElementById('alert-message').innerText = `Successfully processed ${currentAction} for ${selectedSeeker}.`;
                setTimeout(() => { alert.classList.add('d-none'); }, 4000);
            });
        });
    </script>
</body>
</html>