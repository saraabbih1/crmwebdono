@extends('layouts.app')

@section('title', 'Edit Client')
@section('page-title', 'Edit client')
@section('page-subtitle', $client->name)

@section('content')
    <div class="card content-card">
        <div class="card-header bg-transparent border-0 pt-4 px-4">
            <div class="fw-semibold">Client profile</div>
            <div class="small text-secondary">Keep contact data clean so reminders reach the right inbox.</div>
        </div>
        <div class="card-body">
            <form action="{{ route('clients.update', $client) }}" method="POST" class="row g-3">
                @csrf
                @method('PUT')
                @include('clients.form')
                <div class="col-12 d-flex gap-2 pt-2">
                    <button class="btn btn-primary">Update client</button>
                    <a href="{{ route('clients.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
