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
    public function storeValue(Request $request, $attributeId)
    {
        $request->validate([
            'value' => 'required|string|max:255',
        ]);

        // Sử dụng $attributeId trực tiếp từ URL thay vì hidden input (nếu muốn)
        AttributeValue::create([
            'attribute_id' => $attributeId,
            'value' => $request->value
        ]);

        return back()->with('status', 'Thêm giá trị thành công!');
    }

    // 3. Xóa giá trị con
    // public function destroyValue($id)
    // {
    //     $value = AttributeValue::findOrFail($id);
    //     $value->delete();

    //     return back()->with('status', 'Đã xóa giá trị thuộc tính.');
    // }

    public function destroyValue(AttributeValue $value)
    {
        $value->delete();
        return back()->with('status', 'Đã xóa giá trị thuộc tính.');
    }
}
