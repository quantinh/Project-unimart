{{-- Kế thừa layouts --}}
@extends('layouts.admin')

{{-- Định nghĩa nội dung --}}
@section('content')
    <div id="content" class="container-fluid">
        <div class="row">
            <div class="col-4">
                {{-- Hiển thị thông báo thêm thành công --}}
                @if (session('status'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('status') }}
                        <button class="close" data-dismiss="alert" aria-lable="close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                <div class="card">
                    <div class="card-header font-weight-bold">
                        Thêm nhóm quyền mới
                    </div>
                    <div class="card-body">
                        <form action="{{ url('admin/roles/store') }}" method="" enctype="multipart/form-data">
                            @csrf
                            {{-- Name_roles  --}}
                            <div class="form-group">
                                <label for="name_role">Tên nhóm quyền</label>
                                <input class="form-control @error('name_role') is-invalid @enderror" type="text" name="name_role" id="name_role">
                                @error('name_role')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            {{-- Descriptions  --}}
                            <div class="form-group">
                                <label for="description">Mô tả nhóm quyền</label>
                                <input class="form-control @error('description') is-invalid @enderror" type="text" name="description" id="description">
                                @error('description')
                                    <small class='text-danger'>{{ $message }}</small>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary" name="addrole" value="addrole">Thêm mới</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-8">
                <div class="card">
                    <div class="card-header font-weight-bold">
                        Danh sách nhóm quyền
                    </div>
                    <div class="card-body">
                        @if (count($list_roles) > 0)
                            @php
                                !isset($_GET['page']) ? ($t = 0) : ($t = 6 * ($_GET['page'] - 1));
                            @endphp
                            <table class="table table-hover">
                                <thead>
                                    <tr class="bg-light">
                                        <th class="text-center">STT</th>
                                        <th>Tên nhóm quyền</th>
                                        <th>Mô tả quyền</th>
                                        <th>Tác vụ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($list_roles as $key => $role)
                                        @php
                                            $t++;
                                        @endphp
                                        <tr>
                                            <td class="text-center">{{ $t }}</th>
                                            <td>
                                                <span>{{ $role->name_role }}</span>
                                            </td>
                                            <td>
                                                <span>{{ $role->description }}</span>
                                            </td>
                                            <td>
                                                {{-- Nếu trong thùng rác thì có các thao tác --}}
                                                @if ($role->name_role == 'Administrator')
                                                    {{-- Chỉnh sửa thông tin --}}
                                                    <a href="{{ route('admin.role.edit', $role->id) }}"
                                                        class="btn btn-success btn-sm rounded-0 text-white"
                                                        type="button" data-toggle="tooltip" data-placement="top"
                                                        title="Chỉnh sửa thông tin">
                                                        <i class="fas fa-user-edit"></i>
                                                    </a>
                                                    {{-- Ngược lại thì có các thao tác --}}
                                                @else
                                                    {{-- Chỉnh sửa thông tin --}}
                                                    <a href="{{ route('admin.role.edit', $role->id) }}"
                                                        class="btn btn-success btn-sm rounded-0 text-white"
                                                        type="button" data-toggle="tooltip" data-placement="top"
                                                        title="Chỉnh sửa thông tin">
                                                        <i class="fas fa-user-edit"></i>
                                                    </a>
                                                    {{-- Xóa vĩnh viễn --}}
                                                    <a href="{{ route('admin.role.delete', $role->id) }}"
                                                        onclick="return confirm('Bạn có chắc chắn xóa quyền này không ?')"
                                                        class="btn btn-danger btn-sm rounded-0 text-white"
                                                        type="button" data-toggle="tooltip" data-placement="top"
                                                        title="Xóa vĩnh viễn">
                                                        <i class="fa fa-trash"></i>
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <td colspan="7" class="bg-white">
                                <p class="text-danger">Không tìm thấy nhóm quyền nào trong hệ thống !</p>
                            </td>
                        @endif
                    </div>
                </div>
                <div>
                    {{-- Thanh phân trang --}}
                    {{ $list_roles->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
