document.addEventListener('DOMContentLoaded', () => {
    const buttons = document.querySelectorAll('.profile-nav button');
    const views = document.querySelectorAll('.profile-view');

    buttons.forEach(btn => {
        btn.addEventListener('click', () => {
            buttons.forEach(b => b.classList.remove('active'));
            views.forEach(v => v.classList.remove('active'));

            btn.classList.add('active');
            document.getElementById(btn.dataset.target).classList.add('active');
        });
    });
});