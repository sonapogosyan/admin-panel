<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Http\Resources\ClientResource;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ClientController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request): AnonymousResourceCollection
    {
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

        $clients = $query->latest()->paginate($request->input('per_page', 10));

        return ClientResource::collection($clients);
    }

    public function store(StoreClientRequest $request)
    {
        $this->authorize('create', Client::class);

        $client = Client::create($request->validated());

        if ($request->header('X-Inertia')) {
            return redirect()->back()->with('success', 'Client created successfully.');
        }

        return new ClientResource($client);
    }

    public function show(Client $client): ClientResource
    {
        $this->authorize('view', $client);
        return new ClientResource($client);
    }

    public function update(UpdateClientRequest $request, Client $client)
    {
        $this->authorize('update', $client);

        $client->update($request->validated());

        if ($request->header('X-Inertia')) {
            return redirect()->back()->with('success', 'Client updated successfully.');
        }

        return new ClientResource($client);
    }

    public function destroy(Request $request, Client $client)
    {
        $this->authorize('delete', $client);

        $client->delete();

        if ($request->header('X-Inertia')) {
            return redirect()->back()->with('success', 'Client deleted successfully.');
        }

        return response()->json(null, 204);
    }
}
