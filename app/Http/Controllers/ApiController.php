<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\GCList;

class ApiController extends Controller
{
    public function insert()
    {
        $url = 'https://egc.digits.com.ph/api/egc_campaign';
        $url1 = 'http://127.0.0.1:8000/api/egc_campaign';
        $secretKey = env('EGC_SECRET_KEY');

        $data = json_decode(file_get_contents("$url1/$secretKey"));
        
        if((bool) ($data)){
            foreach ($data as $item) {
                $item = (array) $item;
                $item['is_fetch'] = 1;
                $item['created_at'] = date('Y-m-d H:i:s');
                GCList::updateOrInsert(['id' => $item['id']], $item);
            }
            
            return true;
        }else{
            return false;
        }
    }

    public function fetch($secretKey) 
    {
        $envKey = env('EGC_SECRET_KEY');

        if ($secretKey == $envKey){
            return GCList::where('is_fetch', 1)->orderBy('id', 'desc')->limit(1000)->pluck('id')->toArray();
        }else{
            return 'Secret Key mismatch';
        }
    }
}
