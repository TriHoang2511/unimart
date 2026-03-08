document.addEventListener('DOMContentLoaded', () => {
    const sectionDetail = document.querySelector('#post-product-wp .section-detail');
    const buttonShowMore = document.querySelector('#post-product-wp .button .show-more');
    const buttonShowLess = document.querySelector('#post-product-wp .button .show-less');
    const button = document.querySelector('#post-product-wp .button');
    const buttonContainer = document.querySelector('#post-product-wp .button-container');
    const arrowIcon = document.querySelector('#post-product-wp .button .fa-solid');

    const initial = {
        showAllContent: true,
        currentIcon: 'fa-chevron-down',
    };

    if (!button || !sectionDetail || !buttonShowMore || !buttonShowLess || !arrowIcon) {
        console.warn('One or more elements not found in #post-product-wp.');
        return;
    }

    // 🔥 ban đầu có blur
    buttonContainer.classList.add('button-blur');

    button.addEventListener('click', () => {
        if (initial.showAllContent) {
            // Show more → bỏ blur
            showButton(buttonShowLess, true);
            showButton(buttonShowMore, false);
            sectionDetail.classList.remove('maxHeight');
            buttonContainer.classList.remove('button-blur');
            changeIcon(arrowIcon, 'fa-chevron-down', 'fa-chevron-up');
            initial.currentIcon = 'fa-chevron-up';
        } else {
            // Show less → thêm blur lại
            showButton(buttonShowMore, true);
            showButton(buttonShowLess, false);
            sectionDetail.classList.add('maxHeight');
            buttonContainer.classList.add('button-blur');
            changeIcon(arrowIcon, 'fa-chevron-up', 'fa-chevron-down');
            initial.currentIcon = 'fa-chevron-down';
        }

        initial.showAllContent = !initial.showAllContent;
    });

    function changeIcon(element, currentIcon, newIcon) {
        if (element) {
            element.classList.remove(currentIcon);
            element.classList.add(newIcon);
        }
    }

    function showButton(button, visible = true) {
        if (button) {
            button.classList.toggle('d-none', !visible);
        }
    }
    // Khai báo các biến DOM
    const mainImageSliderWrapper = document.querySelector('.main-image-slider-wrapper');
    const mainImageItems = document.querySelectorAll('.main-image-item');

    const modal = document.getElementById('image-slider-modal');
    const closeModal = document.querySelector('.close-modal');
    const sliderItems = document.querySelectorAll('.image-slider .slider-item');
    const sliderNextBtn = document.querySelector('#image-slider .next-btn');
    const sliderPrevBtn = document.querySelector('#image-slider .prev-btn');

    const thumbItems = document.querySelectorAll('#list-thumb .thumb-item');
    const listPrevBtn = document.querySelector('#list-thumb .prev-btn');
    const listNextBtn = document.querySelector('#list-thumb .next-btn');
    const blurBackgroundPrevBtn = document.querySelector('#list-thumb .swiper-button-prev');
    const blurBackgroundNextBtn = document.querySelector('#list-thumb .swiper-button-next');
    const listThumbWrapper = document.querySelector('.list-thumb-wrapper');

    let currentMainImageIndex = 0;
    let currentThumbIndex = 0;
    let currentSlideIndex = 0;

    // Hàm cập nhật slider ảnh chính và thumbnail
    function showMainImage(index) {
        if (!mainImageItems[index]) return;
        currentMainImageIndex = index;
        mainImageSliderWrapper.style.transform = `translateX(${-currentMainImageIndex * 100}%)`;

        thumbItems.forEach(item => item.classList.remove('active'));
        thumbItems[currentMainImageIndex].classList.add('active');
        currentThumbIndex = currentMainImageIndex;
        updateButtonStates();
    }

    // Hàm cuộn slider thumbnail
    function scrollToThumb(index) {
        if (!thumbItems[index] || !listThumbWrapper) return;
        showMainImage(index);

        const containerWidth = listThumbWrapper.clientWidth;
        const thumb = thumbItems[index];
        const thumbCenter = thumb.offsetLeft + thumb.offsetWidth / 2;
        const scrollPosition = thumbCenter - containerWidth / 2;

        listThumbWrapper.scrollTo({
            left: scrollPosition,
            behavior: 'smooth'
        });
    }

    // Hàm cập nhật trạng thái nút next/prev và hiệu ứng mờ 2 bên mép của thumbnail
    function updateButtonStates() {
        if (!listThumbWrapper || !listPrevBtn || !listNextBtn) return;
        listPrevBtn.style.display = currentThumbIndex > 0 ? 'flex' : 'none';
        listNextBtn.style.display = currentThumbIndex < thumbItems.length - 1 ? 'flex' : 'none';
        // blurBackgroundPrevBtn.style.display = currentThumbIndex > 0 ? 'block' : 'none';
        // blurBackgroundNextBtn.style.display = currentThumbIndex < thumbItems.length - 1 ? 'block' : 'none';
        // --- LOGIC HIỆU ỨNG MỜ (Dựa trên Cuộn Thực tế) ---

        // 1. Mờ bên Trái (Prev)
        const isScrolledFromStart = listThumbWrapper.scrollLeft > 0;
        blurBackgroundPrevBtn.style.display = isScrolledFromStart ? 'block' : 'none';

        // 2. Mờ bên Phải (Next)
        const maxScrollLeft = listThumbWrapper.scrollWidth - listThumbWrapper.clientWidth;
        // Làm tròn để tránh lỗi dấu phẩy động
        const isScrolledToFinish = Math.round(listThumbWrapper.scrollLeft) >= Math.round(maxScrollLeft);

        // Nếu tổng chiều rộng nội dung > chiều rộng hiển thị VÀ chưa cuộn hết
        const isContentOverflowing = listThumbWrapper.scrollWidth > listThumbWrapper.clientWidth;

        if (isContentOverflowing && !isScrolledToFinish) {
            blurBackgroundNextBtn.style.display = 'block';
        } else {
            blurBackgroundNextBtn.style.display = 'none';
        }
    }

    // Hàm navigate tiện dụng
    function navigate(index, itemsLength) {
        if (itemsLength === 0) return 0;
        if (index < 0) return itemsLength - 1;
        if (index >= itemsLength) return 0;
        return index;
    }

    // Hàm hiển thị ảnh trong modal
    function showSlide(index) {
        if (!sliderItems[index]) return;
        sliderItems.forEach(item => item.classList.remove('active'));
        sliderItems[index].classList.add('active');
        currentSlideIndex = index;
    }

    // Xử lý click thumbnail
    thumbItems.forEach((item, index) => {
        item.querySelector('a').addEventListener('click', (e) => {
            e.preventDefault();
            showMainImage(index);
        });
    });

    // Xử lý next/prev cho thumbnail
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

    // Khởi tạo trạng thái ban đầu
    if (thumbItems.length > 0) {
        showMainImage(0);
    }

    // Mở modal khi click vào ảnh chính
    if (mainImageItems.length > 0) {
        mainImageItems.forEach((item, index) => {
            item.addEventListener('click', () => {
                showSlide(index);
                modal.style.display = 'block';
                document.body.style.overflow = 'hidden';
            });
        });
    }

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

    // Xử lý next/prev trong modal
    if (sliderNextBtn && sliderPrevBtn) {
        sliderNextBtn.addEventListener('click', () => {
            currentSlideIndex = navigate(currentSlideIndex + 1, sliderItems.length);
            showSlide(currentSlideIndex);
        });
        sliderPrevBtn.addEventListener('click', () => {
            currentSlideIndex = navigate(currentSlideIndex - 1, sliderItems.length);
            showSlide(currentSlideIndex);
        });
    }

    // Khởi tạo tab nội dung
    const tabButtons = document.querySelectorAll('.tab-btn');
    const tabContents = document.querySelectorAll('.tab-content');

    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            const targetTab = button.getAttribute('data-tab');
            // Cập nhật trạng thái active
            tabButtons.forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');
            // Hiển thị nội dung tương ứng
            tabContents.forEach(content => {
                if (content.id === targetTab) {
                    content.classList.add('active');
                } else {
                    content.classList.remove('active');
                }
            });
        });
    });


    const variants = window.productVariants || [];

    const attributeOptions = document.querySelectorAll(".attribute-option");

    const salePriceDisplay = document.querySelector(".sale-price");

    const originalPriceDisplay = document.querySelector(".base-price");

    const mainImage = document.querySelector("#main-product-image");

    const variantIdInput = document.querySelector('input[name="variant_id"]');

    let selectedAttributes = {};

    /*
    ============================
    INIT SELECTED ATTRIBUTE
    ============================
    */

    document.querySelectorAll(".attribute-option.active").forEach(el => {

        selectedAttributes[el.dataset.attributeId] = parseInt(el.dataset.valueId);

    });

    /*
    ============================
    CLICK ATTRIBUTE
    ============================
    */

    attributeOptions.forEach(option => {

        option.addEventListener("click", function (e) {

            e.preventDefault();

            const valueId = parseInt(this.dataset.valueId);

            const attributeId = this.dataset.attributeId;

            /*
            UPDATE STATE
            */

            selectedAttributes[attributeId] = valueId;

            /*
            UPDATE ACTIVE UI
            */

            const group = this.closest(".attribute-options");

            group.querySelectorAll(".attribute-option")
                .forEach(el => el.classList.remove("active"));

            this.classList.add("active");

            /*
            FIND MATCHING VARIANT
            */

            const selectedValues = Object.values(selectedAttributes);

            const matchedVariant = variants.find(variant => {

                return selectedValues.every(val =>
                    variant.attributes.includes(val)
                );

            });

            /*
            UPDATE UI
            */

            if (matchedVariant) {

                updateProductUI(matchedVariant);

            } else {

                console.warn("Không tìm thấy variant");

            }

        });

    });

    /*
    ============================
    UPDATE PRODUCT UI
    ============================
    */

    function updateProductUI(variant) {

        /* price */

        if (salePriceDisplay) {

            salePriceDisplay.innerText = variant.price;

        }

        if (originalPriceDisplay) {

            originalPriceDisplay.innerText = variant.compare_price;

        }

        /* image */

        if (mainImage && variant.image) {

            mainImage.src = variant.image;

        }

        /* variant id */

        if (variantIdInput) {

            variantIdInput.value = variant.id;

        }

        /* update URL */

        const newPath = `/san-pham/${variant.slug}.html`;

        if (window.location.pathname !== newPath) {

            const newUrl = `${window.location.origin}${newPath}`;

            window.history.pushState({ path: newUrl }, "", newUrl);

        }

    }
});