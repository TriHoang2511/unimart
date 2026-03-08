document.addEventListener('DOMContentLoaded', () => {
    const sectionDetail = document.querySelector('#post-product-wp .section-detail');
    const buttonShowMore = document.querySelector('#post-product-wp .button .show-more');
    const buttonShowLess = document.querySelector('#post-product-wp .button .show-less');
    const button = document.querySelector('#post-product-wp .button');
    const arrowIcon = document.querySelector('#post-product-wp .button .fa-solid'); // Cụ thể hơn với .fa-solid

    const initial = {
        showAllContent: true, // true => show full content
        currentIcon: 'fa-chevron-down', // Theo dõi biểu tượng hiện tại
    };

    // Kiểm tra sự tồn tại của các phần tử
    if (!button || !sectionDetail || !buttonShowMore || !buttonShowLess || !arrowIcon) {
        console.warn('One or more elements not found in #post-product-wp. Check if #post-product-wp exists or is added dynamically.');
        console.warn({
            button: !!button,
            sectionDetail: !!sectionDetail,
            buttonShowMore: !!buttonShowMore,
            buttonShowLess: !!buttonShowLess,
            arrowIcon: !!arrowIcon
        });
        return;
    }

    button.addEventListener('click', () => {
        if (initial.showAllContent) {
            // Hiển thị nội dung đầy đủ
            showButton(buttonShowLess, true);
            showButton(buttonShowMore, false);
            sectionDetail.classList.remove('gradient', 'maxHeight');
            changeIcon(arrowIcon, 'fa-chevron-down', 'fa-chevron-up');
            initial.currentIcon = 'fa-chevron-up';
        } else {
            // Ẩn bớt nội dung
            showButton(buttonShowMore, true);
            showButton(buttonShowLess, false);
            sectionDetail.classList.add('gradient', 'maxHeight');
            changeIcon(arrowIcon, 'fa-chevron-up', 'fa-chevron-down');
            initial.currentIcon = 'fa-chevron-down';
        }

        initial.showAllContent = !initial.showAllContent;
    });

    function changeIcon(element, currentIcon, newIcon) {
        if (element && currentIcon && newIcon) {
            element.classList.remove(currentIcon);
            element.classList.add(newIcon);
        } else {
            console.warn('Invalid parameters in changeIcon:', { element, currentIcon, newIcon });
        }
    }

    function showButton(button, visible = true) {
        if (button) {
            button.classList.toggle('d-none', !visible);
        } else {
            console.warn('Button element is null in showButton');
        }
    }
});

