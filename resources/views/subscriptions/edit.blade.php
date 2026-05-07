<form action="{{ route('subscriptions.update', $subscription) }}" method="POST">
    @csrf
    @method('PUT')

    @include('subscriptions.form')

    <button>Modifier</button>
</form>
