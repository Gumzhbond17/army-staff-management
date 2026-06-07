<?php

namespace App\Http\Controllers;

use App\Models\Province;
use Illuminate\Http\Request;

class ProvinceController extends Controller
{
    public function index(Request $request)
    {
        $provinces = Province::query()
            ->when($request->search, fn ($q) => $q
                ->where('code', 'like', "%{$request->search}%")
                ->orWhere('name', 'like', "%{$request->search}%")
            )
            ->orderBy('code', 'desc')
            ->paginate(10);

        return view('provinces.index', compact('provinces'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|integer',
            'name' => 'required|string|max:255|unique:ranks,name',
        ]);

        Province::create([
            'code' => $request->code,
            'name' => $request->name,
            'is_active' => $request->boolean('is_active', true),
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        return redirect()->route('provinces.index')
            ->with('success', 'ເພີ່ມຂໍ້ມູນສຳເລັດແລ້ວ');
    }

    public function update(Request $request, Province $province)
    {
        $request->validate([
            'code' => 'required|integer|unique:provinces,code,'.$province->id,
            'name' => 'required|string|max:255|unique:provinces,name,'.$province->id,
        ]);

        $province->update([
            'code' => $request->code,
            'name' => $request->name,
            'is_active' => $request->boolean('is_active'),
            'updated_by' => 1,
        ]);

        return redirect()->route('provinces.index')
            ->with('success', 'ແກ້ໄຂຂໍ້ມູນສຳເລັດແລ້ວ');
    }

    public function destroy(Province $province)
    {
        $province->delete();

        return redirect()->route('provinces.index')
            ->with('success', 'ລຶບຂໍ້ມູນສຳເລັດແລ້ວ');
    }
}
