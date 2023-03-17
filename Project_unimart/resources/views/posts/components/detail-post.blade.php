<!-- Detail-post  -->
<div class="col l-9 me-12">
    <div class="main-content">
        <div class="section-box" id="detail-blog-wp">
            @if ($post->count() > 0)
                <div class="section-head-about">
                    <h3 class="section-title-about">{{ $post->title }}</h3>
                </div>
                <div class="section-detail-about">
                    <span class="create-date-about">{{ $post->created_at }}</span>
                    <div class="detail">
                        <p>{!! $post->content !!}</p>
                        <p style="text-align: center;">
                            <img style="width: 95%;" src="{{ asset($post->thumbnail) }}" alt="">
                        </p>
                    </div>
                </div>
            @else
                <p class="text-danger">Không tìm thấy bài viết chi tiết nào !</p>
            @endif
        </div>
        <div class="section" id="social-wp">
            <div class="section-detail-about">
                <div class="fb-like" data-href="" data-layout="button_count" data-action="like"
                    data-size="small" data-show-faces="true" data-share="true"></div>
                <div class="g-plusone-wp">
                    <div class="g-plusone" data-size="medium"></div>
                </div>
                <div class="fb-comments" id="fb-comment" data-href="" data-numposts="5"></div>
            </div>
        </div>
    </div>
</div>
