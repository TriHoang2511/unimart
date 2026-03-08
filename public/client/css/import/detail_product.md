/* DETAIL PRODUCT */
#detail-product-wp .thumb-wp,
#detail-product-wp .thumb-respon-wp {
    width: 42%;
}

#detail-product-wp .thumb-respon-wp {
    border: 1px solid #c7c7c7;
}

#detail-product-wp .info {
    width: 58%;
    padding-left: 25px;
}

#post-product-wp {
    position: relative;
}

#detail-product-wp .section-detail,
#post-product-wp .section-detail {
    background: #fff;
    padding: 17px;
    font-size: inherit;
}

#post-product-wp .section-detail.gradient {
    background: linear-gradient(to top, rgba(255, 255, 255, 0) 0%, rgba(255, 255, 255, 1) 100%);
}

#main-image-slider {
    position: relative;
    overflow: hidden;
    height: 400px; /* Điều chỉnh theo kích thước ảnh thực tế */
    display: flex;
    align-items: center;
    justify-content: center;
}

.main-image-wrapper {
    display: flex;
    transition: transform 0.3s ease-in-out; /* Hiệu ứng slide mượt mà */
    width: calc(100% * var(--num-slides, 1)); /* JS sẽ set --num-slides */
    height: 100%;
    will-change: transform; /* Tối ưu hiệu suất GPU */
}

.main-slide-item {
    width: 100%;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100%;
}

.main-slide-item .main-image {
    max-width: 100%;
    max-height: 100%;
    cursor: pointer;
    border: 1px solid #d9d9d9;
}

#image-slider-modal>.modal-content>.close-modal {
    cursor: pointer;
    position: absolute;
    font-size: 46px;
    top: 15px;
    right: 15px;
    z-index: 1000;
}

#list-thumb {
    margin-top: 25px;
    position: relative;
    display: block;
    overflow: visible;
}

.list-thumb-container {
    position: relative;
    width: 100%;
    overflow: hidden; /* Ẩn thumbnail ngoài khung */
    display: block;
}

.list-thumb-wrapper {
    display: flex;
    align-items: center;
    width: 100%;
    transition: transform 0.3s ease-in-out; /* Hiệu ứng slide mượt */
    will-change: transform; /* Tối ưu hiệu suất GPU */
}

.list-thumb-wrapper::-webkit-scrollbar {
    display: none;
}

.thumb-item {
    margin: 0 5px;
    cursor: pointer;
    flex-shrink: 0;
}

.thumb-item img {
    width: 70px;
    height: 70px;
    object-fit: contain;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    transition: border-color 0.3s ease, box-shadow 0.3s ease;
}

.thumb-item.active img {
    border-color: #d70018;
}

.slider-nav {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 100%;
    pointer-events: none;
}

.image-slider .slider-nav {
    z-index: 1;
}

.prev-btn,
.next-btn {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background: #acadaf8c;
    color: white;
    padding: 8px;
    cursor: pointer;
    border-radius: 50%;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.3s ease;
    pointer-events: auto;
}

.prev-btn:hover,
.next-btn:hover {
    background: rgba(0, 0, 0, 0.9);
}

.prev-btn {
    left: -15px;
}

.next-btn {
    right: -15px;
}

.prev-btn i,
.next-btn i {
    font-size: 16px;
}

#detail-product-wp .info .product-name {
    font-size: 24px;
    padding-bottom: 15px;
    margin-bottom: 20px;
    border-bottom: 1px solid #d0d0d0;
}

#detail-product-wp .info .desc p {
    color: #666;
    padding-bottom: 5px;
}

#detail-product-wp .info .desc p:last-child {
    padding-bottom: 0;
}

#detail-product-wp .info .desc {
    margin-bottom: 15px;
}

#detail-product-wp .info .num-product {
    margin-bottom: 25px;
}

#detail-product-wp .info .num-product .title {
    color: #666;
}

#detail-product-wp .info .num-product .status {
    display: inline-block;
    background: #ddd;
    padding: 0px 10px;
}

#detail-product-wp .info .price {
    font-size: 28px;
    font-family: 'Roboto Medium';
    color: #f12a43;
    margin-bottom: 15px;
}

#num-order-wp {
    margin-bottom: 25px;
}

#num-order {
    width: 30px;
    height: 30px;
    line-height: 30px;
    text-align: center;
    border: 1px solid #b1b1b1;
    font-family: "Roboto Regular";
    color: #545454;
}

#minus,
#plus {
    display: inline-block;
    width: 30px;
    height: 30px;
    line-height: 30px;
    text-align: center;
    border: 1px solid #a0a0a0;
    font-family: "Roboto Regular";
    color: #515151;
    font-size: 10px;
    cursor: pointer;
}

#minus:hover {
    color: #3a9800;
    border: 1px solid #3a9800;
}

#plus:hover {
    color: #F00;
    border: 1px solid #F00;
}

#detail-product-wp .add-cart {
    display: inline-block;
    padding: 10px 30px;
    font-size: 16px;
    background: green;
    color: #fff;
    border-radius: 5px;
    text-transform: uppercase;
}

