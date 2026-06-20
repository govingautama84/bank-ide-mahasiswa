document.addEventListener('DOMContentLoaded', () => {
    // Delete Confirmation
    const deleteButtons = document.querySelectorAll('.btn-delete');
    deleteButtons.forEach(btn => {
        btn.addEventListener('click', (e) => {
            if (!confirm('Apakah Anda yakin ingin menghapus data ini?')) {
                e.preventDefault();
            }
        });
    });

    // Simple Mobile Menu Toggle
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
                nav.style.background = 'white';
                nav.style.padding = '1rem';
                nav.style.boxShadow = '0 4px 6px -1px rgba(0,0,0,0.1)';
            } else {
                nav.style.display = 'none';
            }
        });

        navbar.insertBefore(mobileMenuBtn, nav);
    }
});
