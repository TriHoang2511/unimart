<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // public function create() {
    //     return view('admin.product.add');
    // }

    public function index (){
        return view('admin.product.index');
    }
}