document.addEventListener('DOMContentLoaded', () => {
    const mainImageSlider = document.getElementById('main-image-slider');
    const mainImageWrapper = document.querySelector('.main-image-wrapper');
    const mainSlideItems = document.querySelectorAll('.main-slide-item');
    const modal = document.getElementById('image-slider-modal');
    const closeModal = document.querySelector('.close-modal');
    const thumbItems = document.querySelectorAll('#list-thumb .thumb-item');
    const listPrevBtn = document.querySelector('#list-thumb .prev-btn');
    const listNextBtn = document.querySelector('#list-thumb .next-btn');
    const sliderItems = document.querySelectorAll('#image-slider .slider-item');
    const sliderPrevBtn = document.querySelector('#image-slider .prev-btn');
    const sliderNextBtn = document.querySelector('#image-slider .next-btn');
    const listThumbWrapper = document.querySelector('.list-thumb-wrapper');

    let currentThumbIndex = 0;
    let currentSlideIndex = 0;
    const numSlides = mainSlideItems.length; // Số lượng ảnh

    // Log để kiểm tra số lượng slide
    // console.log('Number of slides:', numSlides);

    // Hàm slide main image (hiệu ứng translateX)
    function showThumbImage(index) {
        if (index < 0 || index >= numSlides || !mainImageWrapper) return;
        mainImageWrapper.style.transform = `translateX(${-index * 100}%)`;
        thumbItems.forEach((item, i) => {
            item.classList.toggle('active', i === index);
        });
        currentThumbIndex = index;
        updateButtonStates();
    }

    // Hàm slide thumbnail với translate3d (tránh cắt, center item)
    function scrollToThumb(index) {
        if (!thumbItems[index] || !listThumbWrapper) return;
        showThumbImage(index); // Slide main image

        const thumbWidth = thumbItems[0].offsetWidth + 10; // Margin 5px * 2
        const containerWidth = listThumbWrapper.parentElement.clientWidth; // Container cha
        const scrollPosition = thumbWidth * index - (containerWidth - thumbWidth) / 2;

        // Giới hạn vị trí để tránh translate quá mức
        const maxScroll = (thumbItems.length - 1) * thumbWidth - containerWidth;
        const boundedPosition = Math.max(0, Math.min(scrollPosition, maxScroll));
        
        listThumbWrapper.style.transform = `translate3d(${-boundedPosition}px, 0, 0)`;
    }

    // Hàm cập nhật trạng thái nút prev/next
    function updateButtonStates() {
        if (!listPrevBtn || !listNextBtn) return;
        listPrevBtn.style.display = currentThumbIndex > 0 ? 'flex' : 'none';
        listNextBtn.style.display = currentThumbIndex < thumbItems.length - 1 ? 'flex' : 'none';
    }

    // Hàm navigate
    function navigate(index, itemsLength) {
        if (itemsLength === 0) return 0;
        if (index < 0) return itemsLength - 1;
        if (index >= itemsLength) return 0;
        return index;
    }

    // Xử lý click thumbnail (chỉ slide main image)
    thumbItems.forEach((item, index) => {
        item.querySelector('a').addEventListener('click', (e) => {
            e.preventDefault();
            showThumbImage(index);
        });
    });

    // Xử lý next/prev thumbnail (slide thumbnail + main)
    if (listNextBtn && listPrevBtn) {
        listNextBtn.addEventListener('click', () => {
            currentThumbIndex = navigate(currentThumbIndex + 1, thumbItems.length);
            scrollToThumb(currentThumbIndex);
        });
        listPrevBtn.addEventListener('click', () => {
            currentThumbIndex = navigate(currentThumbIndex - 1, thumbItems.length);
            scrollToThumb(currentThumbIndex);
        });
    }

    // Khởi tạo main slider
    if (numSlides > 0) {
        showThumbImage(0);
    }

    // Xử lý click vào tất cả ảnh trong main slider để mở modal
    mainSlideItems.forEach((item, index) => {
        const img = item.querySelector('.main-image');
        if (img) {
            img.addEventListener('click', () => {
                currentSlideIndex = index; // Đồng bộ với index của ảnh được click
                showSlide(currentSlideIndex);
                modal.style.display = 'block';
                document.body.style.overflow = 'hidden';
            });
        }
    });

    // Đóng modal
    if (closeModal) {
        closeModal.addEventListener('click', () => {
            modal.style.display = 'none';
            document.body.style.overflow = '';
        });
    }
    modal.addEventListener('click', (event) => {
        if (event.target === modal) {
            modal.style.display = 'none';
            document.body.style.overflow = '';
        }
    });

    // Xử lý next/prev modal
    if (sliderNextBtn && sliderPrevBtn) {
        sliderNextBtn.addEventListener('click', () => {
            currentSlideIndex = navigate(currentSlideIndex + 1, numSlides);
            showSlide(currentSlideIndex);
        });
        sliderPrevBtn.addEventListener('click', () => {
            currentSlideIndex = navigate(currentSlideIndex - 1, numSlides);
            showSlide(currentSlideIndex);
        });
    }

    // Hàm slide modal (sử dụng class .active)
    function showSlide(index) {
        if (index < 0 || index >= numSlides || !sliderItems.length) return;
        sliderItems.forEach((item, i) => {
            item.classList.toggle('active', i === index);
        });
        currentSlideIndex = index;
        // Log để kiểm tra
        // console.log('Current slide index:', index);
    }

    // Xử lý input số lượng
    const numOrder = document.getElementById('num-order');
    const plusBtn = document.getElementById('plus');
    const minusBtn = document.getElementById('minus');

    if (numOrder && plusBtn && minusBtn) {
        plusBtn.addEventListener('click', () => {
            let value = parseInt(numOrder.value) || 1;
            let newValue = value + 1;
            numOrder.value = newValue;
            if (typeof update_href === 'function') update_href(newValue);
        });

        minusBtn.addEventListener('click', () => {
            let value = parseInt(numOrder.value) || 1;
            if (value > 1) {
                let newValue = value - 1;
                numOrder.value = newValue;
                if (typeof update_href === 'function') update_href(newValue);
            }
        });
    }
});