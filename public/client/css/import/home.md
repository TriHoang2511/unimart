/*MAIN CONTENT*/
#main-content-wp {
    background: #f7f7f7;
    padding-top: 25px;
    padding-bottom: 75px;
    margin-top: 120px;
}

.main-content {
    width: 75%;
    margin-left: 2%;
}

.sidebar {
    width: 23%;
}

#slider-wp .item img {
    width: 100% !important
}

#category-product-wp {
    border: 1px solid #ececec;
}

#selling-wp,
#banner-wp,
#filter-product-wp {
    border: 1px solid #ececec;
    margin-top: 25px;
}

#category-product-wp .section-title,
#selling-wp .section-title,
#filter-product-wp .section-title {
    display: block;
    font-size: 20px;
    text-transform: uppercase;
    background: #969696;
    color: #fff;
    padding: 15px;
    border-bottom: 1px solid #dadada;
}

#category-product-wp .section-detail {
    background: #fff;
}

#category-product-wp .list-item {
    background: #fff;
}

#category-product-wp .list-item li a {
    display: block;
    color: #333;
    padding: 8px 20px;
    border-bottom: 1px solid #eee;
}

#category-product-wp .list-item li:last-child a {
    border-bottom: none;
}

#category-product-wp .list-item>li:hover>a,
#category-product-wp .list-item .sub-menu>li:hover>a {
    background: #eee;
}

#selling-wp .section-detail {
    background: #fff;
}

#selling-wp .section-detail .list-item {
    padding: 10px;
}

#selling-wp .section-detail .list-item li {
    margin-bottom: 10px;
    padding-bottom: 10px;
    border-bottom: 1px solid #ddd;
}

#selling-wp .section-detail .list-item li:last-child {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
}

#selling-wp .section-detail .list-item .thumb {
    display: block;
    width: 30%;
    border: 1px solid #ddd;
}

#selling-wp .section-detail .list-item .info {
    width: 70%;
    padding-left: 10px;
}

#selling-wp .section-detail .list-item .info .product-name {
    display: block;
    color: #333;
    line-height: 24px;
    font-family: 'Roboto Medium';
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -moz-line-clamp: 1;
    -o-line-clamp: 1;
    -webkit-box-orient: vertical;
    -moz-box-orient: vertical;
    -o-box-orient: vertical;
    max-height: 100%;
    overflow: hidden;
    margin-bottom: 2px;
}

#selling-wp .section-detail .list-item .info .price .new {
    display: inline-block;
    font-family: 'Roboto Medium';
    color: #f12a43;
    margin-right: 5px;
}

#selling-wp .section-detail .list-item .info .price .old {
    font-family: 'Roboto Light';
    color: #999;
    text-decoration: line-through;
    font-size: 13px;
}

#selling-wp .section-detail .list-item .info .buy-now {
    display: block;
    font-family: 'Roboto Medium';
    text-transform: uppercase;
    color: #333;
    font-size: 13px;
}

#support-wp {
    margin-top: 25px;
}

#support-wp .section-detail {
    background: #fff;
    padding: 15px 0px;
}

#support-wp .section-detail .list-item li {
    float: left;
    width: 20%;
    text-align: center;
    position: relative;
}

#support-wp .section-detail .list-item li:before {
    position: absolute;
    content: '';
    top: 50%;
    right: 0px;
    width: 1px;
    height: 75%;
    background: #ddd;
    transform: translateY(-50%);
}

#support-wp .section-detail .list-item li:last-child:before {
    display: none;
}

#support-wp .section-detail .list-item li .thumb {
    display: inline-block;
}

#support-wp .section-detail .list-item li .title {
    font-size: 16px;
    line-height: normal;
    margin-bottom: 5px;
}

#support-wp .section-detail .list-item li .desc {
    font-size: 13px;
    font-family: 'Roboto Light';
    color: #8e8e8e;
    line-height: normal;
}

#feature-product-wp .section-title,
#list-product-wp .section-title {
    font-size: 21px;
    text-transform: uppercase;
    line-height: normal;
    margin: 20px 0px 20px 0px;
}

#feature-product-wp .list-item li,
#same-category-wp .list-item li {
    border-radius: 10px;
    background: #fff;
    padding: 10px 10px 30px 10px;
    margin: 3% 4%;
    box-shadow: 0 4px 20px -8px rgba(0, 0, 0, .11), 0 0 10px 0 rgba(0, 0, 0, .059);
}

