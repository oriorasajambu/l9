<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Api\Logger;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public function index()
    {
        $logs = Logger::latest()->get();
        return ['data' => $logs];
    }

    public function find(Request $request)
    {
        $logs = Logger::where('trace_id', $request->json('trace_id'))->get();
        if (count($logs) == 0) return ['data' => 'trace id not found'];
        return ['data' => $logs];
    }
}
