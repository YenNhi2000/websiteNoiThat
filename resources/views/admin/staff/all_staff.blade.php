@extends('admin_layout')
@section('admin_content')

<div class="tables">
    <div class="bs-example widget-shadow" data-example-id="hoverable-table"> 
        <h4 class="text-center text-uppercase">Danh sách nhân viên</h4>

        <div class="row w3-res-tb">
            <div class="col-sm-5 m-b-xs">
                <a href="{{url('/staff-storage')}}" class="storage">Thùng rác</a>
            </div>
            <div class="col-sm-4"></div>
            <div class="col-sm-3">
                <div class="input-group">
                    <input type="text" class="input-sm form-control keywords" id="myInput" onkeyup="search()" placeholder="Tìm kiếm...">
                    <!-- <span class="input-group-btn">
                        <button class="btn btn-search" type="button">Tìm</button>
                    </span> -->
                </div>
            </div>
        </div>
        
        <table class="table table-striped b-t b-light table-hover" id="myTable">
            <thead>
                <tr>
                    <th style="width:20px;">
                        <label class="i-checks m-b-none">
                            <input type="checkbox"><i></i>
                        </label>
                    </th>
                    <th>Họ tên NV</th>
                    <th>SĐT</th>
                    <th>Email</th>
                    <th style="width:30px;"></th>
                </tr> 
            </thead>    
            <tbody class="staffList"> 
                @foreach($all_staff as $key => $staff)
                <tr>
                    <td><label class="i-checks m-b-none"><input type="checkbox" name="post[]"><i></i></label></td>
                    <td>{{ $staff->staff_name }}</td>
                    <td>{{ $staff->staff_phone }}</td>
                    <td>{{ $staff->staff_email }}</td>
                    <td>
                        <a href="{{URL::to('/delete-staff/'.$staff->staff_id)}}" class="active delete" onclick="return confirm('Bạn có muốn xóa nhân viên này không?')">
                            <i class="fa fa-times text-danger text" title="Xóa"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody> 
        </table>
    </div>
</div>

@endsection