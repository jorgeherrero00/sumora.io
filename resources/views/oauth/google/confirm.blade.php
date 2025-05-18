<h2>¿Guardar acceso a tu Google Sheets en Sumora?</h2>

<form method="POST" action="{{ route('oauth.google.store') }}">
    @csrf
    <input type="hidden" name="access_token" value="{{ $access_token }}">
    <input type="hidden" name="refresh_token" value="{{ $refresh_token }}">
    <button type="submit">Sí, guardar</button>
</form>

<a href="/">No guardar</a>
