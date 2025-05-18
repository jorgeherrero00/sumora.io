<h2>Selecciona una hoja de cálculo de Google Sheets</h2>

<form action="{{ route('oauth.google.sheets.save') }}" method="POST">
    @csrf
    <select name="spreadsheet_id" required>
        @foreach($files as $file)
            <option value="{{ $file['id'] }}">{{ $file['name'] }} ({{ $file['id'] }})</option>
        @endforeach
    </select>
    <br><br>
    <button type="submit">Guardar selección</button>
</form>
