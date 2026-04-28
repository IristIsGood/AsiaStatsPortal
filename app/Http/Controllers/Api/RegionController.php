<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\FormatsApiResponse;
use App\Models\Region;
use Illuminate\Http\Request;

class RegionController extends Controller
{
    use FormatsApiResponse;

    public function index(Request $request)
    {
        $regions = Region::all();
        return response()->json($regions);
    }

    public function show(Request $request, $id)
    {
        $region = Region::with('children')->findOrFail($id);
        return response()->json($region);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'country_code' => 'required|max:10',
            'name'         => 'required|max:255',
            'level'        => 'required|in:national,sub-national',
            'parent_id'    => 'nullable|exists:regions,id',
        ]);

        $region = Region::create($validated);
        return response()->json($region, 201);
    }

    public function update(Request $request, $id)
    {
        $region = Region::findOrFail($id);
        $region->update($request->all());
        return response()->json($region);
    }

    public function destroy($id)
    {
        Region::findOrFail($id)->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }
}