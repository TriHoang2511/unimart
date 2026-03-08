@extends('client.layouts.app')

<style>
    /* ===============================
ATTRIBUTE VARIATIONS
=============================== */

    .product-attributes {
        margin-top: 20px;
    }

    /* group */

    .attribute-group {
        margin-bottom: 18px;
    }

    /* attribute name */

    .attribute-title {
        font-weight: 600;
        margin-bottom: 8px;
        font-size: 15px;
        color: #333;
    }

    /* container options */

    .attribute-options {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    /* option */

    .attribute-option {
        border: 1px solid #ddd;
        padding: 8px 14px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 14px;
        background: #fff;
        transition: all 0.2s ease;
    }

    /* hover */

    .attribute-option:hover {
        border-color: #d70018;
    }

    /* active */

    .attribute-option.active {
        border-color: #d70018;
        color: #d70018;
        font-weight: 600;
    }

    /* ===============================
COLOR OPTIONS
=============================== */

    .color-option {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    /* color dot */

    .color-dot {
        width: 14px;
        height: 14px;
        border-radius: 50%;
        border: 1px solid #ccc;
    }

    /* color demo */

    .color-dot.black {
        background: #000;
    }

    .color-dot.white {
        background: #fff;
    }

    .color-dot.blue {
        background: #007bff;
    }
</style>
@section('content')
    <div id="main-content-wp" class="clearfix detail-product-page">
        <div class="wp-inner">
            {{-- Breadcrumb --}}
            <div class="section" id="breadcrumb-wp">
                <div class="section-detail">
                    @include('client.partials.breadcrumb', [
                        'items' => isset($category) ? [$category] : [],
                        'current_title' => $selectedVariant->variant_full_name,
                    ])
                </div>
            </div>

            <div class="product-content">
                {{-- Form Mua hàng --}}
                <form action="{{ route('cart.buyNow') }}" method="POST">
                    @csrf
                    {{-- Gửi variant_id thay vì product_id để chính xác biến thể --}}
                    <input type="hidden" name="variant_id" value="{{ $selectedVariant->id ?? '' }}">

                    <div class="section" id="detail-product-wp">
                        <div class="section-detail clearfix">

                            {{-- Gallery Ảnh --}}
                            <div class="thumb-wp fl-left">
                                <h3 class="product-name">
                                    {{ $selectedVariant->variant_full_name ?? '' }}
                                </h3>
                                {{-- PHẦN 1: ẢNH CHÍNH - Phải có đủ các ảnh để Slider chạy được --}}
                                <div id="imageZoom" class="image-zoom-container">
                                    <div class="main-image-slider-wrapper"
                                        style="display: flex; transition: transform 0.5s ease;">
                                        {{-- Ảnh đại diện --}}
                                        <img id="main-product-image" class="main-image-item"
                                            src="{{ asset('storage/' . ($selectedVariant->variant_image ?? $product->featured_image)) }}"
                                            alt="{{ $product->name }}" style="width:100%;flex-shrink:0;">

                                        {{-- Các ảnh chi tiết --}}
                                        @foreach ($product->images as $img)
                                            <img class="main-image-item" src="{{ asset('storage/' . $img->image_path) }}"
                                                alt="Detail" style="width: 100%; flex-shrink: 0;">
                                        @endforeach
                                    </div>
                                </div>

                                {{-- PHẦN 2: LIST THUMBNAILS --}}
                                <div id="list-thumb" class="list-thumb">
                                    <div id="list-thumb-container" class="list-thumb-container">
                                        <div class="list-thumb-wrapper" style="overflow-x: auto; display: flex;">
                                            {{-- Thumb cho ảnh đại diện --}}
                                            <div class="thumb-item active">
                                                <a href="#"
                                                    data-image="{{ asset('storage/' . ($selectedVariant->variant_image ?? $product->featured_image)) }}">
                                                    <img
                                                        src="{{ asset('storage/' . ($selectedVariant->variant_image ?? $product->featured_image)) }}">
                                                </a>
                                            </div>
                                            {{-- Thumb cho các ảnh chi tiết --}}
                                            @foreach ($product->images as $img)
                                                <div class="thumb-item">
                                                    <a href="#"
                                                        data-image="{{ asset('storage/' . $img->image_path) }}">
                                                        <img src="{{ asset('storage/' . $img->image_path) }}"
                                                            alt="Thumb">
                                                    </a>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    {{-- Nút điều hướng - Cần đúng class để JS tìm thấy --}}
                                    <div class="slider-nav">
                                        <div class="swiper-button-prev" style="display: none;"> {{-- JS sẽ điều khiển display --}}
                                            <div class="prev-btn"><i class="fa-solid fa-chevron-left"></i></div>
                                        </div>
                                        <div class="swiper-button-next">
                                            <div class="next-btn"><i class="fa-solid fa-chevron-right"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Đừng quên Modal cũng phải đủ ảnh như trên --}}
                            <div id="image-slider-modal" class="modal" style="display: none;">
                                <div class="modal-content">
                                    <span class="close-modal">&times;</span>
                                    <div id="image-slider" class="image-slider">
                                        <div class="slider-item active">
                                            <img
                                                src="{{ asset('storage/' . ($selectedVariant->variant_image ?? $product->featured_image)) }}">
                                        </div>
                                        @foreach ($product->images as $img)
                                            <div class="slider-item">
                                                <img src="{{ asset('storage/' . $img->image_path) }}">
                                            </div>
                                        @endforeach
                                        <div class="next-btn"><i class="fa-solid fa-chevron-right"></i></div>
                                        <div class="prev-btn"><i class="fa-solid fa-chevron-left"></i></div>
                                    </div>
                                </div>
                            </div>

                            {{-- Thông tin sản phẩm --}}
                            <div class="info fl-right">

                                {{-- <h3 class="product-name">
                                    {{ $product->name }}
                                    {{ $selectedVariant->variant_full_name ?? '' }}
                                </h3> --}}

                                {{-- <div class="desc">
                                    {!! $selectedVariant->display_specs !!}
                                </div> --}}

                                {{-- <div class="num-product">
                                    <span class="title">Trạng thái: </span>
                                    <span class="status-{{ $product->status }}">
                                        {{ $product->status === 'published' ? 'Còn hàng' : 'Hết hàng' }}
                                    </span>
                                </div> --}}

                                {{-- ======= GIÁ ======= --}}
                                <div class="product-price">
                                    @php
                                        $variant = $selectedVariant ?? $product->defaultVariant;
                                    @endphp
                                    <div class="price-label">
                                        <p>Giá sản phẩm</p>
                                    </div>
                                    @if ($variant)
                                        <div class="is-flex">
                                            <div id="display-product-price" class="sale-price">
                                                {{ number_format($selectedVariant->price, 0, ',', '.') }}đ
                                            </div>

                                            @if ($variant->compare_at_price > $variant->price)
                                                <div class="base-price">
                                                    {{ number_format($variant->compare_at_price, 0, ',', '.') }}đ
                                                </div>
                                            @endif
                                        </div>
                                    @else
                                        <div>Giá liên hệ</div>
                                    @endif
                                </div>

                                {{-- ======= ATTRIBUTE VARIATIONS (DEMO UI) ======= --}}
                                @php

                                    $variants = $product->variants;

                                    $attributes = [];

                                    foreach ($variants as $variant) {
                                        foreach ($variant->attributeValues as $value) {
                                            $attributeId = $value->attribute->id;

                                            $attributes[$attributeId]['name'] = $value->attribute->name;

                                            $attributes[$attributeId]['values'][$value->id] = $value;
                                        }
                                    }

                                @endphp
                                <div class="product-attributes">

                                    @foreach ($attributes as $attributeId => $attr)
                                        <div class="attribute-group">

                                            <div class="attribute-title">
                                                {{ $attr['name'] }}
                                            </div>

                                            <div class="attribute-options">

                                                @foreach ($attr['values'] as $value)
                                                    @php
                                                        $isActive = $selectedVariant->attributeValues
                                                            ->pluck('id')
                                                            ->contains($value->id);
                                                    @endphp

                                                    <a href="#"
                                                        class="attribute-option {{ $isActive ? 'active' : '' }}"
                                                        data-value-id="{{ $value->id }}"
                                                        data-attribute-id="{{ $attributeId }}">

                                                        {{ $value->value }}

                                                    </a>
                                                @endforeach

                                            </div>

                                        </div>
                                    @endforeach

                                </div>

                                {{-- ======= MUA HÀNG ======= --}}
                                <div id="add-to-cart-container">
                                    <div id="num-order-wp">
                                        <button type="button" class="minus">-</button>
                                        <input type="number" name="qty" value="1" min="1"
                                            class="num-order">
                                        <button type="button" class="plus">+</button>
                                    </div>

                                    <button type="submit" class="checkout">
                                        Thanh toán
                                    </button>

                                    <button type="button" class="add-cart add-to-cart"
                                        data-url="{{ route('cart.add', $selectedVariant->id) }}">
                                        Thêm giỏ hàng
                                    </button>
                                </div>

                            </div>
                        </div>
                    </div>
                </form>

                {{-- Sản phẩm liên quan --}}
                <div class="section" id="same-category-wp">
                    <div class="section-head">
                        <h3 class="section-title">Có thể bạn cũng thích</h3>
                    </div>
                    <div class="section-detail">
                        <ul class="list-item">
                            @foreach ($related_products as $item)
                                @php
                                    $variant = $item->defaultVariant;
                                @endphp
                                <li>

                                    <a href="{{ route('product.detail', $variant->slug) }}" class="thumb">
                                        <img
                                            src="{{ asset('storage/' . ($variant->variant_image ?? $item->featured_image)) }}">
                                    </a>

                                    <a href="{{ route('product.detail', $variant->slug) }}" class="product-name">
                                        {{ $item->name }}
                                    </a>

                                    <div class="price">
                                        <span class="new">
                                            {{ number_format($variant->price ?? 0, 0, ',', '.') }}đ
                                        </span>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                {{-- Tab mô tả chi tiết --}}
                <div class="section" id="post-product-wp">
                    <div class="section-head tab-header">
                        <div class="tab-btn active" data-tab="tab1">Mô tả sản phẩm</div>
                        <div class="tab-btn" data-tab="tab2">Thông số kỹ thuật</div>
                    </div>
                    <div class="b-gray">
                        <div class="tab-content active section-detail maxHeight" id="tab1"
                            style="user-select: text; -webkit-user-select: text; -moz-user-select: text; -ms-user-select: text;">
                            @if ($selectedVariant->description)
                                {!! $selectedVariant->description !!}
                            @else
                                {!! $product->description !!}
                            @endif
                        </div>
                        <div class="tab-content section-detail" id="tab2">
                            {!! $selectedVariant->technical_specifications ?? $product->technical_specifications !!}
                        </div>
                    </div>
                    <div class="button-container button-blur">
                        <div class="button">
                            <span class="show-more">Xem thêm</span>
                            <span class="show-less d-none">Ẩn bớt</span>
                            <i class="fa-solid fa-chevron-down"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        @php
            $variantData = $product->variants->map(function ($v) {
                return [
                    'id' => $v->id,

                    'slug' => $v->slug,

                    'price' => number_format($v->price, 0, ',', '.') . 'đ',

                    'compare_price' => $v->compare_at_price ? number_format($v->compare_at_price, 0, ',', '.') . 'đ' : null,

                    'image' => asset('storage/' . ($v->variant_image ?? $v->product->featured_image)),

                    'attributes' => $v->attributeValues->pluck('id')->toArray(),
                ];
            });
        @endphp

        window.productVariants = @json($variantData);
    </script>
    <script src="{{ asset('client/js/detail_product.js') }}" type="text/javascript"></script>
@endsection
