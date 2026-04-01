(() => {
    const root = document.documentElement;
    const toggle = document.querySelector('[data-nav-toggle]');
    const panel = document.querySelector('[data-nav-panel]');
    const links = panel ? panel.querySelectorAll('a') : [];

    if (!toggle || !panel) {
        return;
    }

    const closeNav = () => {
        root.classList.remove('nav-open');
        toggle.setAttribute('aria-expanded', 'false');
    };

    const openNav = () => {
        root.classList.add('nav-open');
        toggle.setAttribute('aria-expanded', 'true');
    };

    toggle.addEventListener('click', () => {
        if (root.classList.contains('nav-open')) {
            closeNav();
            return;
        }

        openNav();
    });

    links.forEach((link) => {
        link.addEventListener('click', closeNav);
    });

    window.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            closeNav();
        }
    });

    window.addEventListener('resize', () => {
        if (window.innerWidth > 1120) {
            closeNav();
        }
    });
})();
