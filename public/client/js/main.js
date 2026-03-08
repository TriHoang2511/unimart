$(document).ready(function () {
    // SLIDER
    var slider = $('#slider-wp .section-detail');
    slider.owlCarousel({
        autoPlay: 4500,
        navigation: false,
        navigationText: false,
        paginationNumbers: false,
        pagination: true,
        items: 1,
        itemsDesktop: [1000, 1],
        itemsDesktopSmall: [900, 1],
        itemsTablet: [600, 1],
        itemsMobile: true
    });

    // FEATURE PRODUCT
    var feature_product = $('#feature-product-wp .list-item');
    feature_product.owlCarousel({
        autoPlay: true,
        navigation: true,
        navigationText: false,
        paginationNumbers: false,
        pagination: false,
        stopOnHover: true,
        items: 4,
        itemsDesktop: [1000, 4],
        itemsDesktopSmall: [800, 3],
        itemsTablet: [600, 2],
        itemsMobile: [375, 1]
    });

    // SAME CATEGORY
    var same_category = $('#same-category-wp .list-item');
    same_category.owlCarousel({
        autoPlay: true,
        navigation: true,
        navigationText: false,
        paginationNumbers: false,
        pagination: false,
        stopOnHover: true,
        items: 4,
        itemsDesktop: [1000, 4],
        itemsDesktopSmall: [800, 3],
        itemsTablet: [600, 2],
        itemsMobile: [375, 1]
    });

    // SCROLL TOP
    $(window).scroll(function () {
        if ($(this).scrollTop() != 0) {
            $('#btn-top').stop().fadeIn(150);
        } else {
            $('#btn-top').stop().fadeOut(150);
        }
    });
    $('#btn-top').click(function () {
        $('body,html').stop().animate({scrollTop: 0}, 800);
    });

    // CHOOSE NUMBER ORDER`
    var value = parseInt($('.num-order').val());

    // Nút tăng
    $(document).on('click', '.plus', function() {
        let input = $(this).siblings('.num-order');
        let value = parseInt(input.val()) || 1;
        let max = parseInt(input.attr('max')) || 999;
        if (value < max) {
            input.val(value + 1).trigger('change');
        }
    });

    // Nút giảm
    $(document).on('click', '.minus', function() {
        let input = $(this).siblings('.num-order');
        let value = parseInt(input.val()) || 1;
        let min = parseInt(input.attr('min')) || 1;
        if (value > min) {
            input.val(value - 1).trigger('change');
        }
    });


    // MAIN MENU
    $('#category-product-wp .list-item > li').find('.sub-menu').after('<i class="fa fa-angle-right arrow" aria-hidden="true"></i>');

    // TAB
    tab();

    // EVEN MENU RESPON
    $('html').on('click', function (event) {
        var target = $(event.target);
        var site = $('#site');

        if (target.is('#btn-respon i')) {
            if (!site.hasClass('show-respon-menu')) {
                site.addClass('show-respon-menu');
            } else {
                site.removeClass('show-respon-menu');
            }
        } else {
            $('#container').click(function () {
                if (site.hasClass('show-respon-menu')) {
                    site.removeClass('show-respon-menu');
                    return false;
                }
            });
        }
    });

    // MENU RESPON
    $('#main-menu-respon li .sub-menu').after('<span class="fa fa-angle-right arrow"></span>');
    $('#main-menu-respon li .arrow').click(function () {
        if ($(this).parent('li').hasClass('open')) {
            $(this).parent('li').removeClass('open');
        } else {
            $(this).parent('li').addClass('open');
        }
    });

    $(document).on("click", ".add-cart", function (e) {
        e.preventDefault();

        const $btn = $(this);
        const baseUrl = $btn.data("url") || $btn.attr("href");

        // 1️⃣ Lấy số lượng
        // Tìm input số lượng gần nhất trong cùng sản phẩm (nếu có)
        let qtyInput = $btn.closest(".product-detail, .product-item, body").find(".num-order").first();
        let qty = parseInt(qtyInput.val()) || 1;

        // 2️⃣ Ghép URL gửi Ajax
        const finalUrl = baseUrl + (baseUrl.includes("?") ? "&" : "?") + "qty=" + qty;

        // 3️⃣ Gửi request bằng AJAX
        $.ajax({
            url: finalUrl,
            method: "GET",
            dataType: "json",
            success: function (response) {
            if (response.success) {
                $("#cart-wp #num").text(response.num);
                $("#cart-respon-wp #num").text(response.num);
                $("#dropdown").html(response.dropdown);
                showPopup("Đã thêm sản phẩm vào giỏ!");
            }
            },
            error: function () {
            alert("Có lỗi khi thêm vào giỏ hàng!");
            }
        });
    });


    // Hàm hiện popup với style hiện đại
    function showPopup(msg) {
        let popup = document.createElement("div");
        popup.innerHTML = `<i class="fa fa-check-circle"></i> ${msg}`;
        popup.style.position = "fixed";
        popup.style.top = "125px";
        popup.style.right = "20px";
        popup.style.background = "linear-gradient(135deg, #28a745, #218838)";
        popup.style.color = "#fff";
        popup.style.padding = "12px 20px";
        popup.style.fontSize = "15px";
        popup.style.fontWeight = "bold";
        popup.style.borderRadius = "8px";
        popup.style.boxShadow = "0 4px 12px rgba(0,0,0,0.2)";
        popup.style.zIndex = "9999";
        popup.style.opacity = "0";
        popup.style.transition = "opacity 0.3s ease";
        document.body.appendChild(popup);

        // Cho nó hiện từ từ
        setTimeout(() => {
            popup.style.opacity = "1";
        }, 50);

        // Sau 2s thì ẩn đi
        setTimeout(() => {
            popup.style.opacity = "0";
            setTimeout(() => popup.remove(), 300);
        }, 2000);
    }

    });


    // TAB
    function tab() {
        var tab_menu = $('#tab-menu li');
        tab_menu.stop().click(function () {
            $('#tab-menu li').removeClass('show');
            $(this).addClass('show');
            var id = $(this).find('a').attr('href');
            $('.tabItem').hide();
            $(id).show();
            return false;
        });
        $('#tab-menu li:first-child').addClass('show');
        $('.tabItem:first-child').show();
    }

    // Hàm update_href (giả định từ code cũ, cần định nghĩa nếu sử dụng)
    function update_href(value) {
        // Thêm logic cập nhật href nếu cần, ví dụ: document.querySelector('.add-cart').href = `?page=cart&quantity=${value}`;
    }

