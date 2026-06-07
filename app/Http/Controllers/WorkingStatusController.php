<?php

namespace App\Http\Controllers;

use App\Models\WorkingStatus;
use Illuminate\Http\Request;

class WorkingStatusController extends Controller
{
    public function index(Request $request)
    {
        $statuses = WorkingStatus::query()
            ->when($request->search, fn ($q) => $q->where('name', 'like', "%{$request->search}%"))
            ->latest()
            ->paginate(10);

        return view('working-statuses.index', compact('statuses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:units,name',
        ]);

        WorkingStatus::create([
            'name' => $request->name,
            'is_active' => $request->boolean('is_active', true),
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        return redirect()->route('working-statuses.index')
            ->with('success', 'ເພີ່ມຂໍ້ມູນສຳເລັດແລ້ວ');
    }

    public function update(Request $request, WorkingStatus $workStatus)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:units,name,'.$workStatus->id,
        ]);

        $workStatus->update([
            'name' => $request->name,
            'is_active' => $request->boolean('is_active'),
            'updated_by' => 1,
        ]);

        return redirect()->route('working-statuses.index')
            ->with('success', 'ແກ້ໄຂຂໍ້ມູນສຳເລັດແລ້ວ');
    }

    public function destroy(WorkingStatus $workStatus)
    {
        $workStatus->delete();

        return redirect()->route('working-statuses.index')
            ->with('success', 'ລຶບຂໍ້ມູນສຳເລັດແລ້ວ');
    }
}
