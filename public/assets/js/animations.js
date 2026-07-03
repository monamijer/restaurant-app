document.addEventListener('DOMContentLoaded', function () {
    const elements = document.querySelectorAll('.animate-on-scroll');

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                observer.unobserve(entry.target); // ne s'anime qu'une fois
            }
        });
    }, {
        threshold: 0.15 // se déclenche quand 15% de l'élément est visible
    });

    elements.forEach(el => observer.observe(el));
});