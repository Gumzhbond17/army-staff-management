<?php

namespace App\Http\Controllers;

use App\Models\StaffCategory;
use Illuminate\Http\Request;

class StaffCategoryController extends Controller
{
    public function index(Request $request)
    {
        $staff_categories = StaffCategory::query()
            ->when($request->search, fn ($q) => $q->where('name', 'like', "%{$request->search}%"))
            ->latest()
            ->paginate(10);

        return view('staff-categories.index', compact('staff_categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:staff_categories,name',
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

    // ✅ show
    public function show(StaffCategory $staffCategory)
    {
        return view('staff-categories.show', compact('staffCategory'));
    }

    // ✅ edit
    public function edit(StaffCategory $staffCategory)
    {
        return view('staff-categories.edit', compact('staffCategory'));
    }

    // ✅ update
    public function update(Request $request, StaffCategory $staffCategory)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:staff_categories,name,'.$staffCategory->id,
        ]);

        $staffCategory->update([
            'name' => $request->name,
            'is_active' => $request->boolean('is_active'),
            'updated_by' => 1,
        ]);

        return redirect()->route('staff-categories.index')
            ->with('success', 'ແກ້ໄຂຂໍ້ມູນສຳເລັດແລ້ວ');
    }

    // ✅ destroy
    public function destroy(StaffCategory $staffCategory)
    {
        $staffCategory->delete();

        return redirect()->route('staff-categories.index')
            ->with('success', 'ລຶບຂໍ້ມູນສຳເລັດແລ້ວ');
    }
}
