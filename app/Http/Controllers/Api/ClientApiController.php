<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClientRequest;
use App\Http\Resources\ClientResource;
use App\Models\Client;
use App\Services\ActivityLogger;

class ClientApiController extends Controller
{
    public function index()
    {
        return ClientResource::collection(Client::latest()->paginate(15));
    }

    public function store(ClientRequest $request, ActivityLogger $activityLogger): ClientResource
    {
        $client = Client::create($request->validated());
        $activityLogger->log('client.created', "Client {$client->name} was created via API.", $client);

        return new ClientResource($client);
    }

    public function show(Client $client): ClientResource
    {
        return new ClientResource($client);
    }

    public function update(ClientRequest $request, Client $client, ActivityLogger $activityLogger): ClientResource
    {
        $client->update($request->validated());
        $activityLogger->log('client.updated', "Client {$client->name} was updated via API.", $client);

        return new ClientResource($client);
    }

    public function destroy(Client $client, ActivityLogger $activityLogger)
    {
        $name = $client->name;
        $client->delete();
        $activityLogger->log('client.deleted', "Client {$name} was deleted via API.");

        return response()->json(status: 204);
    }
}
