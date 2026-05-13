@extends('layouts.app')

@section('title', 'Add Client')
@section('page-title', 'Add client')
@section('page-subtitle', 'Create a new client record')

@section('content')
    <div class="card content-card">
        <div class="card-header bg-transparent border-0 pt-4 px-4">
            <div class="fw-semibold">Client profile</div>
            <div class="small text-secondary">Add contact details used by subscriptions and reminders.</div>
        </div>
        <div class="card-body">
            <form action="{{ route('clients.store') }}" method="POST" class="row g-3">
                @csrf
                @include('clients.form', ['client' => null])
                <div class="col-12 d-flex gap-2 pt-2">
                    <button class="btn btn-primary">Save client</button>
                    <a href="{{ route('clients.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
