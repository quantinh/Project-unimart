<!-- List-post  -->
<div class="col l-9 c-12">
    <div class="section-blog" id="list-blog-wp">
        <div class="section-blog-head">
            <h3 class="section-blog-title">Bài viết công nghệ</h3>
        </div>
        <div class="section-blog-detail">
            @if ($list_posts->count() > 0)
                <ul class="list-item">
                    @foreach ($list_posts as $post)
                        <li class="item-post">
                            <a href="{{ route('blog.detail', $post->slug) }}" title="" class="item-post-link">
                                <img class="item-post-img" src="{{ asset($post->thumbnail) }}" alt="">
                            </a>
                            <div class="info-blog">
                                <a href="{{ route('blog.detail', $post->slug) }}" title="" class="title-link">{{ $post->title }}</a>
                                <span class="create-date">{{ $post->created_at }}</span>
                                <span class="desc-blog">{!! Str::of($post->content)->limit(200) !!}</span>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-danger">Không tìm thấy bài viết nào !</p>
            @endif
        </div>
    </div>
    <!-- Phân trang các theo danh mục sản phẩm -->
    {{ $list_posts->links('components.paginate') }}
</div>
