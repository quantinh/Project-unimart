{{-- Danh sách phần banners  --}}
<div class="col l-3 mobile-on-hide">
    {{-- Đổ dữ liệu slider --}}
    <iframe width="100%" height="200" src="{{ $link_video->link}}" frameborder="0"
        allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen>
    </iframe>
    {{-- Đổ dữ liệu banner --}}
    <div class="sale">
        <a href="">
            <img class="sale-img" src="{{ asset($banners_position_head->image) }}" alt="sale">
        </a>
    </div>
</div>
