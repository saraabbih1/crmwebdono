<form action="{{ route('clients.store') }}" method="POST">

    @csrf

    <input type="text" name="name" placeholder="Nom">

    <input type="text" name="phone" placeholder="Téléphone">

    <input type="email" name="email" placeholder="Email">

    <button>
        Ajouter
    </button>

</form>