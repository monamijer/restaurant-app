document.addEventListener('DOMContentLoaded', function () {
    const elements = document.querySelectorAll('.animate-on-scroll');

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                observer.unobserve(entry.target); // animate just once.
            }
        });
    }, {
        threshold: 0.15 // trigger when 15% of element is visible
    });

    elements.forEach(el => observer.observe(el));
});