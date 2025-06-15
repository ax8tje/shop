document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.image-slider').forEach(slider => {
        const sliderImages = slider.querySelector('.slider-images');
        const images = slider.querySelectorAll('.slide-image');
        const leftArrow = slider.querySelector('.left-arrow');
        const rightArrow = slider.querySelector('.right-arrow');

        let currentIndex = 0;

        function updateSlider() {
            const offset = -currentIndex * 500;
            sliderImages.style.transform = `translateX(${offset}px)`;
        }

        leftArrow.addEventListener('click', () => {
            currentIndex = (currentIndex - 1 + images.length) % images.length;
            updateSlider();
        });

        rightArrow.addEventListener('click', () => {
            currentIndex = (currentIndex + 1) % images.length;
            updateSlider();
        });

        updateSlider();
    });
});