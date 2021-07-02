<?php
namespace App\Http\Controllers\API;

use App\Models\HotlineShopping;

class HotlineShoppingController
{
    public function index(){
        $data = HotlineShopping::all();

        return [
            'status' => 'OK',
            'data' => $data
        ];
    }
}
