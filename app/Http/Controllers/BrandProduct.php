<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\Type;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\Ex_Brand;
use App\Imports\Im_Brand;
use App\Models\Admin;
use App\Models\Customer;

session_start();

class BrandProduct extends Controller
{
    public function AuthLogin(){
        $admin_id = Session::get('admin_id');
        if($admin_id){
            return Redirect::to('/all-brand-product');
        }else{
            return Redirect::to('/admin')->send();
        }
    }

    public function all_brand_product(){
        $this->AuthLogin();
        // Thông tin admin
        $info = Admin::where('admin_id', Session::get('admin_id'))->first();
        
        // $all_brand_product = DB::table('tbl_brand')->get();

        $all_brand_product = Brand::where('brand_storage','0')->orderBy('brand_id','desc')->get();
        // $all_brand_product = Brand::orderBy('brand_id','desc')->get();  //xếp ds giảm dần
        // $all_brand_product = Brand::orderBy('brand_id','desc')->take(1)->get(); //dùng take để show số lượng sp mong muốn
        // $all_brand_product = Brand::orderBy('brand_id','desc')->paginate(2);    //phân trang

        return view('admin.brand.all_brand')->with(compact('info', 'all_brand_product'));
    }

    public function import_brand(Request $request){
        $path = $request->file('file')->getRealPath();
        Excel::import(new Im_Brand, $path);
        return back();
    }

    public function export_brand(){
        return Excel::download(new Ex_Brand,'brand_product.xlsx');
    }

    public function add_brand_product(){
        $this->AuthLogin();

        return view('admin.brand.add_brand');
    }

    public function save_brand_product(Request $request){
        $this->AuthLogin();
        // $data = array();
        // $data['brand_name'] = $request->brand_product_name;
        // $data['brand_desc'] = $request->brand_product_desc;
        // $data['brand_status'] = $request->brand_product_status;
        // DB::table('tbl_brand')->insert($data);

        $this->validate($request, [
            'brand_name' => 'required|unique:tbl_brand,brand_name|max:255',
            'brand_slug' => 'required|max:255',
            'brand_desc' => 'required',
        ],
        [
            'brand_name.required' => 'Bạn chưa nhập tên thương hiệu sản phẩm',
            'brand_name.unique' => 'Tên thương hiệu đã có. Vui lòng điền tên khác',
            'brand_slug.required' => 'Bạn chưa nhập slug thương hiệu sản phẩm',
            'brand_desc.required' => 'Bạn chưa nhập mô tả thương hiệu sản phẩm',
        ]);

        $data = $request->all();
        $brand = new Brand();
        $brand->brand_name = $data['brand_name'];
        $brand->brand_slug = $data['brand_slug'];
        $brand->brand_desc = $data['brand_desc'];
        $brand->brand_status = $data['brand_status'];
        $brand->save();

        Toastr::success('Thêm thương hiệu sản phẩm thành công','');
        return Redirect::to('add-brand-product');
    }

    public function unactive_brand($brand_pro_slug){
        $this->AuthLogin();
        Brand::where('brand_slug', $brand_pro_slug)->update(['brand_status'=>1]);
        Toastr::success('Kích hoạt thương hiệu sản phẩm thành công','');
        return redirect()->back();
    }

    public function active_brand($brand_pro_slug){
        $this->AuthLogin();
        Brand::where('brand_slug', $brand_pro_slug)->update(['brand_status'=>0]);
        Toastr::success('Bỏ kích hoạt thương hiệu sản phẩm thành công','');
        return redirect()->back();
    }

    public function edit_brand_product($brand_pro_slug){
        $this->AuthLogin();

        // $edit_brand_product = DB::table('tbl_brand')->where('brand_slug', $brand_pro_slug)->get();

        $edit_brand_product = Brand::where('brand_slug', $brand_pro_slug)->get();
        // $edit_brand_product = Brand::find($brand_pro_slug);
        return view('admin.brand.edit_brand')->with(compact('edit_brand_product'));
    }

    public function update_brand_product(Request $request, $brand_id){
        $this->AuthLogin();

        $data = $request->all();
        $brand = Brand::find($brand_id);
        $brand->brand_name = $data['brand_name'];
        $brand->brand_slug = $data['brand_slug'];
        $brand->brand_desc = $data['brand_desc'];
        $brand->save();

        Toastr::success('Cập nhật thương hiệu sản phẩm thành công','');
        return Redirect::to('all-brand-product');
    }

    public function delete_brand_product($brand_id){
        $this->AuthLogin();
        $del_brand = Brand::find($brand_id);
        // $del_brand->delete();
        $del_brand->brand_storage = '1';
        $del_brand->save();
        Toastr::success('Xóa thương hiệu sản phẩm thành công','');
        return redirect()->back();
    }

    public function brand_storage(){
        $this->AuthLogin();

        $storage = Brand::where('brand_storage','1')->orderBy('brand_id','desc')->get();
        return view('admin.brand.storage')->with(compact('storage'));
    }

    public function restore_brand($brand_id){
        $this->AuthLogin();
        $restore = Brand::find($brand_id);
        $restore->brand_storage = '0';
        $restore->save();
        Toastr::success('Khôi phục thương hiệu sản phẩm thành công','');
        return redirect()->back();
    }

//End Admin 



    public function show_brand(Request $request, $brand_slug){
        $cat = Category::where('category_status','1')->orderBy('category_id','desc')->get();
        $brand = Brand::where('brand_status','1')->orderBy('brand_id','desc')->get();
        $type = Type::where('type_status','1')->orderBy('type_id','desc')->get();
        $feature_product = Product::where('product_status','1')->orderBy('product_id','asc')->limit(6)->get();

        // Seo  
        $url_canonical = $request->url();

        // Thông tin khách hàng
        $result = Customer::where('customer_id', Session::get('customer_id'))->first(); 

        $brand_by_id = DB::table('tbl_product')
            ->join('tbl_brand','tbl_brand.brand_id', '=', 'tbl_product.brand_id')
            ->where('tbl_product.brand_slug', $brand_slug)->get();

        $brand_name = Brand::where('brand_slug', $brand_slug)->limit(1)->get();

        return view('pages.brand.show_brand')
            ->with(compact('cat_pro','brand_pro','type_pro','feature_pro','brand_by_id','brand_name', 'url_canonical', 'result'));
    }
}