// Phần xử lý #list-thumb và #image-slider-modal bằng JavaScript thuần
// document.addEventListener('DOMContentLoaded', () => {
//     const zoomImage = document.getElementById('zoom-image');
//     const modal = document.getElementById('image-slider-modal');
//     const closeModal = document.querySelector('.close-modal');
//     const thumbItems = document.querySelectorAll('#list-thumb .thumb-item');
//     const listPrevBtn = document.querySelector('#list-thumb .prev-btn');
//     const listNextBtn = document.querySelector('#list-thumb .next-btn');
//     const sliderItems = document.querySelectorAll('#image-slider .slider-item');
//     const sliderPrevBtn = document.querySelector('#image-slider .prev-btn');
//     const sliderNextBtn = document.querySelector('#image-slider .next-btn');

//     let currentThumbIndex = 0;
//     let currentSlideIndex = 0;

//     // Hàm hiển thị ảnh thumbnail
//     function showThumbImage(index) {
//         if (!thumbItems[index]) return;
//         const pictureSrc = thumbItems[index].querySelector('a').dataset.image;
//         zoomImage.src = pictureSrc;
//         thumbItems.forEach(item => item.classList.remove('active'));
//         thumbItems[index].classList.add('active');
//         currentThumbIndex = index;
//     }

//     // Hàm hiển thị ảnh trong modal
//     function showSlide(index) {
//         if (!sliderItems[index]) return;
//         sliderItems.forEach(item => item.classList.remove('active'));
//         sliderItems[index].classList.add('active');
//         currentSlideIndex = index;
//     }

//     // Hàm next/prev tiện dụng
//     function navigate(index, itemsLength) {
//         if (itemsLength === 0) return 0;
//         if (index < 0) return itemsLength - 1;
//         if (index >= itemsLength) return 0;
//         return index;
//     }

//     // Xử lý click thumbnail
//     thumbItems.forEach((item, index) => {
//         item.querySelector('a').addEventListener('click', (e) => {
//             e.preventDefault();
//             showThumbImage(index);
//         });
//     });

//     // Xử lý next/prev cho thumbnail
//     if (listNextBtn && listPrevBtn) {
//         listNextBtn.addEventListener('click', () => {
//             currentThumbIndex = navigate(currentThumbIndex + 1, thumbItems.length);
//             showThumbImage(currentThumbIndex);
//         });
//         listPrevBtn.addEventListener('click', () => {
//             currentThumbIndex = navigate(currentThumbIndex - 1, thumbItems.length);
//             showThumbImage(currentThumbIndex);
//         });
//     }

//     // Khởi tạo thumbnail đầu tiên
//     if (thumbItems.length > 0) {
//         showThumbImage(0);
//     }

//     // Mở modal khi click vào ảnh chính
//     if (zoomImage) {
//         zoomImage.addEventListener('click', () => {
//             const currentSrc = zoomImage.getAttribute('src');
//             sliderItems.forEach((slide, index) => {
//                 const img = slide.querySelector('img');
//                 if (img && img.getAttribute('src') === currentSrc) {
//                     currentSlideIndex = index;
//                 }
//             });
//             showSlide(currentSlideIndex);
//             modal.style.display = 'block';
//             document.body.style.overflow = 'hidden'; // chặn scroll nền
//         });
//     }

//     // Đóng modal
//     if (closeModal) {
//         closeModal.addEventListener('click', () => {
//             modal.style.display = 'none';
//             document.body.style.overflow = '';
//         });
//     }
//     modal.addEventListener('click', (event) => {
//         if (event.target === modal) {
//             modal.style.display = 'none';
//             document.body.style.overflow = '';
//         }
//     });

//     // Xử lý next/prev trong modal
//     if (sliderNextBtn && sliderPrevBtn) {
//         sliderNextBtn.addEventListener('click', () => {
//             currentSlideIndex = navigate(currentSlideIndex + 1, sliderItems.length);
//             showSlide(currentSlideIndex);
//         });
//         sliderPrevBtn.addEventListener('click', () => {
//             currentSlideIndex = navigate(currentSlideIndex - 1, sliderItems.length);
//             showSlide(currentSlideIndex);
//         });
//     }

//     // Xử lý input số lượng (plus/minus)
//     const numOrder = document.getElementById('num-order');
//     const plusBtn = document.getElementById('plus');
//     const minusBtn = document.getElementById('minus');

//     if (numOrder && plusBtn && minusBtn) {
//         plusBtn.addEventListener('click', () => {
//             let value = parseInt(numOrder.value) || 1;
//             let newValue = value + 1;
//             numOrder.value = newValue;
//             if (typeof update_href === 'function') update_href(newValue);
//         });

//         minusBtn.addEventListener('click', () => {
//             let value = parseInt(numOrder.value) || 1;
//             if (value > 1) {
//                 let newValue = value - 1;
//                 numOrder.value = newValue;
//                 if (typeof update_href === 'function') update_href(newValue);
//             }
//         });
//     }
// });
