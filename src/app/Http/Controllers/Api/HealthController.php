<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class HealthController extends Controller
{
    // Liveness: is the process up? No dependencies checked.
    public function live()
    {
        return response()->json(['status' => 'ok']);
    }

    // Readiness: can the app serve traffic? Checks the database.
    public function ready()
    {
        try {
            DB::connection()->getPdo();
            DB::select('select 1');

            return response()->json(['status' => 'ready', 'database' => 'up']);
        } catch (\Throwable $e) {
            report($e);

            return response()->json(['status' => 'unavailable', 'database' => 'down'], 503);
        }
    }
}