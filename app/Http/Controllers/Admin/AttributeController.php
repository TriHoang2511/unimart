<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\AttributeValue;
use Illuminate\Http\Request;

class AttributeController extends Controller
{
    // ===================================================
    // ATTRIBUTE PARENT
    // ===================================================

    public function index()
    {
        $attributes = Attribute::with('values')->get();
        return view('admin.product.attribute.index', compact('attributes'));
    }

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

    public function edit(Attribute $attribute)
    {
        $attribute->load('values');
        return view('admin.product.attribute.edit', compact('attribute'));
    }

    public function update(Request $request, Attribute $attribute)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:attributes,name,' . $attribute->id,
        ]);

        $attribute->update([
            'name' => $request->name,
        ]);

        return redirect()
            ->route('admin.product.attributes.index')
            ->with('status', 'Đã cập nhật thuộc tính "' . $attribute->name . '" thành công!');
    }

    // ===================================================
    // ATTRIBUTE VALUES
    // ===================================================

    /**
     * List tất cả giá trị của 1 thuộc tính
     */
    public function listValues(Attribute $attribute)
    {
        return $attribute->values()
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Thêm giá trị cho thuộc tính
     */
    public function storeValue(Request $request, Attribute $attribute)
    {
        $request->validate([
            'value' => 'required|string|max:255',
            'sku_code' => 'required|string',
        ], [
            'value.required' => 'Giá trị không được để trống.',
            'sku_code.required' => 'SKU không được để trống.',
        ]);

        // Chuẩn hóa SKU
        $sku = strtoupper($request->sku_code);

        // Kiểm tra trùng SKU trong cùng attribute
        if (
            AttributeValue::where('attribute_id', $attribute->id)
                ->where('sku_code', $sku)
                ->exists()
        ) {
            return back()->withErrors([
                'sku_code' => 'SKU này đã tồn tại trong thuộc tính.'
            ]);
        }

        AttributeValue::create([
            'attribute_id' => $attribute->id,
            'value' => $request->value,
            'sku_code' => $sku,
            'is_active' => true,
        ]);

        return back()->with('status', 'Thêm giá trị thành công!');
    }

    /**
     * Cập nhật giá trị thuộc tính
     */
    public function updateValue(Request $request, AttributeValue $value)
    {
        $request->validate([
            'value' => 'required|string|max:255',
            'sku_code' => 'required|string',
            // 'is_active' => 'boolean',
        ]);

        $sku = strtoupper($request->sku_code);

        // Check trùng SKU (ngoại trừ chính nó)
        if (
            AttributeValue::where('attribute_id', $value->attribute_id)
                ->where('sku_code', $sku)
                ->where('id', '!=', $value->id)
                ->exists()
        ) {
            return back()->withErrors([
                'sku_code' => 'SKU này đã tồn tại.'
            ]);
        }

        $value->update([
            'value' => $request->value,
            'sku_code' => $sku,
            // 'is_active' => $request->boolean('is_active'),
        ]);

        return back()->with('status', 'Cập nhật giá trị thành công!');
    }

    /**
     * Bật / tắt sử dụng giá trị
     */
    public function toggleValue(AttributeValue $value)
    {
        $value->update([
            'is_active' => ! $value->is_active
        ]);

        return back()->with('status', 'Đã cập nhật trạng thái.');
    }

    /**
     * Xóa giá trị (chỉ nên dùng khi chưa gán SKU)
     */
    public function destroyValue(AttributeValue $value)
    {
        // TODO: kiểm tra đã gán SKU hay chưa
        $value->delete();

        return back()->with('status', 'Đã xóa giá trị thuộc tính.');
    }
}
