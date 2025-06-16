document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.image-slider2').forEach(slider => {
        const sliderImages = slider.querySelector('.slider-images');
        const images = slider.querySelectorAll('.slide-image2');
        const leftArrow = slider.querySelector('.left-arrow2');
        const rightArrow = slider.querySelector('.right-arrow2');

        const thumbWrapper = document.querySelector('.thumbnail-slider-wrapper');
        const thumbnailSlider = document.querySelector('.thumbnail-slider');
        const thumbnails = document.querySelectorAll('.thumbnail-image');
        const leftThumb = document.querySelector('.left-thumb');
        const rightThumb = document.querySelector('.right-thumb');

        let currentIndex = 0;
        let thumbnailIndex = 0;
        const maxVisibleThumbs = 5;

        function updateSlider() {
            const offset = -currentIndex * 500;
            sliderImages.style.transform = `translateX(${offset}px)`;

            thumbnails.forEach((thumb, index) => {
                thumb.classList.toggle('active-thumbnail', index === currentIndex);
            });

            if (currentIndex < thumbnailIndex) {
                thumbnailIndex = currentIndex;
            } else if (currentIndex >= thumbnailIndex + maxVisibleThumbs) {
                thumbnailIndex = currentIndex - maxVisibleThumbs + 1;
            }
            const thumbOffset = -thumbnailIndex * 90;
            thumbnailSlider.style.transform = `translateX(${thumbOffset}px)`;
        }

        leftArrow.addEventListener('click', () => {
            currentIndex = (currentIndex - 1 + images.length) % images.length;
            updateSlider();
        });

        rightArrow.addEventListener('click', () => {
            currentIndex = (currentIndex + 1) % images.length;
            updateSlider();
        });

        thumbnails.forEach(thumb => {
            thumb.addEventListener('click', () => {
                currentIndex = parseInt(thumb.dataset.index);
                updateSlider();
            });
        });

        leftThumb.addEventListener('click', () => {
            if (thumbnailIndex > 0) {
                thumbnailIndex--;
                const offset = -thumbnailIndex * 90;
                thumbnailSlider.style.transform = `translateX(${offset}px)`;
            }
        });

        rightThumb.addEventListener('click', () => {
            if (thumbnailIndex < images.length - maxVisibleThumbs) {
                thumbnailIndex++;
                const offset = -thumbnailIndex * 90;
                thumbnailSlider.style.transform = `translateX(${offset}px)`;
            }
        });

        updateSlider();
    });
});
