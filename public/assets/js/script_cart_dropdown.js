document.addEventListener('DOMContentLoaded', () => {
    const wrapper  = document.querySelector('.cart-wrapper');
    const dropdown = document.getElementById('cartDropdown');
    if (!wrapper || !dropdown) return;

    wrapper.addEventListener('mouseenter', () => {
        dropdown.classList.add('show');
    });
    wrapper.addEventListener('mouseleave', () => {
        dropdown.classList.remove('show');
    });
});