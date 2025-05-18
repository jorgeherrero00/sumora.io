<?php

namespace App\Http\Controllers\Auth;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Models\UserIntegration;

class GoogleController extends Controller
{
    public function redirect()
    {
        $query = http_build_query([
            'client_id' => config('services.google.client_id'),
            'redirect_uri' => config('services.google.redirect'),
            'response_type' => 'code',
            'scope' => 'https://www.googleapis.com/auth/spreadsheets https://www.googleapis.com/auth/drive.readonly',
            'access_type' => 'offline',
            'prompt' => 'consent',
        ]);

        return redirect('https://accounts.google.com/o/oauth2/v2/auth?' . $query);
    }

    public function callback(Request $request)
    {
        $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
            'code' => $request->code,
            'client_id' => config('services.google.client_id'),
            'client_secret' => config('services.google.client_secret'),
            'redirect_uri' => config('services.google.redirect'),
            'grant_type' => 'authorization_code',
        ]);

        $data = $response->json();

        // Mostrar para confirmar guardado
        return view('oauth.google.confirm', [
            'access_token' => $data['access_token'],
            'refresh_token' => $data['refresh_token'] ?? null,
            'expires_in' => $data['expires_in'],
        ]);
    }

    public function store(Request $request)
{
    UserIntegration::updateOrCreate([
        'user_id' => Auth::id(),
        'tipo' => 'google_sheets',
    ], [
        'token' => $request->access_token,
        'config' => json_encode([
            'refresh_token' => $request->refresh_token,
        ]),
    ]);

    return redirect()->route('dashboard')->with('success', 'Integración guardada correctamente.');
}

public function listSheets()
{
    $integration = Auth::user()->integrations()->where('tipo', 'google_sheets')->first();

    if (! $integration) {
        return redirect()->route('dashboard')->with('error', 'No se encontró integración con Google.');
    }

    $accessToken = $integration->token;

    $response = Http::withToken($accessToken)->get('https://www.googleapis.com/drive/v3/files', [
        'q' => "mimeType='application/vnd.google-apps.spreadsheet'",
        'fields' => 'files(id, name)',
    ]);


    $files = $response->json()['files'] ?? [];

    return view('oauth.google.select_sheet', compact('files'));
}

public function saveSheet(Request $request)
{
    $integration = Auth::user()->integrations()->where('tipo', 'google_sheets')->first();

    if (! $integration) {
        return redirect()->route('dashboard')->with('error', 'No se encontró integración con Google.');
    }

    $config = json_decode($integration->config, true) ?? [];
    $config['spreadsheet_id'] = $request->spreadsheet_id;

    $integration->update([
        'config' => json_encode($config),
    ]);

    return redirect()->route('dashboard')->with('success', 'Google Sheet guardada correctamente.');
}



}

