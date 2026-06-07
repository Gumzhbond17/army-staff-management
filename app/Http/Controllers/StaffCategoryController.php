<?php

namespace App\Http\Controllers;

use App\Models\StaffCategory;
use Illuminate\Http\Request;

class StaffCategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = StaffCategory::query()
            ->when($request->search, fn ($q) => $q->where('name', 'like', "%{$request->search}%"))
            ->latest()
            ->paginate(10);

        return view('staff-categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:units,name',
        ]);

        StaffCategory::create([
            'name' => $request->name,
            'is_active' => $request->boolean('is_active', true),
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        return redirect()->route('staff-categories.index')
            ->with('success', 'ເພີ່ມຂໍ້ມູນສຳເລັດແລ້ວ');
    }

    public function update(Request $request, StaffCategory $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:units,name,'.$category->id,
        ]);

        $category->update([
            'name' => $request->name,
            'is_active' => $request->boolean('is_active'),
            'updated_by' => 1,
        ]);

        return redirect()->route('staff-categories.index')
            ->with('success', 'ແກ້ໄຂຂໍ້ມູນສຳເລັດແລ້ວ');
    }

    public function destroy(StaffCategory $category)
    {
        $category->delete();

        return redirect()->route('staff-categories.index')
            ->with('success', 'ລຶບຂໍ້ມູນສຳເລັດແລ້ວ');
    }
}
