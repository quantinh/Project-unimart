{{-- Kế thừa layouts --}}
@extends('layouts.admin')

{{-- Định nghĩa nội dung --}}
@section('content')
    {{-- Thư viên biên tập tiny.cloud import vào nếu ko sẽ ko thể hiện file-manager --}}
    <script type="text/javascript"
    src='https://cdn.tiny.cloud/1/2atz1z10b5nr3uca5yjmuczzzor7gw44gcwkfxpdgzr89743/tinymce/5/tinymce.min.js'
    referrerpolicy="origin"></script>
    {{-- Cấu hình file-manager --}}
    <script type="text/javascript">
        var editor_config = {
            //Copy đường dẫn dự án vào đây để upload ảnh
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
        <div class="card">
            <div class="card-header font-weight-bold">
                Chỉnh sửa trang
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        {{-- Đường dẫn đưa thông tin cập nhập lên db --}}
                        <form action="{{ route('admin.page.update', $page->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            {{-- Tiêu đề  --}}
                            <div class="form-group">
                                <label for="title">Tiêu đề bài viết của trang</label>
                                <input class="form-control @error('title') is-invalid @enderror" type="text" name="title" id="title"
                                    value="{{ $page->title }}">
                                @error('title')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Nội dung   --}}
                            <div class="form-group">
                                <label for="content">Nội dung bài viết của trang</label>
                                <textarea class="form-control @error('content') is-invalid @enderror" id="content" cols="30" rows="15" name="content">{{ $page->content }}</textarea>
                                @error('content')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Mô tả thêm --}}
                            <div class="form-group">
                                <label for="description">Mô tả bài viết của trang</label>
                                <textarea id="description" class="form-control @error('description') is-invalid @enderror" cols="30" rows="10" name="description"
                                    placeholder="">{{ $page->description }}</textarea>
                                @error('description')
                                    <small class='text-danger'>{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Images-page --}}
                            <div class="form-group">
                                <label for="file">Ảnh bài viết của trang</label>
                                <input type="file" class="form-control-file" name="file">
                                <img src="{{ asset($page->thumbnail) }}" alt="" style="max-width: 150px; height: auto; margin-top: 10px;">
                                @error('file')
                                    <small class='text-danger'>{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Trạng thái  --}}
                            <div class="form-group">
                                <label for="" class="d-block">Trạng thái</label>
                                <div class="form-check-inline">
                                    <input class="form-check-input" type="radio" name="status" id="public"
                                    value="{{ $page->status }}"
                                    {{ $page->status == 'Công khai' ? ' checked' : '' }}>
                                    <label class="form-check-label" for="public">
                                        Công khai
                                    </label>
                                </div>
                                <div class="form-check-inline">
                                    <input class="form-check-input" type="radio" name="status" id="waiting-for-approval"
                                    value="{{ $page->status }}"
                                    {{ $page->status == 'Chờ duyệt' ? ' checked' : '' }}>
                                    <label class="form-check-label" for="waiting-for-approval">
                                        Chờ duyệt
                                    </label>
                                </div>
                            </div>
                            <br>

                            <input type="submit" name="btn-submit" value="Cập nhập" class="btn btn-primary">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
