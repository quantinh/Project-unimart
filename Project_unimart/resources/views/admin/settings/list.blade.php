{{-- Kế thừa layouts --}}
@extends('layouts.admin')

{{-- Định nghĩa nội dung --}}
@section('content')
    <div id="content" class="container-fluid">
        {{-- Hiển thị thông báo thêm thành công --}}
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
            <div class="card-header font-weight-bold d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold">Danh sách thiết lập</h5>
            </div>
            <div class="card-body">
                <table class="table table-hover">
                    <thead>
                        <tr class="bg-light">
                            <th scope="col" class="text-center">STT</th>
                            <th scope="col" class="text-center">Config key</th>
                            <th scope="col" class="text-center">Config value</th>
                            <th scope="col" class="text-center">Trạng thái</th>
                            <th scope="col" class="text-center">Người tạo</th>
                            <th scope="col" class="text-center">Ngày tạo</th>
                            <th scope="col" class="text-center">Tác vụ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($list_settings) > 0)
                            @php
                                !isset($_GET['page']) ? ($t = 0) : ($t = 6 * ($_GET['page'] - 1));
                            @endphp
                            @foreach ($list_settings as $item)
                                @php
                                    $t++;
                                @endphp
                                <tr>
                                    <td class="text-center">{{ $t }}</td>
                                    <td class="text-center">{{ $item->config_key }}</td>
                                    <td class="text-center">{{ Str::of($item->config_value)->limit(20) }}</td>
                                    <td class="text-center">
                                        <span class="badge badge-{{ $item->status == 'Công khai' ? 'primary' : 'warning' }} d-inline-block p-2">{{ $item->status }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="text-info">{{ $item->name }}</span>
                                    </td>
                                    <td class="text-center">
                                        {{ $item->created_at->format('H:i d-m-Y') }}
                                    </td>
                                    <td class="text-center">
                                        {{-- Thay đổi trạng thái --}}
                                        <a href="{{ route('admin.setting.changeStatus', $item->id) }}"
                                            class="btn btn-success btn-sm rounded-0" type="button" data-toggle="tooltip"
                                            data-placement="top" title="Thay đổi trạng thái">
                                            <i class="fas fa-sync-alt"></i>
                                        </a>
                                        {{-- Chỉnh sửa --}}
                                        <a href="{{ route('admin.setting.edit', ['id' => $item->id]) . '?type=' . $item->type}}"
                                            class="btn btn-success btn-sm rounded-0" type="button" data-toggle="tooltip"
                                            data-placement="top" title="Chỉnh sửa thông tin thiết lập">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        {{-- xóa vĩnh viễn --}}
                                        <a href="{{ route('admin.setting.delete', $item->id) }}"
                                            class="btn btn-danger btn-sm rounded-0" type="button" data-toggle="tooltip"
                                            data-placement="top" title="Xóa vĩnh viễn trang này"
                                            onclick="return confirm('Xác nhận xóa vĩnh viễn thiết lập này không ?');">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <td colspan="7">
                                <p class="text-danger">Không tìm thấy thiết lập nào !</p>
                            </td>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
        {{ $list_settings->links() }}
    </div>
@endsection
