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
                <h6 class="m-0 font-weight-bold">Danh sách thành viên</h6>
                <div class="form-search form-inline">
                    <form action="#">
                        <input type="text" name="keyword" value="{{ request()->input('keyword') }}"
                            class="form-control form-search" placeholder="Nhập từ khóa..." style="padding: 10px;">
                        <input type="submit" name="btn-search" value="Tìm kiếm" class="btn btn-primary">
                    </form>
                </div>
            </div>
            <div class="card-body">
                <div class="analytic">
                    <a href="{{ request()->fullUrlwithQuery(['status' => 'active']) }}" class="text-primary">Kích
                        hoạt<span class="text-muted"> ({{ $count[0] }})</span></a>
                    <a href="{{ request()->fullUrlwithQuery(['status' => 'trash']) }}" class="text-primary">Vô hiệu
                        hóa<span class="text-muted"> ({{ $count[1] }})</span></a>
                </div>
                <form action="{{ url('admin/users/action') }}" method="">
                    <div class="form-action form-inline py-3">
                        <select class="form-control mr-1" name="act" id="">
                            <option>Chọn tác vụ</option>
                            @foreach ($list_act as $k => $act)
                                <option value="{{ $k }}">{{ $act }}</option>
                            @endforeach
                        </select>
                        <input type="submit" name="btn-search" value="Áp dụng" class="btn btn-primary">
                    </div>
                    <table class="table table-hover table-checkall">
                        <thead>
                            <tr class="bg-light">
                                <th class="text-center"><input type="checkbox" name="checkall"></th>
                                <th scope="col" class="text-center">STT</th>
                                <th scope="col" class="text-center">Ảnh</th>
                                <th scope="col" class="text-center">Họ và tên</th>
                                <th scope="col" class="text-center">Email</th>
                                <th scope="col" class="text-center">Quyền</th>
                                <th scope="col" class="text-center">Ngày tạo</th>
                                @if (request()->status == 'active')
                                    <th scope="col" class="text-center">Ngày cập nhật</th>
                                @else
                                    <th scope="col" class="text-center">Ngày vô hiệu hóa</th>
                                @endif
                                @if (request()->status == 'trash')
                                    <th scope="col" class="text-center">Tình trạng</th>
                                @else
                                    <th scope="col" class="text-center">Tác vụ</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($users) > 0)
                                @php
                                    !isset($_GET['page']) ? ($t = 0) : ($t = 6 * ($_GET['page'] - 1));
                                @endphp
                                @foreach ($users as $user)
                                    @php
                                        $t++;
                                    @endphp
                                    <tr>
                                        <td class="text-center">
                                            <input type="checkbox" name="list_check[]" value="{{ $user->id }}">
                                        </td>
                                        <td scope="row" class="text-center">{{ $t }}</td>
                                        <td class="box_img_admin text-center">
                                            <img src="{{ asset($user->avatar) }}" class="img-fluid" alt="Logo">
                                        </td>
                                        <td class="text-info text-center">{{ $user->name }}</td>
                                        <td class="text-center">{{ $user->email }}</td>
                                        <td class="text-center">{{ $user->name_role }}</td>
                                        <td class="text-center">{{ $user->created_at->format('H:i d-m-Y')  }}
                                        </td>
                                        <td class="text-center">{{ $user->updated_at->format('H:i d-m-Y') }}
                                        </td>
                                        {{-- Nếu đang tình trạng vô hiệu hóa thì hiển thị 2 nút  --}}
                                        @if (request()->status == 'trash')
                                            <td class="text-center">
                                                <a href="{{ route('user.restore', $user->id) }}"
                                                    class="btn btn-success btn-sm rounded-0 text-white" type="button"
                                                    data-toggle="tooltip" data-placement="top"
                                                    title="Khôi phục">
                                                    <i class="fas fa-trash-restore-alt"></i>
                                                </a>
                                                {{-- Xóa --}}
                                                <a href="{{ route('user.permanentlyDelete', $user->id) }}"
                                                    onclick="return confirm('Bạn có chắc xóa thành viên này không ?')"
                                                    class="btn btn-danger btn-sm rounded-0 text-white" type="button"
                                                    data-toggle="tooltip" data-placement="top" title="Xóa vĩnh viễn">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            </td>
                                        @else
                                            <td class="text-center">
                                                <a href="{{ route('user.edit', $user->id) }}"
                                                    class="btn btn-success btn-sm rounded-0 text-white" type="button"
                                                    data-toggle="tooltip" data-placement="top"
                                                    title="Chỉnh sửa thông tin">
                                                    <i class="fas fa-user-edit"></i>
                                                </a>
                                                @if (Auth::id() != $user->id)
                                                    <a href="{{ route('user.delete', $user->id) }}"
                                                        onclick="return confirm('Bạn có chắc chắn muốn vô hiệu hóa thành viên này không ?')"
                                                        class="btn btn-danger btn-sm rounded-0 text-white" type="button"
                                                        data-toggle="tooltip" data-placement="top"
                                                        title="Vô hiệu hóa thành viên">
                                                        <i class="fas fa-user-slash"></i>
                                                    </a>
                                                @endif
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="7" class="bg-white">
                                        <span class="text-danger">Không tìm thấy thành viên nào trong hệ thống !</span>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                    {{-- Thanh phân trang --}}
                    {{ $users->links() }}
                </form>
            </div>
        </div>
    </div>
@endsection
