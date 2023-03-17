<!-- Slider  -->
<div class="col l-6">
    <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner">
            {{-- Đổ dữ liệu slider --}}
            @foreach($list_sliders as $key => $slider)
                {{-- Nếu key = 0 tức là phần tử đầu thì thêm class active và ngược lại thì để trống --}}
                <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                    <img class="d-block w-100 carousel-img" src="{{ asset($slider->image) }}" alt="PC workstation">
                </div>
            @endforeach
        </div>
        {{-- Nút next  --}}
        <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        {{-- Nút prev  --}}
        <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>
</div>
