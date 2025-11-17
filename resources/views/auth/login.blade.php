
<h1>Login</h1>

<form method="POST" action="/login">
    @csrf

    <label>Email</label>
    <input type="email" name="email" value="{{ old('email') }}" required>
    <br>

    <label>Heslo</label>
    <input type="password" name="password" required>
    <br>

    @error('login')
        <p style="color:red">{{ $message }}</p>
    @enderror

    <button type="submit">Přihlásit</button>
</form>

<a href="/register">Registrovat se</a>
