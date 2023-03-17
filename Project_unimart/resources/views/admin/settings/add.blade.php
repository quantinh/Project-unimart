{{-- Kế thừa layouts --}}
@extends('layouts.admin')

{{-- Định nghĩa nội dung --}}
@section('content')
    {{-- Thư viên biên tập tiny.cloud import vào nếu ko sẽ ko thể hiện file-manager --}}
    {{-- <script type="text/javascript"
        src='https://cdn.tiny.cloud/1/4bxsgkp94m5dlcmtom28vs3rcv0r6cee5xd6d5vxuf7k2fwm/tinymce/5/tinymce.min.js'
        referrerpolicy="origin">
    </script> --}}
    {{-- Cấu hình file-manager uploads --}}
    {{-- <script>
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
    </script> --}}
    <div id="content" class="container-fluid">
        <div class="card">
            <div class="card-header font-weight-bold">
                Thêm thiết lập mới
            </div>
            <div class="card-body">
                <div class="form-action form-inline py-3">
                    <div class="dropdown">
                        <button class="btn btn-outline-primary dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">Kiểu giá trị</button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                            <a class="dropdown-item" href="{{ url('admin/settings/add').'?type=text' }}">Kiểu text</a>
                            <a class="dropdown-item" href="{{ url('admin/settings/add').'?type=textarea' }}">Kiểu textarea</a>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <form action="{{ route('admin.setting.store').'?type='.request()->type }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            {{-- Congfig_key --}}
                            <div class="form-group">
                                <label for="config_key">Config key</label>
                                <input class="form-control @error('config_key') is-invalid @enderror" type="text" name="config_key" id="config_key" placeholder="">
                                @error('config_key')
                                    <small class='text-danger'>{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- config_value --}}
                            @if (request()->type == 'text')
                                <div class="form-group">
                                    <label for="config_value">Config value</label>
                                    <input class="form-control @error('config_value') is-invalid @enderror" type="text" name="config_value" id="config_value" placeholder="">
                                    @error('config_value')
                                        <small class='text-danger'>{{ $message }}</small>
                                    @enderror
                                </div>
                            @elseif(request()->type == 'textarea')
                                <div class="form-group">
                                    <label for="config_value">Config value</label>
                                    <textarea class="form-control @error('config_value') is-invalid @enderror" type="textarea" name="config_value" placeholder="" rows="10"></textarea>
                                    @error('config_value')
                                        <small class='text-danger'>{{ $message }}</small>
                                    @enderror
                                </div>
                            @endif

                            {{-- Status --}}
                            <div class="form-group">
                                <label for="" class="d-block">Trạng thái</label>
                                <div class="form-check-inline">
                                    <input class="form-check-input" type="radio" name="status" id="public"
                                        value="Công khai" checked>
                                    <label class="form-check-label" for="public">
                                        Công khai
                                    </label>
                                </div>
                                <div class="form-check-inline">
                                    <input class="form-check-input" type="radio" name="status" id="waiting-for-approval"
                                        value="Chờ duyệt">
                                    <label class="form-check-label" for="waiting-for-approval">
                                        Chờ duyệt
                                    </label>
                                </div>
                            </div>
                            <br>

                            <input type="submit" name="btn-submit" value="Thêm mới" class="btn btn-primary">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
