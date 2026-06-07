<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function index(Request $request)
    {
        $units = Unit::query()
            ->when($request->search, fn ($q) => $q
                ->where('name', 'like', "%{$request->search}%")
            )
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('units.index', compact('units'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:units,name',
        ]);

        Unit::create([
            'name' => $request->name,
            'is_active' => $request->boolean('is_active', true),
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        return redirect()->route('units.index')
            ->with('success', 'ເພີ່ມຂໍ້ມູນສຳເລັດແລ້ວ');
    }

    public function update(Request $request, Unit $unit)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:units,name,'.$unit->id,
        ]);

        $unit->update([
            'name' => $request->name,
            'is_active' => $request->boolean('is_active'),
            'updated_by' => 1,
        ]);

        return redirect()->route('units.index')
            ->with('success', 'ແກ້ໄຂຂໍ້ມູນສຳເລັດແລ້ວ');
    }

    public function destroy(Unit $unit)
    {
        $unit->delete(true);

        return redirect()->route('units.index')
            ->with('success', 'ລຶບຂໍ້ມູນສຳເລັດແລ້ວ');
    }
}
