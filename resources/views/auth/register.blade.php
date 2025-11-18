
<h1>Registrace</h1>

<form method="POST" action="/register">
    @csrf

    <label>Jméno</label>
    <input type="text" name="name" value="{{ old('name') }}" required>
    <br>
    <label>Příjmení</label>
    <input type="text" name="surname" required value="{{ old('surname') }}">
    <br>


    <label>Email</label>
    <input type="email" name="email" value="{{ old('email') }}" required>
    <br>

    <label>Heslo</label>
    <input type="password" name="password" required>
    <br>

    <label>Potvrzení hesla</label>
    <input type="password" name="password_confirmation" required>
    <br>

    @foreach ($errors->all() as $error)
        <p style="color:red">{{ $error }}</p>
    @endforeach

    <button type="submit">Registrovat</button>
</form>

<a href="/login">Přihlásit</a>
