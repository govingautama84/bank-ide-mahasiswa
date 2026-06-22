document.addEventListener('DOMContentLoaded', () => {
    // 1. Initialize Toast Container
    const toastContainer = document.createElement('div');
    toastContainer.className = 'toast-container';
    document.body.appendChild(toastContainer);

    // Toast Function
    window.showToast = (message, type = 'success') => {
        const toast = document.createElement('div');
        toast.className = `toast ${type}`;
        
        let icon = type === 'success' ? '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>' : '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>';
        
        toast.innerHTML = `
            <div class="toast-icon">${icon}</div>
            <div class="toast-content">${message}</div>
            <button class="toast-close">&times;</button>
        `;
        
        toastContainer.appendChild(toast);
        
        // Trigger animation
        setTimeout(() => toast.classList.add('show'), 50);
        
        // Close event
        const closeBtn = toast.querySelector('.toast-close');
        closeBtn.addEventListener('click', () => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 400);
        });
        
        // Auto remove
        setTimeout(() => {
            if(toast.parentElement) {
                toast.classList.remove('show');
                setTimeout(() => toast.remove(), 400);
            }
        }, 5000);
    };

    // 2. Convert PHP Alerts to Toasts
    const phpAlerts = document.querySelectorAll('.alert');
    phpAlerts.forEach(alert => {
        const msg = alert.innerText.trim();
        if(msg) {
            const isDanger = alert.classList.contains('alert-danger');
            showToast(msg, isDanger ? 'danger' : 'success');
        }
        // Remove from DOM to keep it clean
        alert.remove();
    });

    // 3. Custom Modal UI for Confirmations
    const modalOverlay = document.createElement('div');
    modalOverlay.className = 'modal-overlay';
    modalOverlay.innerHTML = `
        <div class="modal-box">
            <div class="modal-icon"><svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg></div>
            <h3 class="modal-title">Konfirmasi Hapus</h3>
            <p class="modal-text">Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.</p>
            <div class="modal-actions">
                <button class="btn btn-outline" id="modal-cancel">Batal</button>
                <button class="btn btn-danger" id="modal-confirm">Ya, Hapus</button>
            </div>
        </div>
    `;
    document.body.appendChild(modalOverlay);

    let confirmCallback = null;

    document.getElementById('modal-cancel').addEventListener('click', () => {
        modalOverlay.classList.remove('active');
        confirmCallback = null;
    });

    document.getElementById('modal-confirm').addEventListener('click', () => {
        if(confirmCallback) {
            confirmCallback();
        }
        modalOverlay.classList.remove('active');
    });

    const deleteButtons = document.querySelectorAll('.btn-delete');
    deleteButtons.forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const href = btn.getAttribute('href');
            
            confirmCallback = () => {
                window.location.href = href;
            };
            
            modalOverlay.classList.add('active');
        });
    });

    // 4. Simple Mobile Menu Toggle
    const mobileMenuBtn = document.createElement('button');
    mobileMenuBtn.className = 'btn btn-outline mobile-menu-btn';
    mobileMenuBtn.innerHTML = '☰ Menu';
    mobileMenuBtn.style.display = 'none';

    const navbar = document.querySelector('.navbar .container');
    const nav = document.querySelector('.navbar-nav');

    if (navbar && nav) {
        if (window.innerWidth <= 768) {
            mobileMenuBtn.style.display = 'block';
            nav.style.display = 'none';
        }

        window.addEventListener('resize', () => {
            if (window.innerWidth <= 768) {
                mobileMenuBtn.style.display = 'block';
                if (!nav.classList.contains('show')) nav.style.display = 'none';
            } else {
                mobileMenuBtn.style.display = 'none';
                nav.style.display = 'flex';
                nav.style.flexDirection = 'row';
                nav.style.position = 'static';
                nav.style.boxShadow = 'none';
                nav.style.background = 'transparent';
                nav.style.padding = '0';
            }
        });

        mobileMenuBtn.addEventListener('click', () => {
            if (nav.style.display === 'none') {
                nav.style.display = 'flex';
                nav.style.flexDirection = 'column';
                nav.style.position = 'absolute';
                nav.style.top = '100%';
                nav.style.left = '0';
                nav.style.width = '100%';
                nav.style.background = 'var(--glass-bg)';
                nav.style.backdropFilter = 'blur(16px)';
                nav.style.padding = '1.5rem';
                nav.style.boxShadow = 'var(--glass-shadow)';
                nav.classList.add('show');
            } else {
                nav.style.display = 'none';
                nav.classList.remove('show');
            }
        });

        navbar.insertBefore(mobileMenuBtn, nav);
    }
});
