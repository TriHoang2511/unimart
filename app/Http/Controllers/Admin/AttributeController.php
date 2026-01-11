<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\AttributeValue;
use Illuminate\Http\Request;

class AttributeController extends Controller
{
    // Hiển thị danh sách
    public function index()
    {
        $attributes = Attribute::with('values')->get();
        return view('admin.product.attribute.index', compact('attributes'));
    }

    // 1. Thêm thuộc tính cha (Màu sắc, Size...)
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:attributes,name',
        ], [
            'name.required' => 'Tên thuộc tính không được để trống.',
            'name.unique' => 'Thuộc tính này đã tồn tại.',
        ]);

        Attribute::create([
            'name' => $request->name
        ]);

        return back()->with('status', 'Đã thêm thuộc tính mới thành công!');
    }

    // 2. Thêm giá trị con (Đỏ, Xanh, 128GB...)
    public function storeValue(Request $request)
    {
        $request->validate([
            'attribute_id' => 'required|exists:attributes,id',
            'value' => 'required|string|max:255',
        ], [
            'value.required' => 'Giá trị không được để trống.',
        ]);

        // Logic chống trùng lặp giá trị trong cùng 1 thuộc tính
        $exists = AttributeValue::where('attribute_id', $request->attribute_id)
                                ->where('value', $request->value)
                                ->exists();
        
        if ($exists) {
            return back()->with('error', 'Giá trị này đã tồn tại trong thuộc tính.');
        }

        AttributeValue::create([
            'attribute_id' => $request->attribute_id,
            'value' => $request->value
        ]);

        return back()->with('status', 'Đã thêm giá trị mới thành công!');
    }

    // 3. Xóa giá trị con
    public function destroyValue($id)
    {
        $value = AttributeValue::findOrFail($id);
        $value->delete();

        return back()->with('status', 'Đã xóa giá trị thuộc tính.');
    }
}