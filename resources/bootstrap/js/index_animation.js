document.addEventListener("DOMContentLoaded", function () {
    const fadeInElements = document.querySelectorAll('.fade-in');

    const handleFadeIn = () => {
        fadeInElements.forEach(el => {
            const rect = el.getBoundingClientRect();
            if (rect.top <= (window.innerHeight || document.documentElement.clientHeight)) {
                el.classList.add('visible');
            }
        });
    };

    // Check initially if any element is in view
    handleFadeIn();

    // Check on scroll
    window.addEventListener('scroll', handleFadeIn);
});

