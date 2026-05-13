@extends('layouts.app')

@section('title', 'Add Client')
@section('page-title', 'Add client')
@section('page-subtitle', 'Create a new client record')

@section('content')
    <div class="card content-card">
        <div class="card-body">
            <form action="{{ route('clients.store') }}" method="POST" class="row g-3">
                @csrf
                @include('clients.form', ['client' => null])
                <div class="col-12">
                    <button class="btn btn-primary">Save client</button>
                    <a href="{{ route('clients.index') }}" class="btn btn-link">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
