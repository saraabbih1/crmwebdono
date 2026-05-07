<form action="{{ route('notifications.store') }}" method="POST">
    @csrf

    @include('notifications.form', ['notification' => null])

    <button>Ajouter</button>
</form>
