document.addEventListener('DOMContentLoaded', () => {
    const toggle   = document.getElementById('profileToggle');
    const dropdown = document.getElementById('profileDropdown');
    if (!toggle || !dropdown) return;

    toggle.addEventListener('click', e => {
        e.preventDefault();
        e.stopPropagation();
        dropdown.classList.toggle('show');
    });

    // Kliknięcie wewnątrz menu nie powinno go zamykać:
    dropdown.addEventListener('click', e => e.stopPropagation());

    // Klik gdziekolwiek poza menu zamyka dropdown:
    document.addEventListener('click', () => {
        dropdown.classList.remove('show');
    });
});
