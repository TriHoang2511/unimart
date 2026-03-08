<li>
    <a href="{{ url('danh-muc/'.$category->slug) }}">{{ $category->name }}</a>
    @if($category->childrenForMenu->count() > 0)
        <ul class="sub-menu">
            @foreach($category->childrenForMenu as $child)
                {{-- Gọi lại chính nó để vẽ các cấp sâu hơn --}}
                @include('client.partials.menu', ['category' => $child])
            @endforeach
        </ul>
    @endif
</li>