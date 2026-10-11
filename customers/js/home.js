
document.addEventListener('DOMContentLoaded', () => {
    const menuToggle = document.getElementById('menuToggle');
    const navLinks = document.getElementById('navLinks');
    const dropdown = document.querySelector('.nav-dropdown');
    const dropdownToggle = document.querySelector('.dropdown-toggle');

    if (menuToggle && navLinks) {
        menuToggle.addEventListener('click', () => {
            const isOpen = navLinks.classList.toggle('open');

            menuToggle.setAttribute('aria-expanded', String(isOpen));

            if (!isOpen && dropdown && dropdownToggle) {
                dropdown.classList.remove('open');
                dropdownToggle.setAttribute('aria-expanded', 'false');
            }
        });

        navLinks.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
                navLinks.classList.remove('open');
                menuToggle.setAttribute('aria-expanded', 'false');
            });
        });
    }

    if (dropdown && dropdownToggle) {
        dropdownToggle.addEventListener('click', event => {
            event.stopPropagation();

            const isOpen = dropdown.classList.toggle('open');

            dropdownToggle.setAttribute('aria-expanded', String(isOpen));
        });

        document.addEventListener('click', event => {
            if (!dropdown.contains(event.target)) {
                dropdown.classList.remove('open');
                dropdownToggle.setAttribute('aria-expanded', 'false');
            }
        });

        document.addEventListener('keydown', event => {
            if (event.key === 'Escape') {
                dropdown.classList.remove('open');
                dropdownToggle.setAttribute('aria-expanded', 'false');
            }
        });
    }
});
