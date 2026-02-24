<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') — {{ config('app.name', 'LMS') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Dashboard CSS -->
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

    @stack('styles')
</head>

<body>
    <!-- Mobile Topbar -->
    <div class="mobile-topbar d-lg-none">
        <button class="btn btn-link text-white p-0" id="sidebarToggle">
            <i class="fas fa-bars fa-lg"></i>
        </button>
        <span class="fw-bold text-white">{{ config('app.name', 'LMS') }}</span>
        <div></div>
    </div>

    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay d-lg-none" id="sidebarOverlay"></div>

    <div class="d-flex">
        {{-- Sidebar --}}
        <nav class="sidebar d-flex flex-column" id="sidebar">
            <div class="sidebar-brand">
                <a href="{{ route('dashboard') }}">
                    <i class="fas fa-graduation-cap"></i>
                    <span>{{ config('app.name', 'LMS') }}</span>
                </a>
            </div>

            <div class="sidebar-menu">
                <div class="sidebar-label">Main</div>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                            href="{{ route('dashboard') }}">
                            <i class="fas fa-tachometer-alt"></i><span>Dashboard</span>
                        </a>
                    </li>
                </ul>

                <div class="sidebar-label">Academic</div>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('students.*') ? 'active' : '' }}"
                            href="{{ route('students.index') }}">
                            <i class="fas fa-users"></i><span>Students</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('lecturers.*') ? 'active' : '' }}"
                            href="{{ route('lecturers.index') }}">
                            <i class="fas fa-chalkboard-teacher"></i><span>Lecturers</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('courses.*') ? 'active' : '' }}"
                            href="{{ route('courses.index') }}">
                            <i class="fas fa-book"></i><span>Courses</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('batches.*') ? 'active' : '' }}"
                            href="{{ route('batches.index') }}">
                            <i class="fas fa-layer-group"></i><span>Batches</span>
                        </a>
                    </li>
                </ul>

                <div class="sidebar-label">Assessment</div>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('exams.*') ? 'active' : '' }}"
                            href="{{ route('exams.index') }}">
                            <i class="fas fa-clipboard-list"></i><span>Exams</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('results.*') ? 'active' : '' }}"
                            href="{{ route('results.index') }}">
                            <i class="fas fa-square-poll-vertical"></i><span>Results</span>
                        </a>
                    </li>
                </ul>

                <div class="sidebar-label">Communication</div>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('announcements.*') ? 'active' : '' }}"
                            href="{{ route('announcements.index') }}">
                            <i class="fas fa-bullhorn"></i><span>Announcements</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('resources.*') ? 'active' : '' }}"
                            href="{{ route('resources.index') }}">
                            <i class="fas fa-folder-open"></i><span>Resources</span>
                        </a>
                    </li>
                </ul>

                <div class="sidebar-label">System</div>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}"
                            href="{{ route('reports.index') }}">
                            <i class="fas fa-chart-pie"></i><span>Reports</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}"
                            href="{{ route('settings.edit') }}">
                            <i class="fas fa-cog"></i><span>Settings</span>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="sidebar-footer">
                <div class="sidebar-user">
                    <div class="sidebar-user-avatar">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div class="sidebar-user-info">
                        <div class="sidebar-user-name">{{ Auth::user()->name }}</div>
                        <div class="sidebar-user-role">{{ Auth::user()->email }}</div>
                    </div>
                </div>
                <button class="btn btn-sidebar-logout" type="button" data-bs-toggle="modal"
                    data-bs-target="#logoutModal">
                    <i class="fas fa-sign-out-alt"></i><span>Logout</span>
                </button>
                <form method="POST" action="{{ route('logout') }}" id="logoutForm" class="d-none">
                    @csrf
                </form>
            </div>
        </nav>

        {{-- Main content --}}
        <div class="main-content">
            <header class="main-header">
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-1">
                            @php $crumbs = explode(' / ', trim(View::yieldContent('breadcrumb'))); @endphp
                            @foreach($crumbs as $i => $crumb)
                            @if($i === count($crumbs) - 1)
                            <li class="breadcrumb-item active">{{ $crumb }}</li>
                            @else
                            <li class="breadcrumb-item">{{ $crumb }}</li>
                            @endif
                            @endforeach
                        </ol>
                    </nav>
                    <h4 class="mb-0 fw-bold">@yield('title', 'Dashboard')</h4>
                </div>
                <div class="header-actions">
                    <button class="btn btn-icon" id="darkModeToggle" title="Toggle dark mode">
                        <i class="fas fa-moon"></i>
                    </button>
                    <div class="header-user d-none d-md-flex">
                        <span>{{ Auth::user()->name }}</span>
                    </div>
                </div>
            </header>

            <main class="main-body">
                @include('partials.alerts')
                @yield('content')
            </main>
        </div>
    </div>

    {{-- Logout Confirmation Modal --}}
    <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content logout-modal-content">
                <div class="modal-body text-center p-4">
                    <div class="logout-modal-icon">
                        <i class="fas fa-sign-out-alt"></i>
                    </div>
                    <h5 class="fw-bold mb-2" id="logoutModalLabel">Confirm Logout</h5>
                    <p class="text-muted mb-4" style="font-size:.875rem">Are you sure you want to sign out of your
                        account?</p>
                    <div class="d-flex gap-2 justify-content-center">
                        <button type="button" class="btn btn-outline-secondary px-4"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-danger px-4" id="logoutConfirmBtn">
                            <i class="fas fa-sign-out-alt me-1"></i> Logout
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Generic Action Confirmation Modal --}}
    <div class="modal fade" id="actionConfirmModal" tabindex="-1" aria-labelledby="actionConfirmModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content logout-modal-content">
                <div class="modal-body text-center p-4">
                    <div class="logout-modal-icon" style="background:rgba(220,53,69,.12);color:#dc3545">
                        <i class="fas fa-triangle-exclamation"></i>
                    </div>
                    <h5 class="fw-bold mb-2" id="actionConfirmModalLabel">Confirm Action</h5>
                    <p class="text-muted mb-4" id="actionConfirmModalMessage" style="font-size:.875rem">Are you sure you
                        want to continue?</p>
                    <div class="d-flex gap-2 justify-content-center">
                        <button type="button" class="btn btn-outline-secondary px-4"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-danger px-4" id="actionConfirmBtn">
                            <i class="fas fa-check me-1"></i> Confirm
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Container -->
    <div class="toast-container position-fixed bottom-0 end-0 p-3" id="toastContainer"></div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>

    <script>
        // Dark mode
    (function() {
        const html = document.documentElement;
        const toggle = document.getElementById('darkModeToggle');
        const saved = localStorage.getItem('lms-theme');
        if (saved) html.setAttribute('data-bs-theme', saved);
        if (toggle) {
            toggle.addEventListener('click', function() {
                const current = html.getAttribute('data-bs-theme');
                const next = current === 'dark' ? 'light' : 'dark';
                html.setAttribute('data-bs-theme', next);
                localStorage.setItem('lms-theme', next);
                this.querySelector('i').className = next === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
            });
            if (html.getAttribute('data-bs-theme') === 'dark') {
                toggle.querySelector('i').className = 'fas fa-sun';
            }
        }
    })();

    // Mobile sidebar
    (function() {
        const sidebar = document.getElementById('sidebar');
        const toggle = document.getElementById('sidebarToggle');
        const overlay = document.getElementById('sidebarOverlay');
        function close() { sidebar.classList.remove('show'); overlay.classList.remove('show'); }
        if (toggle) toggle.addEventListener('click', function() {
            sidebar.classList.toggle('show');
            overlay.classList.toggle('show');
        });
        if (overlay) overlay.addEventListener('click', close);
    })();

    // Logout confirmation
    (function() {
        const btn = document.getElementById('logoutConfirmBtn');
        const form = document.getElementById('logoutForm');
        if (btn && form) btn.addEventListener('click', function() { form.submit(); });
    })();

    // Global confirmation dialog for CRUD actions
    // Uses CLICK interception (fires before form submit even starts)
    (function() {
        var modalEl = document.getElementById('actionConfirmModal');
        var msgEl   = document.getElementById('actionConfirmModalMessage');
        var titleEl = document.getElementById('actionConfirmModalLabel');
        var okBtn   = document.getElementById('actionConfirmBtn');
        if (!modalEl || !okBtn) return;

        var bsModal = null;
        var pendingForm = null;

        function getModal() {
            if (!bsModal && typeof bootstrap !== 'undefined') {
                bsModal = new bootstrap.Modal(modalEl);
            }
            return bsModal;
        }

        // When user clicks "Confirm" in the modal → submit the form natively
        okBtn.addEventListener('click', function() {
            var f = pendingForm;
            pendingForm = null;
            var m = getModal();
            if (m) m.hide();
            if (f) HTMLFormElement.prototype.submit.call(f);
        });

        modalEl.addEventListener('hidden.bs.modal', function() { pendingForm = null; });

        // Capture-phase CLICK listener — catches button clicks before submit fires
        document.addEventListener('click', function(e) {
            var clickedBtn = e.target.closest('form.js-confirm-action button, form.js-confirm-action [type="submit"]');
            if (!clickedBtn) return;
            var form = clickedBtn.closest('form.js-confirm-action');
            if (!form) return;

            // Block the click so the form never fires a submit event
            e.preventDefault();
            e.stopImmediatePropagation();

            var msg   = form.getAttribute('data-confirm') || 'Are you sure you want to continue?';
            var title = form.getAttribute('data-confirm-title') || 'Confirm Action';
            if (msgEl)   msgEl.textContent  = msg;
            if (titleEl) titleEl.textContent = title;
            pendingForm = form;

            var m = getModal();
            if (m) m.show();
        }, true);

        // Also block submit as a safety net (in case Enter key submits the form)
        document.addEventListener('submit', function(e) {
            var form = e.target;
            if (form && form.classList && form.classList.contains('js-confirm-action')) {
                e.preventDefault();
                e.stopImmediatePropagation();
                var msg   = form.getAttribute('data-confirm') || 'Are you sure you want to continue?';
                var title = form.getAttribute('data-confirm-title') || 'Confirm Action';
                if (msgEl)   msgEl.textContent  = msg;
                if (titleEl) titleEl.textContent = title;
                pendingForm = form;
                var m = getModal();
                if (m) m.show();
            }
        }, true);
    })();

    // Toast helper
    function showToast(message, type = 'success') {
        const container = document.getElementById('toastContainer');
        const icons = { success: 'fa-check-circle', danger: 'fa-circle-exclamation', warning: 'fa-exclamation-triangle', info: 'fa-info-circle' };
        const toast = document.createElement('div');
        toast.className = 'toast align-items-center border-0 text-bg-' + type;
        toast.setAttribute('role', 'alert');
        toast.innerHTML = '<div class="d-flex"><div class="toast-body"><i class="fas ' + (icons[type] || icons.info) + ' me-2"></i>' + message + '</div><button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button></div>';
        container.appendChild(toast);
        new bootstrap.Toast(toast, { delay: 4000 }).show();
        toast.addEventListener('hidden.bs.toast', function() { toast.remove(); });
    }

    @if(session('success'))
    showToast(@json(session('success')), 'success');
    @endif
    @if(session('error'))
    showToast(@json(session('error')), 'danger');
    @endif
    @if(session('warning'))
    showToast(@json(session('warning')), 'warning');
    @endif
    @if(session('info'))
    showToast(@json(session('info')), 'info');
    @endif
    @if(session('status'))
    (function() {
        const statusMap = {
            'profile-updated': 'Profile updated successfully.',
            'password-updated': 'Password updated successfully.',
            'verification-link-sent': 'Verification link has been sent.',
        };
        const status = @json(session('status'));
        showToast(statusMap[status] || status, 'info');
    })();
    @endif
    </script>

    @stack('scripts')
</body>

</html>