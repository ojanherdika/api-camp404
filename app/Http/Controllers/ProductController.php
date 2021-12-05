<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Products;

class ProductController extends BaseController
{
    public function findAll(){
        $data = Products::paginate(10,[
            'id','title','category','price','stock','free_shipping','rate'
        ]);
        if(count($data)==0){
            return $this->out([],'Kosong',null,204);
        }else{
            return $this->out($data,'OK',null,200);
        }
    }
    public function findOne(Products $produk){
        return $this->out($produk,'OK',null,200);
    }
}
