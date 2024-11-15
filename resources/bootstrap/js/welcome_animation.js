document.addEventListener('DOMContentLoaded', function () {
    const elements = document.querySelectorAll('.fade-in-up');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('fade-in-up');
                observer.unobserve(entry.target); // Optional: stop observing after the element is animated
            }
        });
    });

    elements.forEach(element => {
        observer.observe(element);
    });
});
