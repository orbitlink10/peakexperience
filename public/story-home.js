(() => {
    const setupNav = () => {
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
    };

    const setupVideoCovers = () => {
        const frames = document.querySelectorAll('.hero-stage-video, .service-showcase-video-frame');
        if (!frames.length) {
            return;
        }

        const aspectRatio = 16 / 9;

        const resizeFrame = (frame) => {
            const iframe = frame.querySelector('iframe');
            if (!iframe) {
                return;
            }

            const { clientWidth, clientHeight } = frame;
            if (!clientWidth || !clientHeight) {
                return;
            }

            let width = clientWidth;
            let height = width / aspectRatio;

            if (height < clientHeight) {
                height = clientHeight;
                width = height * aspectRatio;
            }

            const overscan = frame.classList.contains('service-showcase-video-frame') ? 1.18 : 1.08;

            iframe.style.width = `${width * overscan}px`;
            iframe.style.height = `${height * overscan}px`;
        };

        const resizeAll = () => {
            frames.forEach((frame) => {
                resizeFrame(frame);
            });
        };

        if ('ResizeObserver' in window) {
            const observer = new ResizeObserver((entries) => {
                entries.forEach((entry) => {
                    resizeFrame(entry.target);
                });
            });

            frames.forEach((frame) => {
                observer.observe(frame);
            });
        }

        resizeAll();
        window.addEventListener('load', resizeAll);
        window.addEventListener('resize', resizeAll);
    };

    setupNav();
    setupVideoCovers();
})();
