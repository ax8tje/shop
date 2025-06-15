document.addEventListener('DOMContentLoaded', () => {
    const burgerToggle = document.getElementById('burgerToggle');
    const sideMenu = document.getElementById('sideMenu');
    const burgerIcon = document.getElementById('burgerIcon');

    let menuOpen = false;

    burgerToggle.addEventListener('click', () => {
        menuOpen = !menuOpen;

        sideMenu.classList.toggle('active');

        burgerIcon.src = menuOpen
            ? 'assets/img/burger-menu-left-white.png'
            : 'assets/img/burger-menu-white.png';
    });
});