.status-launching_soon {
    color: #1b4c2f;
    font-size: 12px;
    font-weight: 500;
    border-radius: 5px;
    padding: 0px 8px;
    background-color: #dff9e8;
}

.num-product .status-out_of_stock {
    color: #cc0f35;
    font-size: 15px;
    font-weight: 700;
    border-radius: 5px;
    padding: 4px 8px;
    background-color: #ffe6e1;
}

.num-product .status-preorder {
    color: #fff;
    font-size: 15px;
    font-weight: 700;
    border-radius: 5px;
    padding: 4px 8px;
    background-color: #17a2b8;
}

.num-product .status-coming_soon {
    color: #cc0f35;
    font-size: 15px;
    font-weight: 700;
    border-radius: 5px;
    padding: 4px 8px;
    background-color: #ffe6e1;
}

#detail-product-wp .add-cart:hover {
    background: #006d00;
}

#post-product-wp .section-title,
#same-category-wp .section-title {
    font-size: 21px;
    margin: 35px 0px 20px 0px;
    text-transform: uppercase;
}

#post-product-wp .section-detail p {
    padding-bottom: 15px;
    color: #666;
    text-align: justify;
}

.owl-item .thumb-item img {
    border-radius: 0.5rem;
    width: 71px;
    height: 63px;
    object-fit: cover;
    display: block;
    margin: 0 auto;
}

#post-product-wp .section-head {
    position: relative;
}

#post-product-wp .section-detail.maxHeight {
    max-height: 200px;
}

#post-product-wp .section-detail {
    overflow: hidden;
}

.button-show-more {
    background: linear-gradient(180deg, hsla(0, 0%, 100%, 0), hsla(0, 0%, 100%, .91) 50%, #fff 55%);
    border-radius: 10px;
    bottom: 0;
    display: block;
    left: 0;
    margin-bottom: 0;
    padding-top: 50px;
    position: absolute;
    right: 0;
    text-align: center;
    width: 100%;
}

.b-gray {
    background: #dfdfdf;
    border-radius: 8px;
    margin-top: 8px;
    padding: 8px 16px;
}

.button-blur {
    background: linear-gradient(180deg, hsla(0, 0%, 100%, 0), hsla(0, 0%, 100%, .91) 49%, #fff 36%);
    border-radius: 10px;
    bottom: 0;
    display: block;
    left: 0;
    margin-bottom: 0;
    padding-top: 50px;
    position: absolute;
    right: 0;
    text-align: center;
    width: 100%;
}

.button {
    padding: 1rem 0;
    color: #3b82f6;
    display: flex;
    justify-content: center;
    align-items: center;
    cursor: pointer;
}

.button span {
    font-weight: 700;
    font-size: 14px;
}

.button i {
    margin-left: 8px;
    font-size: 14px;
    margin-top: 3px;
}

.d-none {
    display: none;
}

/* Modal Slider */
.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0%, 0%, 0.8);
    z-index: 1000;
}

.modal-content {
    position: relative;
    margin: 50px auto;
    width: 77%; /* Giữ nguyên từ file gốc */
    background: black;
    border-radius: 8px;
    padding: 20px;
    text-align: center;
}

.close-modal {
    position: absolute;
    top: 10px;
    right: 20px;
    font-size: 30px;
    cursor: pointer;
    color: #fff; /* Cải thiện màu cho rõ hơn */
    transition: all 0.3s ease-in-out;
}

.close-modal:hover {
    color: #ccc;
}

.image-slider {
    position: relative;
    overflow: hidden;
    width: 100%;
    height: 600px; /* Giữ nguyên từ file gốc */
    box-sizing: border-box; /* Đảm bảo padding không làm tăng kích thước */
}

.slider-item {
    display: none; /* Quay lại logic gốc */
    width: 100%;
    height: 100%;
}

.slider-item.active {
    display: block; /* Hiển thị slide hiện tại */
}

.slider-item img {
    transform: translateY(0.5rem); /* Giữ nguyên từ file gốc */
    min-width: 400px; /* Giữ nguyên từ file gốc */
    height: 580px; /* Giữ nguyên từ file gốc */
    margin: 0 auto; /* Căn giữa */
    object-fit: contain; /* Đảm bảo giữ tỷ lệ, không cắt */
    max-width: 100%; /* Giới hạn chiều rộng tối đa */
}

.image-slider .prev-btn {
    left: 10px;
}

.image-slider .next-btn {
    right: 10px;
}

/* Responsive */
@media (max-width: 768px) {
    .thumb-item img {
        width: 50px;
        height: 50px;
    }

    .prev-btn,
    .next-btn {
        width: 25px;
        height: 25px;
        padding: 6px;
    }

    .prev-btn i,
    .next-btn i {
        font-size: 14px;
    }

    .prev-btn {
        left: -10px;
    }

    .next-btn {
        right: -10px;
    }

    #main-image-slider {
        height: 300px; /* Giảm chiều cao trên mobile */
    }

    .image-slider {
        height: 400px; /* Giảm chiều cao modal trên mobile */
    }

    .slider-item img {
        min-width: 300px; /* Giảm min-width trên mobile */
        height: 380px; /* Giảm chiều cao trên mobile */
        margin: 0 auto;
        object-fit: contain;
        max-width: 100%;
    }
}