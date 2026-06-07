<?php

namespace App\Http\Controllers;

use App\Models\Rank;
use Illuminate\Http\Request;

class RankController extends Controller
{
    public function index(Request $request)
    {
        $limit = $request->limit ?? 5;

        $ranks = Rank::query()->when($request->search, function ($query) use ($request) {
            return $query->whereAny(['name'], 'like', '%'.$request->search.'%');
        })->paginate($limit);

        return view('ranks.index', compact('ranks'));
    }
}
