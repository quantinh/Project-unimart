<!-- Danh mục sản phẩm  -->
<div class="col l-3 mobile-on-hide" style="margin-right: -9px;">
    <div class="category">
        <h3 class="category-heading">Danh mục sản phẩm</h3>
        <ul class="category-list">
            {{-- Lấy danh mục sản phẩm cha --}}
            @foreach($categorys as $indexCategory => $category)
                <li class="category-item category-item--{{ $indexCategory == 0 ? 'active' : '' }}">
                    <a href="{{ route('product.category', $category->slug) }}" class="category-item-link">
                        <img class="category-img" src="{{ asset($category->icon_cat) }}" alt="">
                        {{ $category->cat_name }}
                    </a>
                    @if($category->categoryChildrent->count())
                        <i class="category-item-link-icon fa-solid fa-angle-right"></i>
                    @endif
                    <ul class="category-list-sub">
                        {{-- Lấy danh muc con --}}
                        @foreach($category->categoryChildrent as $indexcategoryChilrent => $categoryChilrent)
                            <li class="category-list-sub-item category-sub-item{{ $indexcategoryChilrent == 0 ? '--active' : '' }}">
                                <a href="{{ route('product.category', $categoryChilrent->slug) }}" class="category-list-sub-link" title="">
                                    {{ $categoryChilrent->cat_name }}
                                </a>
                                @if($categoryChilrent->categoryChildrent->count())
                                    <i class="category-item-sub-icon fa-solid fa-angle-right"></i>
                                @endif
                                <ul class="category-list-sub-child">
                                {{-- Lấy danh muc cháu --}}
                                    @foreach($categoryChilrent->categoryChildrent as $categoryGrandChilrent)
                                        <li class="category-list-sub-item">
                                            <a href="{{ route('product.category', $categoryGrandChilrent->slug) }}" class="category-list-sub-link" title="">
                                                {{ $categoryGrandChilrent->cat_name }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                        @endforeach
                    </ul>
                </li>
            @endforeach
        </ul>
    </div>
</div>
