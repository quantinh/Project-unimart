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
            // path_absolute: "https://quantinh.unitopcv.com/Project_unimart/",
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
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        <div class="card">
            <div class="card-header font-weight-bold">
                Thêm sản phẩm mới
            </div>
            <div class="card-body">
                <form action="{{ url('admin/products/store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="name_product">Tên sản phẩm</label>
                                <input class="form-control" type="text" name="name_product" id="name_product"
                                    value="{{ old('name_product') }}">
                                @error('name_product')
                                    <small class='text-danger'>{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="row">
                                {{-- Price --}}
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="price">Giá sản phẩm</label>
                                        <input class="form-control" type="number" name="price" id="price"
                                            value="{{ old('price') }}">
                                        @error('price')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                {{-- Sale-off --}}
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="price_old">Giá cũ <i>(Không bắt buộc)</i></label>
                                        <input class="form-control" type="number" name="price_old" id="price_old"
                                            value="{{ old('price_old') }}">
                                        @error('price_old')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        {{-- Product-quantily --}}
                                        <label for="quantily">Số lượng</label>
                                        <input class="form-control" type="number" name="quantily" id="quantily"
                                            value="{{ old('quantily') }}">
                                        @error('quantily')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-6">
                                    {{-- Cat_id --}}
                                    <div class="form-group">
                                        <label for="cat_id">Danh mục sản phẩm</label>
                                        <select name="cat_id" class="form-control" id="cat"
                                            value={{ old('cat_id') }}>
                                            <option value="0">Chọn danh mục</option>
                                            @foreach ($list_cats as $item)
                                                <option value={{ $item->id }}
                                                    {{ old('cat_id') == $item->id ? 'selected=selected' : '' }}>
                                                    {{ $item->cat_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('cat_id')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="brand_id">Thương hiệu</label>
                                        <select name="brand_id" class="form-control" id="brand">
                                            <option value="0">Chọn hãng</option>
                                            @foreach ($list_brands as $item)
                                                <option value={{ $item->id }}
                                                    {{ old('brand_id') == $item->id ? 'selected=selected' : '' }}>
                                                    {{ $item->name_brand }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('brand_id')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="color_id">Màu sản phẩm</label>
                                        <select name="color_id" class="form-control" id="color">
                                            <option value="0">Chọn màu sắc</option>
                                            @foreach ($list_colors as $item)
                                                <option value={{ $item->id }}
                                                    {{ old('color_id') == $item->id ? 'selected=selected' : '' }}>
                                                    {{ $item->name_color }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('color_id')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="image">Ảnh <em id="is-checked" class="text-grey">(png, jpg, jepg)</em></label><br>
                                        <input type="file" name="image" id="image" class="form-control-file" required><br>
                                        @error('image')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="image_desc">Ảnh mô tả <em id="is-checked" class="text-grey">(png, jpg, jepg)</em></label><br>
                                        <input type="file" name="image_desc[]" id="image_desc" class="form-control-file" multiple="multiple" required><br>
                                        @error('image_desc.*')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <label for="product_featured" class="ml-3 mb-3">Danh mục hiển thị <em id="is-checked" class="text-grey">(Chọn ít nhất một danh mục)</em></label>
                                <div class="col-6">
                                    <div class="form-group">
                                        <input type="checkbox" id="product_featured" name="product_featured"
                                            value="Sản phẩm nối bật"
                                            {{ old('product_featured') == 'product_featured ' ? ' checked=checked' : '' }}>
                                        <label for="product_featured" class="mx-2">Sản phẩm nổi bật</label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <input type="checkbox" id="product_selling" name="product_selling"
                                            value="Sản phẩm bán chạy"
                                            {{ old('product_selling') == 'product_selling ' ? ' checked=checked' : '' }}>
                                        <label for="product_selling" class="mx-2">Sản phẩm bán chạy </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            {{-- Detail --}}
                            <div class="form-group">
                                <label for="detail">Chi tiết sản phẩm</label>
                                <textarea id="detail" class="form-control" cols="30" rows="20" name="detail" placeholder="">{{ old('detail') }}</textarea>
                                @error('detail')
                                    <small class='text-danger'>{{ $message }}</small>
                                @enderror
                            </div><br>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            {{-- Description --}}
                            <div class="form-group">
                                <label for="description">Mô tả sản phẩm</label>
                                <textarea id="description" class="form-control" cols="30" rows="20" name="description" placeholder="">{{ old('description') }}</textarea>
                                @error('description')
                                    <small class='text-danger'>{{ $message }}</small>
                                @enderror
                            </div>
                            {{-- Status --}}
                            <div class="form-group">
                                <label for="status">Trạng thái</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status" id="public"
                                        value="Còn hàng" checked>
                                    <label class="form-check-label" for="public">
                                        Còn hàng
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status" id="waiting"
                                        value="Hết hàng">
                                    <label class="form-check-label" for="waiting">
                                        Hết hàng
                                    </label>
                                </div>
                                @error('status')
                                    <small class='text-danger'>{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div><br>
                    <button type="submit" class="btn btn-primary">Thêm mới</button>
                </form>
            </div>
        </div>
    </div>
@endsection
