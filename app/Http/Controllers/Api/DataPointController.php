<?php

namespace App\Http\Controllers\Api;
use App\Http\Traits\FormatsApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DataPoint;

class DataPointController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    use FormatsApiResponse;
    
    public function index(Request $request)
{
    $query = DataPoint::query();

    if ($request->indicator_id) {
        $query->where('indicator_id', $request->indicator_id);
    }
    if ($request->region_id) {
        $query->where('region_id', $request->region_id);
    }
    if ($request->year) {
        $query->where('year', $request->year);
    }

    $data = $query->get();
    return $this->formatResponse($request, $data, 'data_points');
}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'indicator_id' => 'required|exists:indicators,id',
            'region_id'    => 'required|exists:regions,id',
            'year'         => 'required|integer',
            'value'        => 'required|numeric',
            'source'       => 'nullable|string',
            'status'       => 'in:pending,validated,rejected',
        ]);

        $dataPoint = DataPoint::create($validated);
        $dataPoint->load(['indicator', 'region']);
        return response()->json($dataPoint, 201);
    }
    

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
