<?php

namespace App\Http\Controllers;

use App\Models\District;
use App\Models\Province;
use Illuminate\Http\Request;

class DistrictController extends Controller
{
    public function index(Request $request)
    {
        $districts = District::with('province:id,name,code')
            ->when($request->search, fn ($q) => $q
                ->where('name', 'like', "%{$request->search}%")
            )
            ->orderBy('id', 'desc')
            ->paginate(10);

        $provinces = Province::query()->where('is_active', '=', 1)->get();

        return view('districts.index', compact('districts', 'provinces'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'province_id' => 'required|exists:provinces,id',
            'name' => 'required|string|max:255|unique:districts,name',
        ]);

        District::create([
            'province_id' => $request->province_id,
            'name' => $request->name,
            'is_active' => $request->boolean('is_active', true),
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        return redirect()->route('districts.index')
            ->with('success', 'ເພີ່ມຂໍ້ມູນສຳເລັດແລ້ວ');
    }

    public function update(Request $request, District $district)
    {
        $request->validate([
            'province_id' => 'required|exists:provinces,id',
            'name' => 'required|string|max:255|unique:districts,name,'.$district->id,
        ]);

        $district->update([
            'province_id' => $request->province_id,
            'name' => $request->name,
            'is_active' => $request->boolean('is_active'),
            'updated_by' => 1,
        ]);

        return redirect()->route('districts.index')
            ->with('success', 'ແກ້ໄຂຂໍ້ມູນສຳເລັດແລ້ວ');
    }

    public function destroy(District $district)
    {
        $district->delete(true);

        return redirect()->route('districts.index')
            ->with('success', 'ລຶບຂໍ້ມູນສຳເລັດແລ້ວ');
    }

    public function byProvince(Province $province)
    {
        return response()->json(
            $province->districts()->orderBy('name','asc')->get(['id', 'name'])
        );
    }
}
