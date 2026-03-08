@extends('client.layouts.app')

@section('content')
    <div id="main-content-wp" class="home-mod clearfix">
        <div class="wp-inner">
            <div class="main-content fl-right">

                {{-- Section: Sản phẩm nổi bật --}}
                <div class="section" id="feature-product-wp">
                    <div class="section-head">
                        <h2 class="section-title">Sản phẩm nổi bật</h2>
                    </div>
                    <div class="section-detail">
                        <ul class="list-item clearfix">
                            @foreach ($list_featured_products as $product)
                                @php
                                    // Ưu tiên defaultVariant, nếu null thì lấy đại cái đầu tiên trong list variants
                                    $display = $product->defaultVariant ?? $product->variants->first();
                                @endphp

                                @if ($display)
                                    <li class="img_resize">
                                        <a href="{{ url('san-pham/' . $product->slug . '.html?variant=' . $display->id) }}" class="thumb">
                                            <img src="{{ asset('storage/' . ($display->variant_image ?? $product->featured_image)) }}"
                                                alt="{{ $display->variant_full_name }}">
                                        </a>
                                        <a href="{{ url('san-pham/' . $product->slug . '.html?variant=' . $display->id) }}" class="product-name">
                                            {{ $display->variant_full_name }}
                                        </a>
                                        <div class="price">
                                            <span class="new">{{ number_format($display->price, 0, ',', '.') }}đ</span>
                                            @if ($display->compare_at_price > $display->price)
                                                <span
                                                    class="old">{{ number_format($display->compare_at_price, 0, ',', '.') }}đ</span>
                                            @endif
                                        </div>
                                        <button type="button" class="add-cart" data-id="{{ $display->id }}">Thêm giỏ
                                            hàng
                                        </button>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                </div>

                {{-- Section: Sản phẩm theo danh mục --}}
                @foreach ($products_by_category as $cat)
                    @if ($cat->products_for_home->count())
                        <div class="section" id="list-product-wp">
                            <div id="section-head-wp">
                                <div class="section-head">
                                    <h3 class="section-title">{{ $cat->name }}</h3>
                                </div>
                                <div class="see-more">
                                    <a href="san-pham/dien-thoai" title="">Xem tất cả
                                        <svg stroke="currentColor" fill="currentColor" stroke-width="0"
                                            viewBox="0 0 512 512" class="ml-1 inline size-4" height="1em" width="1em"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path fill="none" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="48" d="m184 112 144 144-144 144"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>

                            <div class="section-detail">
                                <ul class="list-item">
                                    @foreach ($cat->products_for_home as $product)
                                        @php
                                            $display = $product->defaultVariant ?? $product->variants->first();
                                        @endphp

                                        @if ($display)
                                            <li class="img_resize">
                                                <a href="{{ url('san-pham/' . $product->slug . '.html?variant=' . $display->id) }}" class="thumb">
                                                    <img src="{{ asset('storage/' . ($display->variant_image ?? $product->featured_image)) }}"
                                                        alt="{{ $display->variant_full_name }}">
                                                        {{-- {{ dd(get_class($display), $display->slug) }} --}}
                                                </a>
                                                <a href="{{ url('san-pham/' . $product->slug . '.html?variant=' . $display->idx) }}"
                                                    class="product-name">
                                                    {{ $display->variant_full_name }}
                                                </a>
                                                <div class="price">
                                                    <span
                                                        class="new">{{ number_format($display->price, 0, ',', '.') }}đ</span>
                                                    @if ($display->compare_at_price > $display->price)
                                                        <span
                                                            class="old">{{ number_format($display->compare_at_price, 0, ',', '.') }}đ</span>
                                                    @endif
                                                </div>
                                                <button type="button" class="add-cart" data-id="{{ $display->id }}">Thêm
                                                    giỏ hàng</button>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>

            <div class="sidebar fl-left">
                <div class="section" id="category-product-wp">
                    <div class="section-head">
                        <h3 class="section-title">Danh mục sản phẩm</h3>
                    </div>
                    <div class="section-detail">
                        <ul class="list-item">
                            @foreach ($categories as $category)
                                @include('client.partials.menu', ['category' => $category])
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
