<!-- Phân trang các theo danh mục sản phẩm -->
@if ($paginator->hasPages())
    <div class="section" id="paging-wp">
        <div class="section-detail-paginate" id="pagging-filter">
            <ul class="list-item">
                {{-- Nút lùi trang --}}
                @if ($paginator->onFirstPage())
                    <li class="disabled" aria-disabled="true">
                        <a class="num-page" aria-hidden="true"><i class='fa fa-angle-left'></i></a>
                    </li>
                @else
                    <li>
                        <a class="num-page" href="{{ $paginator->previousPageUrl() }}" rel="prev"><i class='fa fa-angle-left'></i></a>
                    </li>
                @endif

                {{-- Duyệt lấy ra các thành phần của trang --}}
                @foreach ($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <li class="disabled" aria-disabled="true">
                            <a>{{ $element }}</a>
                        </li>
                    @endif

                    {{-- Kiểm tra các thành phần tồn tại thì hiển thị số trang nếu trang hiện tại thì thêm class= 'active' vào trang đó --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <li aria-current="page">
                                    <a class="num-page paginate-active">{{ $page }}</a>
                                </li>
                            @else
                                <li>
                                    <a class="num-page" href="{{ $url }}">{{ $page }}</a>
                                </li>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Nút tiến trang tiếp theo --}}
                @if ($paginator->hasMorePages())
                    <li>
                        <a class="num-page" href="{{ $paginator->nextPageUrl() }}" rel="next"><i class='fa fa-angle-right'></i></a>
                    </li>
                @else
                    <li class="disabled" aria-disabled="true">
                        <a class="num-page" aria-hidden="true"><i class='fa fa-angle-right'></i></a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
@endif
