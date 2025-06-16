document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.image-slider1').forEach(slider => {
        const sliderImages = slider.querySelector('.slider-images');
        const images = slider.querySelectorAll('.slide-image1');
        const leftArrow = slider.querySelector('.left-arrow1');
        const rightArrow = slider.querySelector('.right-arrow1');

        let currentIndex = 0;

        function updateSlider() {
            const offset = -currentIndex * 300;
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

    document.querySelectorAll('.buy-button').forEach(btn => {
        btn.addEventListener('click', () => {
            const productId = btn.dataset.id;
            alert(`Produkt ${productId} dodany do koszyka!`);
        });
    });
});
