document.addEventListener("DOMContentLoaded", () => {
    const products = document.querySelectorAll(".product-page-box");

    const observer = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add("visible");
                observer.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.1
    });

    products.forEach(product => {
        observer.observe(product);
    });
});