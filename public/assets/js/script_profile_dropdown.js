document.addEventListener('DOMContentLoaded', () => {
    const toggle = document.getElementById('profileToggle');
    const dropdown = document.getElementById('profileDropdown');

    if (!toggle || !dropdown) return;

    toggle.addEventListener('click', (e) => {
        e.preventDefault();
        dropdown.classList.toggle('show');
    });

    document.addEventListener('click', (e) => {
        if (!toggle.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.classList.remove('show');
        }
    });
});