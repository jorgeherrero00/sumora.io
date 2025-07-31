<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserIntegration;

class IntegrationController extends Controller
{
    public function index()
    {
        $integrations = auth()->user()->integrations;
        return view('integrations.api-keys', compact('integrations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'notion_api' => 'nullable|string|max:255',
            'google_token' => 'nullable|string|max:500',
            'slack_token' => 'nullable|string|max:255',
        ]);

        $user = auth()->user();

        // Notion API
        if ($request->filled('notion_api')) {
            UserIntegration::updateOrCreate([
                'user_id' => $user->id,
                'tipo' => 'notion',
            ], [
                'token' => $request->notion_api,
                'config' => json_encode([
                    'database_id' => null, // Se configurará después
                ])
            ]);
        }

        // Google Sheets
        if ($request->filled('google_token')) {
            UserIntegration::updateOrCreate([
                'user_id' => $user->id,
                'tipo' => 'google_sheets',
            ], [
                'token' => $request->google_token,
                'config' => json_encode([
                    'spreadsheet_id' => null, // Se configurará después
                ])
            ]);
        }

        // Slack
        if ($request->filled('slack_token')) {
            UserIntegration::updateOrCreate([
                'user_id' => $user->id,
                'tipo' => 'slack',
            ], [
                'token' => $request->slack_token,
                'config' => json_encode([
                    'channel' => '#general', // Canal por defecto
                ])
            ]);
        }

        return redirect()->route('integrations.index')->with('success', 'Integraciones guardadas correctamente.');
    }

    public function destroy($integration)
    {
        $integration = UserIntegration::where('user_id', auth()->id())
                                    ->where('id', $integration)
                                    ->firstOrFail();
        
        $integration->delete();
        
        return redirect()->route('integrations.index')->with('success', 'Integración eliminada correctamente.');
    }
}