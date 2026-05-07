<form action="{{ route('subscriptions.store') }}" method="POST">
    @csrf

    @include('subscriptions.form', ['subscription' => null])

    <button>Ajouter</button>
</form>