#feature-product-wp .list-item li .product-name,
#list-product-wp .list-item li .product-name,
#same-category-wp .list-item li .product-name {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    font-size: 14px;
    font-weight: 700;
    color: #222;
    font-family: 'Roboto Medium';
    min-height: 34px;
    margin: 15px 0px 5px 0px;
    line-height: normal;
}

#feature-product-wp .list-item li .price,
#list-product-wp .list-item li .price,
#same-category-wp .list-item li .price {
    margin-bottom: 15px;
}

#feature-product-wp .list-item li .price .new,
#list-product-wp .list-item li .price .new,
#same-category-wp .list-item li .price .new {
    display: inline-block;
    font-family: 'Roboto Medium';
    color: #d70018;
    font-size: 15px;
    font-weight: 700;
    margin-right: 5px;
}

#feature-product-wp .list-item li .price .old,
#list-product-wp .list-item li .price .old,
#same-category-wp .list-item li .price .old {
    display: inline-block;
    font-family: 'Roboto Light';
    color: #a1a1aa;
    font-size: 12px;
    text-decoration: line-through;
}

#feature-product-wp .list-item li .action a,
#list-product-wp .list-item li .action a,
#same-category-wp .list-item li .action a {
    display: block;
    padding: 2px 10px;
    font-size: 12px;
}

#feature-product-wp .list-item li .action .add-cart,
#list-product-wp .list-item li .action .add-cart,
#same-category-wp .list-item li .action .add-cart {
    border: 1px solid #333;
    color: #333;
}

#feature-product-wp .list-item li .action .buy-now,
#list-product-wp .list-item li .action .buy-now,
#same-category-wp .list-item li .action .buy-now {
    color: #da1818;
    border: 1px solid #da1818;
}

#feature-product-wp .list-item li .action .buy-now:hover,
#list-product-wp .list-item li .action .buy-now:hover,
#same-category-wp .list-item li .action .buy-now:hover {
    background: #da1818;
    color: #fff;
}

#feature-product-wp .list-item li .action .add-cart:hover,
#list-product-wp .list-item li .action .add-cart:hover,
#same-category-wp .list-item li .action .add-cart:hover {
    background: #333;
    color: #fff;
}

#list-product-wp .section-detail .list-item li {
    float: left;
    width: 24%; /* Tăng từ 23.125% để chứa badge */
    margin-left: 0.5%;
    margin-right: 0.5%;
    margin-bottom: 2%;
    background: #fff;
    box-shadow: 0 4px 20px -8px rgba(0, 0, 0, .11), 0 0 10px 0 rgba(0, 0, 0, .059);
    padding: 10px;
    border-radius: 10px;
    border: 1px solid transparent;
    min-height: 400px; /* Đảm bảo đủ không gian cho badge */
}

#feature-product-wp .section-detail .list-item li {
    border: 1px solid transparent;
}

#list-product-wp .section-detail .list-item li:hover,
#feature-product-wp .section-detail .list-item li:hover {
    /* border: 1px solid #ddd; */
}

#list-product-wp .section-detail .list-item li:nth-child(4n) {
    margin-right: 0 !important;
}

/*SIDEBAR MENU*/
#category-product-wp .list-item li {
    position: relative;
}

#category-product-wp .list-item li .sub-menu {
    display: none;
    position: absolute;
    left: 100%;
    top: 0;
    width: 265px;
    z-index: 1000;
    border: 1px solid #ececec;
    background: #fff;
}

#category-product-wp .list-item li:hover>.sub-menu {
    display: block;
}

#category-product-wp .list-item li .arrow {
    position: absolute;
    top: 50%;
    right: 5%;
    transform: translateY(-50%);
    color: #666;
}

.owl-item li a img {
    width: 160px;
    margin: 12px auto;
}

ul.list-item li.img_resize a img {
    margin: 12px auto;
    width: 160px;
    aspect-ratio: 1 / 1;
    transition: all 0.3s ease-in-out;
}

ul.list-item li.img_resize:hover a img {
    transform: scale(1.03);
}

.is-flex {
    display: flex !important;
}

.status-launching_soon {
    color: #1b4c2f;
    font-size: 12px;
    font-weight: 500;
    border-radius: 5px;
    padding: 0px 8px;
    background-color: #dff9e8;
}

.status-out_of_stock {
    color: #cc0f35;
    font-size: 12px;
    font-weight: 500;
    border-radius: 5px;
    padding: 0px 8px;
    background-color: #ffe6e1;
}

