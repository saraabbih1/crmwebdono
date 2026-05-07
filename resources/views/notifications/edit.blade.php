<form action="{{ route('notifications.update', $notification) }}" method="POST">
    @csrf
    @method('PUT')

    @include('notifications.form')

    <button>Modifier</button>
</form>
