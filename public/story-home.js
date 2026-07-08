(() => {
    const transitionDuration = 750;
    const body = document.body;
    const transition = document.querySelector('[data-page-transition]');
    const reduceMotionQuery = window.matchMedia
        ? window.matchMedia('(prefers-reduced-motion: reduce)')
        : { matches: false };
    let navigationQueued = false;

    const setTransitionHidden = (isHidden) => {
        if (transition) {
            transition.setAttribute('aria-hidden', isHidden ? 'true' : 'false');
        }
    };

    const markLoaded = () => {
        body.classList.add('loaded');
        body.classList.remove('page-leaving');
        setTransitionHidden(true);
    };

    setTransitionHidden(false);
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', markLoaded, { once: true });
    } else {
        markLoaded();
    }

    const shouldHandleLink = (link, event) => {
        if (
            event.defaultPrevented ||
            event.button !== 0 ||
            event.metaKey ||
            event.ctrlKey ||
            event.shiftKey ||
            event.altKey
        ) {
            return false;
        }

        if (link.hasAttribute('download') || link.hasAttribute('data-no-transition')) {
            return false;
        }

        const target = (link.getAttribute('target') || '').trim().toLowerCase();
        if (target && target !== '_self') {
            return false;
        }

        const rawHref = (link.getAttribute('href') || '').trim();
        if (!rawHref || rawHref.charAt(0) === '#') {
            return false;
        }

        let nextUrl;
        try {
            nextUrl = new URL(rawHref, window.location.href);
        } catch (error) {
            return false;
        }

        if (!['http:', 'https:'].includes(nextUrl.protocol)) {
            return false;
        }

        const currentUrl = new URL(window.location.href);
        if (nextUrl.origin !== currentUrl.origin || nextUrl.href === currentUrl.href) {
            return false;
        }

        if (
            nextUrl.pathname === currentUrl.pathname &&
            nextUrl.search === currentUrl.search &&
            nextUrl.hash
        ) {
            return false;
        }

        return nextUrl;
    };

    document.addEventListener('click', (event) => {
        const target = event.target;
        const link = target instanceof Element ? target.closest('a[href]') : null;

        if (!link) {
            return;
        }

        const nextUrl = shouldHandleLink(link, event);
        if (!nextUrl) {
            return;
        }

        event.preventDefault();

        if (navigationQueued) {
            return;
        }

        navigationQueued = true;
        setTransitionHidden(false);
        body.classList.add('page-leaving');

        const delay = reduceMotionQuery.matches ? 0 : transitionDuration;
        window.setTimeout(() => {
            window.location.assign(nextUrl.href);
        }, delay);
    });

    window.addEventListener('pageshow', (event) => {
        if (event.persisted) {
            markLoaded();
            navigationQueued = false;
        }
    });
})();

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