.status-preorder {
    color: #fff;
    font-size: 12px;
    font-weight: 500;
    border-radius: 5px;
    padding: 0px 8px;
    background-color: #17a2b8;
}

.status-coming_soon {
    color: #cc0f35;
    font-size: 12px;
    font-weight: 500;
    border-radius: 5px;
    padding: 1px 8px;
    background-color: #ffe6e1;
}

.product-status {
    display: inline-flex;
    line-height: 1.5;
}

.product-price {
    background: linear-gradient(to top right, #fcfeff, #eff5ff) padding-box, linear-gradient(to top right, #dbe8fe, #609afa) border-box;
    border: 1px solid transparent;
    border-radius: 16px;
    margin-bottom: 16px;
    padding: 14px 24px;
    width: -moz-fit-content;
    width: fit-content;
}

.price-label {
    color: #1d1d20;
    font-size: 14px;
    font-weight: 500;
    width: -moz-fit-content;
    width: fit-content;
}

.product-price .sale-price {
    color: #1d1d20;
    font-size: 24px;
    font-weight: 600;
}

.product-price .base-price {
    color: #a1a1aa;
    font-size: 16px;
    font-weight: 400;
    margin-left: 8px;
    text-decoration: line-through;
}

/* Product Detail Image Adjustments */
.image-zoom-container {
    width: 360px;
    height: 360px;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #fff;
    border: 1px solid #ececec;
    overflow: hidden;
    margin-bottom: 10px;
}

.main-image {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
    object-position: center;
}

/* Responsive adjustments for smaller screens */
@media (max-width: 768px) {
    .image-zoom-container {
        width: 100%;
        height: auto;
        aspect-ratio: 1 / 1;
    }
    
    .main-image {
        width: 100%;
        height: auto;
    }
}

/* Ensure thumbnails remain consistent */
#list-thumb .thumb-item img {
    width: 63px;
    height: 63px;
    object-fit: contain;
    background-color: #fff;
}

/* Đảm bảo .price là vị trí tương đối và có đủ không gian */
#feature-product-wp .price,
#list-product-wp .price {
    position: relative;
    display: inline-block;
    width: 100%;
    min-height: 40px;
}

/* Định dạng phần trăm giảm giá */
#feature-product-wp .price .product_price_percent,
#list-product-wp .price .product_price_percent {
    background: url('https://cdn2.cellphones.com.vn/x/media/wysiwyg/discount-badge-ui-2025.png') no-repeat center;
    background-size: contain;
    height: 22px;
    width: 78px;
    position: absolute;
    top: -10px;
    left: 5px;
    z-index: 20;
    background-color: #ff0000;
}

/* Định dạng chữ phần trăm */
#feature-product-wp .price .product_price_percent .product_price_percent_detail,
#list-product-wp .price .product_price_percent .product_price_percent_detail {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100%;
    width: 100%;
    color: #fff;
    font-size: 10px;
    font-weight: 500;
    text-align: center;
}

/* Ghi đè bộ chọn tổng quát */
.price .product_price_percent {
    background: url('https://cdn2.cellphones.com.vn/x/media/wysiwyg/discount-badge-ui-2025.png') no-repeat center !important;
    background-size: contain !important;
    height: 22px !important;
    width: 78px !important;
    position: absolute !important;
    top: -10px !important;
    left: 5px !important;
    z-index: 20 !important;
    background-color: #ff0000 !important;
}

.price .product_price_percent .product_price_percent_detail {
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    height: 100% !important;
    width: 100% !important;
    color: #fff !important;
    font-size: 10px !important;
    font-weight: 500 !important;
    text-align: center !important;
}

/* Responsive */
@media (max-width: 768px) {
    #feature-product-wp .price .product_price_percent,
    #list-product-wp .price .product_price_percent,
    .price .product_price_percent {
        height: 18px !important;
        width: 64px !important;
        top: -8px !important;
        left: 4px !important;
    }
    #feature-product-wp .price .product_price_percent .product_price_percent_detail,
    #list-product-wp .price .product_price_percent .product_price_percent_detail,
    .price .product_price_percent .product_price_percent_detail {
        font-size: 9px !important;
    }
    #list-product-wp .section-detail .list-item li {
        width: 48%;
        margin-left: 1%;
        margin-right: 1%;
        min-height: 350px;
    }
}