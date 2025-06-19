document.addEventListener('DOMContentLoaded', () => {
    const toggle   = document.getElementById('cartToggle');
    const dropdown = document.getElementById('cartDropdown');
    if (!toggle || !dropdown) return;

    toggle.addEventListener('click', e => {
        e.preventDefault();
        e.stopPropagation();
        dropdown.classList.toggle('show');
    });

    dropdown.addEventListener('click', e => e.stopPropagation());

    document.addEventListener('click', () => {
        dropdown.classList.remove('show');
    });
});