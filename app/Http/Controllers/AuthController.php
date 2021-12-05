<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use App\Models\Customers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
class AuthController extends BaseController
{
    public function auth(){
        $authheader=request()->header('Authorization'); //basic base64encode
        $keyauth=substr($authheader,6); //hilangkan text basic

        $plainauth=base64_decode($keyauth); //decode text info login
        $tokenauth=explode(':',$plainauth); //pisahkan email dan password

        $email=$tokenauth[0]; //email
        $pass=$tokenauth[1]; //password
        $data= (new Customers())->newQuery()
                ->where(['email'=>$email])
                ->get(['id','first_name','last_name','email','password'])->first();
        if($data==null){
            return $this->out(null,'Gagal',['Pengguna Tidak Ditemukan'],404);
        }else{
            if(Hash::check($pass, $data->password)){
                $data->token=hash('sha256', Str::random(10));
                unset($data->password);
                $data->update();
                return $this->out($data,'OK',null,200);
            }else{
                return $this->out(null,'Gagal',['Anda Tidak Memiliki Wewenang'],401 
                );
            }
        }
        
    }
}
