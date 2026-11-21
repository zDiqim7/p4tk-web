(() => {
    const initialClasses = ['opacity-0', 'translate-y-8'];
    const visibleClasses = ['opacity-100', 'translate-y-0'];
    const showElement = (element) => {
        element.classList.remove(...initialClasses);
        element.classList.add(...visibleClasses, 'is-visible');
    };

    const observeMotion = 'IntersectionObserver' in window
        && !window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    if (!observeMotion) {
        const showRevealElements = (container = document) => {
            container.querySelectorAll('.reveal:not(.is-visible)').forEach(showElement);
        };

        showRevealElements();
        document.addEventListener('scroll-reveal:observe', () => showRevealElements());
        return;
    }

    const observer = new IntersectionObserver((entries, activeObserver) => {
        entries.forEach((entry) => {
            if (!entry.isIntersecting) {
                return;
            }

            showElement(entry.target);
            activeObserver.unobserve(entry.target);
        });
    }, { threshold: 0.15 });

    const observeRevealElements = (container = document) => {
        container.querySelectorAll('.reveal:not(.is-visible)').forEach((element) => observer.observe(element));
    };

    observeRevealElements();
    document.addEventListener('scroll-reveal:observe', () => observeRevealElements());
})();
