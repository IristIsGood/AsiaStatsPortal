<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\FormatsApiResponse;
use App\Models\Indicator;
use Illuminate\Http\Request;

class IndicatorController extends Controller
{
    use FormatsApiResponse;

    // GET /api/v1/indicators?format=json|csv|xml
    public function index(Request $request)
    {
        $indicators = Indicator::all();
        return $this->formatResponse($request, $indicators, 'indicators');
    }

    // GET /api/v1/indicators/{id}
    public function show(Request $request, $id)
    {
        $indicator = Indicator::findOrFail($id);
        return $this->formatResponse($request, collect([$indicator]), 'indicators');
    }

    // POST /api/v1/indicators
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code'     => 'required|unique:indicators|max:50',
            'name_en'  => 'required|max:255',
            'unit'     => 'nullable|max:50',
            'category' => 'nullable|max:100',
        ]);

        $indicator = Indicator::create($validated);
        return response()->json($indicator, 201);
    }

    // PUT /api/v1/indicators/{id}
    public function update(Request $request, $id)
    {
        $indicator = Indicator::findOrFail($id);

        $validated = $request->validate([
            'name_en'  => 'sometimes|max:255',
            'unit'     => 'nullable|max:50',
            'category' => 'nullable|max:100',
        ]);

        $indicator->update($validated);
        return response()->json($indicator);
    }

    // DELETE /api/v1/indicators/{id}
    public function destroy($id)
    {
        Indicator::findOrFail($id)->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }
}