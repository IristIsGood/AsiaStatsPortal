<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\DataPoint;
use Illuminate\Http\Request;

class DataValidationController extends Controller
{
    // GET /api/v1/validations/pending
    public function pending()
{
    $pending = DataPoint::with(['indicator', 'region'])
        ->where('status', 'pending')
        ->get();

    return response()->json($pending);
}

    // POST /api/v1/validations/{id}/approve
    public function approve(Request $request, $id)
    {
        $dataPoint = DataPoint::findOrFail($id);
        $dataPoint->update(['status' => 'validated']);

        // 记录审计日志
        AuditLog::create([
            'user_id'    => $request->user()->id,
            'action'     => 'approved',
            'table_name' => 'data_points',
            'record_id'  => $dataPoint->id,
            'notes'      => 'Data point approved by ' . $request->user()->name,
        ]);

        return response()->json([
            'message'    => 'Data point approved',
            'data_point' => $dataPoint,
        ]);
    }

    // POST /api/v1/validations/{id}/reject
    public function reject(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|max:500',
        ]);

        $dataPoint = DataPoint::findOrFail($id);
        $dataPoint->update(['status' => 'rejected']);

        AuditLog::create([
            'user_id'    => $request->user()->id,
            'action'     => 'rejected',
            'table_name' => 'data_points',
            'record_id'  => $dataPoint->id,
            'notes'      => $request->reason,
        ]);

        return response()->json([
            'message'    => 'Data point rejected',
            'data_point' => $dataPoint,
        ]);
    }
}