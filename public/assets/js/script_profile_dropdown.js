document.addEventListener('DOMContentLoaded', () => {
    const wrapper  = document.querySelector('.profile-wrapper');
    const dropdown = document.getElementById('profileDropdown');
    if (!wrapper || !dropdown) return;

    wrapper.addEventListener('mouseenter', () => {
        dropdown.classList.add('show');
    });

    wrapper.addEventListener('mouseleave', () => {
        dropdown.classList.remove('show');
    });
});

