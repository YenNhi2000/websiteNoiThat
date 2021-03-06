@extends('admin_layout')
@section('admin_content')

<div class="tables">
    <div class="bs-example widget-shadow" data-example-id="hoverable-table">
        <div class="w3-res-tb mb-2 luutru">
            <a href="{{url('/all-staff')}}" class="">
                <i class="fa fa-chevron-left back"></i>Quay về
            </a>
        </div>
        <h4 class="text-center text-uppercase">Danh sách lưu trữ</h4>

        <table class="table table-striped b-t b-light table-hover">
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
                    <th style="width:60px;"></th>
                </tr> 
            </thead>    
            <tbody> 
                @foreach($storage as $key => $val)
                <tr>
                    <td><label class="i-checks m-b-none"><input type="checkbox" name="post[]"><i></i></label></td>
                    <td>{{ $val->staff_name }}</td>
                    <td>{{ $val->staff_phone }}</td>
                    <td>{{ $val->staff_email }}</td>
                    <td>
                        <a href="{{URL::to('/restore-staff/'.$val->staff_id)}}" class="active " onclick="return confirm('Bạn có muốn khôi phục nhân viên này không?')">
                            <i class="fa fa-undo" title="Khôi phục"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody> 
        </table>
    </div>
</div>

@endsection