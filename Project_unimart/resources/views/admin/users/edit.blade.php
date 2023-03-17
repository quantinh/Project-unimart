{{-- Kế thừa layouts --}}
@extends('layouts.admin')

{{-- Định nghĩa nội dung --}}
@section('content')
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
        <div class="card">
            <div class="card-header font-weight-bold">
                Cập nhập thông tin thành viên
            </div>
            <div class="card-body">
                <form action="{{ route('user.update', $users->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    {{-- Fullname --}}
                    <div class="form-group">
                        <label for="name">Họ và tên</label>
                        <input class="form-control" type="text" name="name" value="{{ $users->name }}"
                            id="name">
                        @error('name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input class="form-control" type="email" name="email" value="{{ $users->email }}"
                            id="email" disabled>
                        @error('email')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div class="form-group">
                        <label for="password">Đổi mật khẩu</label>
                        <input class="form-control" type="password" name="password" id="password" required minlength="8">
                        @error('password')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- Confirm-password --}}
                    <div class="form-group">
                        <label for="password-confirm">Xác nhận mật khẩu</label>
                        <input class="form-control" type="password" name="password_confirmation" id="password-confirm"
                            required minlength="8">
                        @error('password_comfirmation')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- form-roles --}}
                    <div class="form-group">
                        <label for="role_id">Nhóm quyền</label>
                        <select class="form-control" id="role_id" name="role_id">
                            <option value="0">Chọn quyền</option>
                            @foreach ($list_roles as $role)
                            {{-- Nếu trên bảng user có role_id khớp với id bảng role thì gán selected cho form  ngược lại thì để trống --}}
                                <option
                                    {{ $users->role_id == $role->id ? 'selected' : '' }} value="{{ $role->id }}">
                                    {{ $role->description }}
                                </option>
                            @endforeach
                        </select>
                        @error('role_id')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- Avatar --}}
                    <div class="form-group">
                        <label for="file">Ảnh đại diện</label><br>
                        <input type="file" name="file" id="file" value="{{ old('file') }}"><br>
                        <img src="{{ asset($users->avatar) }}" alt="Ảnh thành viên"
                            style="max-width: 150px; height: auto; margin-top: 10px;">
                        @error('file')
                            <small class='text-danger'>{{ $message }}</small>
                        @enderror
                    </div>
                    <br>

                    <button type="submit" name="btn-update" value="Cập nhập" class="btn btn-primary">Cập nhập</button>
                </form>
            </div>
        </div>
    </div>
@endsection
