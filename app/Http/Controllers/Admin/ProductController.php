<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // public function create() {
    //     return view('admin.product.add');
    // }

    public function index()
    {
        return view('admin.product.index');
    }

    public function create()
    {
        $categories = ProductCategory::all();
        $categories = $this->data_tree($categories);
        return view('admin.product.create', compact('categories'));
    }

    public function data_tree($categories, $parent_id = 0, $level = 0)
    {
        $result = [];
        foreach ($categories as $category) {
            if ($category->parent_id == $parent_id) {
                $category->level = $level;

                // Kiểm tra xem thằng này có con hay không
                $hasChild = $this->has_child($categories, $category->id);
                $category->has_child = $hasChild; // Lưu vào đối tượng luôn

                $result[] = $category;

                if ($hasChild) {
                    $result_child = $this->data_tree($categories, $category->id, $level + 1);
                    $result = array_merge($result, $result_child);
                }
            }
        }
        return $result;
    }

    public function has_child($data, $id)
    {
        foreach ($data as $item) {
            if ($item->parent_id == $id) {
                return true;
            }
        }
        return false;
    }

    public function edit()
    {
        return view('admin.product.edit');
    }
}
