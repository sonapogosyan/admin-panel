<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Resources\ClientResource;
use App\Http\Resources\OrderResource;
use App\Models\Client;
use App\Models\Order;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/home', function () {
    return redirect()->route('dashboard');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Clients Web View
    Route::get('/clients', function (Request $request) {
        $query = Client::query();
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        return Inertia::render('Clients/Index', [
            'clients' => ClientResource::collection($query->latest()->paginate($request->input('per_page', 10))),
            'filters' => $request->only(['search', 'status']),
        ]);
    })->name('clients.index');

    // Orders Web View
    Route::get('/orders', function (Request $request) {
        $query = Order::with('client');
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhereHas('client', function ($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        return Inertia::render('Orders/Index', [
            'orders' => OrderResource::collection($query->latest()->paginate($request->input('per_page', 10))),
            'filters' => $request->only(['search', 'status']),
            'clients' => ClientResource::collection(Client::all()),
        ]);
    })->name('orders.index');

    // API-like routes for Inertia/Web Session
    Route::prefix('api')->name('api.')->group(function () {
        Route::get('/dashboard', DashboardController::class)->name('dashboard');
        Route::apiResource('clients', ClientController::class);
        Route::apiResource('orders', OrderController::class);
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
