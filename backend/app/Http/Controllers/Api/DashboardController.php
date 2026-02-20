<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\ClientResource;

class DashboardController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $stats = [
            'users_count' => User::count(),
            'clients_count' => Client::count(),
            'active_clients_count' => Client::where('status', 'active')->count(),
            'inactive_clients_count' => Client::where('status', 'inactive')->count(),
            'recent_clients' => ClientResource::collection(
                Client::latest()->limit(5)->get()
            ),
        ];

        return response()->json($stats);
    }
}
