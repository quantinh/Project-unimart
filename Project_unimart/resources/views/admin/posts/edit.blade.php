{{-- Kế thừa layouts --}}
@extends('layouts.admin')

{{-- Định nghĩa nội dung --}}
@section('content')
    {{-- Thư viên biên tập tiny.cloud import vào nếu ko sẽ ko thể hiện file-manager --}}
    <script type="text/javascript"
        src='https://cdn.tiny.cloud/1/2atz1z10b5nr3uca5yjmuczzzor7gw44gcwkfxpdgzr89743/tinymce/5/tinymce.min.js'
        referrerpolicy="origin"></script>
    {{-- Cấu hình file-manager uploads --}}
    <script>
        var editor_config = {
            //Copy đường dẫn dự án để upload ảnh
            path_absolute: "http://localhost:8181/Unt/Framework/LaravelPro/Exercises/Project_unimart/",
            selector: 'textarea',
            relative_urls: false,
            plugins: [
                "advlist autolink lists link image charmap print preview hr anchor pagebreak",
                "searchreplace wordcount visualblocks visualchars code fullscreen",
                "insertdatetime media nonbreaking save table directionality",
                "emoticons template paste textpattern"
            ],
            toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media",
            file_picker_callback: function(callback, value, meta) {
                var x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName(
                    'body')[0].clientWidth;
                var y = window.innerHeight || document.documentElement.clientHeight || document
                    .getElementsByTagName('body')[0].clientHeight;
                var cmsURL = editor_config.path_absolute + 'laravel-filemanager?editor=' + meta.fieldname;
                if (meta.filetype == 'image') {
                    cmsURL = cmsURL + "&type=Images";
                } else {
                    cmsURL = cmsURL + "&type=Files";
                }
                tinyMCE.activeEditor.windowManager.openUrl({
                    url: cmsURL,
                    title: 'Filemanager',
                    width: x * 0.8,
                    height: y * 0.8,
                    resizable: "yes",
                    close_previous: "no",
                    onMessage: (api, message) => {
                        callback(message.content);
                    }
                });
            }
        };
        tinymce.init(editor_config);
    </script>
    <div id="content" class="container-fluid">
        @if (session('status'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <h4 class="alert-heading">Thông báo !</h4>
                {{ session('status') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                        aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        <div class="card">
            <div class="card-header font-weight-bold">
                Cập nhập bài viết
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <form action="{{ route('admin.post.update', $post->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf

                            {{-- Images-post --}}
                            <div class="form-group">
                                <label for="file">Ảnh bài viết</label>
                                <input type="file" class="form-control-file" name="file">
                                <img src="{{ asset($post->thumbnail) }}" alt="" style="max-width: 150px; height: auto; margin-top: 10px;">
                                @error('file')
                                    <small class='text-danger'>{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Form-title-cat  --}}
                            <div class="row">
                                {{-- Name --}}
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="title">Tiêu đề bài viết</label>
                                        <input class="form-control" type="text" name="title" id="title"
                                            value="{{ $post->title }}"
                                            placeholder="">
                                        @error('title')
                                            <small class='text-danger'>{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Post-Category --}}
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="cat">Chọn danh mục</label>
                                        <select id="cat" name="cat" class="form-control">
                                            <option value="0">Chọn danh mục bài viết</option>
                                            @foreach ($list_cats as $name)
                                                {{-- Duyệt xong trỏ tới id nếu có key id thì cho thuộc tính seleted còn không thì để trống --}}
                                                <option
                                                    {{ $post->cat_id == $name->id ? 'selected' : '' }} value="{{ $name->id }}">
                                                    {{ $name->cat_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('cat')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- Content --}}
                            <div class="form-group">
                                <label for="content">Nội dung bài viết</label>
                                <textarea id="content" class="form-control" cols="30" rows="15" name="content"
                                    placeholder="">{{ $post->content }}</textarea>
                                @error('content')
                                    <small class='text-danger'>{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Status  --}}
                            <div class="form-group">
                                <label for="" class="d-block">Trạng thái</label>
                                <div class="form-check-inline">
                                    {{-- Nếu trạng thái có giá trị công khai ở db thì checked vào nếu không thì để trống --}}
                                    <input class="form-check-input" type="radio" name="status" id="public" value="{{ $post->status }}"
                                    {{ $post->status == 'Công khai' ? ' checked' : '' }}>
                                    <label class="form-check-label" for="public">
                                        Công khai
                                    </label>
                                </div>
                                <div class="form-check-inline">
                                    <input class="form-check-input" type="radio" name="status" id="waiting-for-approval" value="{{ $post->status }}"
                                    {{ $post->status == 'Chờ duyệt' ? ' checked' : '' }}>
                                    <label class="form-check-label" for="waiting-for-approval">
                                        Chờ duyệt
                                    </label>
                                </div>
                            </div>
                            <br>
                            <button type="submit" class="btn btn-primary">Cập nhập</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
