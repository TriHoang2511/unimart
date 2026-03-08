<ul class="list-item breadcrumb clearfix" itemscope itemtype="https://schema.org/BreadcrumbList">
    {{-- 1. Trang chủ --}}
    <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
        <a itemprop="item" href="{{ url('/') }}">
            <span itemprop="name">Trang chủ</span>
        </a>
        <meta itemprop="position" content="1" />
    </li>

    {{-- 2. Danh mục trung gian --}}
    @if(!empty($items))
        @foreach($items as $index => $cat)
            @php
                // Ép kiểu về object nếu nó đang là array để tránh lỗi
                $cat = (object) $cat;
                // Nếu bạn chưa có route category.detail, hãy dùng url() tạm thời như dưới đây
                $href = !empty($cat->slug) ? url('san-pham/' . trim($cat->slug, '/')) : '#';
            @endphp
            <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                <a itemprop="item" href="{{ $href }}">
                    <span itemprop="name">{{ $cat->name ?? $cat->category_name ?? '' }}</span>
                </a>
                <meta itemprop="position" content="{{ $index + 2 }}" />
            </li>
        @endforeach
    @endif

    {{-- 3. Trang hiện tại (Tên sản phẩm) --}}
    @if(!empty($current_title))
        @php $pos = (is_array($items) || is_object($items) ? count($items) : 0) + 2; @endphp
        <li class="current" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
            <p itemprop="name">{{ $current_title }}</p>
            <meta itemprop="position" content="{{ $pos }}" />
        </li>
    @endif
</ul>