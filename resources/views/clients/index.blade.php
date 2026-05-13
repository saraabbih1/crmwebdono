@extends('layouts.app')

@section('title', 'Clients')
@section('page-title', 'Clients')
@section('page-subtitle', 'Search and manage client records')
@section('page-actions')
    <a href="{{ route('clients.create') }}" class="btn btn-primary">Add client</a>
@endsection

@section('content')
    <div class="card content-card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-9 col-xl-10">
                    <label class="form-label">Search</label>
                    <input type="search" name="search" value="{{ request('search') }}" class="form-control" placeholder="Name or email">
                </div>
                <div class="col-md-3 col-xl-2 d-grid">
                    <button class="btn btn-outline-primary">Filter</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card content-card">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead><tr><th>Name</th><th>Phone</th><th>Email</th><th class="text-end">Actions</th></tr></thead>
                <tbody>
                    @forelse($clients as $client)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar">{{ strtoupper(substr($client->name, 0, 1)) }}</div>
                                    <div>
                                        <div class="fw-semibold">{{ $client->name }}</div>
                                        <div class="small text-secondary">Client #{{ $client->id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $client->phone }}</td>
                            <td>{{ $client->email ?? '-' }}</td>
                            <td class="text-end">
                                <div class="action-group">
                                    <a href="{{ route('clients.show', $client) }}" class="btn btn-sm btn-outline-secondary">View</a>
                                    <a href="{{ route('clients.edit', $client) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                    <form action="{{ route('clients.destroy', $client) }}" method="POST" onsubmit="return confirm('Delete this client?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4"><div class="empty-state">No clients found.</div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-transparent">{{ $clients->links() }}</div>
    </div>
@endsection
