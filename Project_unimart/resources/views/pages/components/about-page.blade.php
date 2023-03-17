<!-- List-product  -->
<div class="col l-9">
    <div class="main-content">
        <div class="section-box" id="detail-blog-wp">
            <div class="section-head-about">
                <h3 class="section-title-about">{{ $page->title }}</h3>
            </div>
            <div class="section-detail-about">
                <span class="create-date-about">{{ $page->created_at }}</span>
                <div class="detail">
                    <p>
                        <strong>
                            {{-- blade !! dùng để chuyển html thành chuối kí tự thường  --}}
                            {!! $page->content !!}
                        </strong>
                    </p>
                    <p style="text-align: center;">
                        <img style="width: 95%;" src="{{ asset($page->thumbnail) }}" alt="Hình ảnh bài viết">
                    </p>
                    <p>
                        {{-- blade !! dùng để chuyển html thành chuối kí tự thường  --}}
                        {!! $page->description !!}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
