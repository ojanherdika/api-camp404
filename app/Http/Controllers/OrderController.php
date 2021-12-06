<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Orders, App\Models\Products, Carbon\Carbon;

class OrderController extends BaseController
{
    public function __construct(){
        $this->middleware('authorization');
    }
    public function store(){
        $product = Products::find(\request('product_id'));
        if($product==null){
            return $this->out(null,'Gagal',['Produk tidak Ditemukan'],404);
        }
        $order=new Orders();
        $order->order_date=Carbon::now('Asia/Jakarta');
        $order->product_id= $product->id;
        $order->customer_id=request('customer_id');
        $order->qty=request('qty');
        $order->price=$product->price;

        if($order->save()==true){
            return $this->out($order,'OK',null,201);
        }else{
            return $this->out(null,'Gagal',['Order Gagal Disimpan'],504);
        }
    }
    public function findAll(){
        $order =Orders::query()
                ->leftjoin('customers','customers.id','=','orders.customer_id')
                ->leftjoin('products','products.id', '=', 'orders.product_id');
        if(request()->has('q')){
            $q=request('q');
            $order->where('products.title', 'like',"%$q%");
        }
        $data = $order->paginate(10,
        ['orders.*','customers.first_name','customers.last_name','customers.address',
        'customers.city','products.title as product_title']
        );
        return $this->out($data,'OK',null,201);
    }
    public function update(Orders $order){
        $product = Products::find(request('product_id'));
        if($product==null){
            return $this->out(null,'Gagal','[Produk Tidak ditemukan]',404);
        }
        $order->product_id=$product->id;
        $order->customer_id=request('customer_id');
        $order->qty=request('qty');
        $order->price=$product->price;
        $hasil=$order->save();
        return $this->out(
            $hasil ? $order:null, $hasil ? 'OK':'Gagal', $hasil ? null : ['gagal merubah data'], $hasil ? 201:504
        );
    }
    public function delete(Orders $order){
        $hasil=$order->delete();
        return $this->out(
            $hasil?$order:null,$hasil?'OK':'Gagal',$hasil?null:['Gagal hapus data'],$hasil?200:504
        );
    }
}
