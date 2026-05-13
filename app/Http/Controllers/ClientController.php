<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClientRequest;
use App\Models\Client;
use App\Services\ActivityLogger;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::query()
            ->when(request('search'), function ($query, string $search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('clients.index', compact('clients'));
    }

    public function create()
    {
        return view('clients.create');
    }

    public function store(ClientRequest $request, ActivityLogger $activityLogger)
    {
        $client = Client::create($request->validated());
        $activityLogger->log('client.created', "Client {$client->name} was created.", $client);

        return redirect()->route('clients.index')->with('success', 'Client created successfully.');
    }

    public function show(Client $client)
    {
        $client->load(['subscriptions', 'notifications']);

        return view('clients.show', compact('client'));
    }

    public function edit(Client $client)
    {
        return view('clients.edit', compact('client'));
    }

    public function update(ClientRequest $request, Client $client, ActivityLogger $activityLogger)
    {
        $client->update($request->validated());
        $activityLogger->log('client.updated', "Client {$client->name} was updated.", $client);

        return redirect()->route('clients.index')->with('success', 'Client updated successfully.');
    }

    public function destroy(Client $client, ActivityLogger $activityLogger)
    {
        $name = $client->name;
        $client->delete();
        $activityLogger->log('client.deleted', "Client {$name} was deleted.");

        return redirect()->route('clients.index')->with('success', 'Client deleted successfully.');
    }
}
