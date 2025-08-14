<?php

namespace App\Http\Controllers;

use App\Services\NotionService;
use App\Services\SlackService;
use App\Services\GoogleSheetsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TestIntegrationController extends Controller
{
    public function testNotion(Request $request)
{
    $integration = auth()->user()->integrations()
        ->where('tipo', 'notion')
        ->first();

    if (!$integration) {
        return response()->json([
            'success' => false,
            'message' => 'No tienes configurada la integración con Notion'
        ]);
    }

    try {
        $notion = new NotionService($integration->token);
        
        // Primero listar las bases de datos
        $databases = $notion->listarBaseDatos();
        
        if (!$databases['success']) {
            return response()->json([
                'success' => false,
                'message' => 'Error conectando con Notion: ' . $databases['error']
            ]);
        }

        // Obtener configuración del database_id
        $config = json_decode($integration->config ?? '{}', true);
        $databaseId = $config['database_id'] ?? null;

        if (!$databaseId) {
            return response()->json([
                'success' => false,
                'message' => 'No se ha configurado database_id. Bases de datos disponibles:',
                'databases' => $databases['databases'],
                'suggestion' => 'Copia uno de los IDs y configúralo en la integración'
            ]);
        }

        // Obtener esquema de la base de datos configurada
        $schema = $notion->obtenerEsquemaDatabase($databaseId);
        
        if (!$schema['success']) {
            return response()->json([
                'success' => false,
                'message' => 'Error obteniendo esquema de la base de datos: ' . $schema['error'],
                'database_id' => $databaseId,
                'databases_disponibles' => $databases['databases']
            ]);
        }

        // Intentar enviar una prueba
        $resultado = $notion->enviarResumenReunion(
            $databaseId,
            '🧪 Prueba de integración - ' . now()->format('Y-m-d H:i'),
            'Esta es una prueba automática de la integración con Notion desde Sumora.',
            ['Verificar que la integración funciona correctamente', 'Configurar base de datos si es necesario']
        );

        return response()->json([
            'success' => $resultado['success'],
            'message' => $resultado['success'] 
                ? 'Prueba enviada exitosamente a Notion' 
                : 'Error enviando prueba: ' . $resultado['error'],
            'database_info' => [
                'title' => $schema['title'],
                'schema' => $schema['schema'],
                'propiedades_usadas' => $resultado['propiedades_usadas'] ?? []
            ]
        ]);

    } catch (\Exception $e) {
        Log::error('Error probando Notion', ['error' => $e->getMessage()]);
        
        return response()->json([
            'success' => false,
            'message' => 'Error inesperado: ' . $e->getMessage()
        ]);
    }
}

    // En tu SlackService o TestIntegrationController

public function testSlack(Request $request)
{
    $integration = auth()->user()->integrations()
        ->where('tipo', 'slack')
        ->first();

    if (!$integration) {
        return response()->json([
            'success' => false,
            'message' => 'No tienes configurada la integración con Slack'
        ]);
    }

    try {
        // Obtener configuración del canal
        $config = json_decode($integration->config ?? '{}', true);
        $canal = $config['channel'] ?? '#general';
        
        // Normalizar el formato del canal
        $canal = $this->normalizarCanal($canal);
        
        Log::info('🧪 Probando Slack', [
            'token_length' => strlen($integration->token),
            'canal_original' => $config['channel'] ?? 'No configurado',
            'canal_normalizado' => $canal
        ]);

        // Paso 1: Verificar el token
        $tokenResponse = Http::withToken($integration->token)
            ->post('https://slack.com/api/auth.test');

        if (!$tokenResponse->successful() || !$tokenResponse->json('ok')) {
            return response()->json([
                'success' => false,
                'message' => 'Token de Slack inválido: ' . ($tokenResponse->json('error') ?? 'Error desconocido')
            ]);
        }

        $authInfo = $tokenResponse->json();
        Log::info('✅ Token Slack válido', $authInfo);

        // Paso 2: Listar canales disponibles para debug
        $channelsResponse = Http::withToken($integration->token)
            ->get('https://slack.com/api/conversations.list', [
                'types' => 'public_channel,private_channel',
                'limit' => 100
            ]);

        $availableChannels = [];
        if ($channelsResponse->successful() && $channelsResponse->json('ok')) {
            $channels = $channelsResponse->json('channels', []);
            $availableChannels = collect($channels)->map(function($channel) {
                return [
                    'id' => $channel['id'],
                    'name' => '#' . $channel['name'],
                    'is_member' => $channel['is_member'] ?? false
                ];
            })->toArray();
        }

        // Paso 3: Intentar enviar mensaje de prueba
        $payload = [
            'channel' => $canal,
            'text' => '🧪 Prueba de integración desde Sumora - ' . now()->format('H:i:s'),
            'as_user' => false,
            'username' => 'Sumora',
            'icon_emoji' => ':robot_face:'
        ];

        $response = Http::withToken($integration->token)
            ->post('https://slack.com/api/chat.postMessage', $payload);

        $responseData = $response->json();
        
        if ($response->successful() && $responseData['ok']) {
            return response()->json([
                'success' => true,
                'message' => "✅ Mensaje enviado exitosamente al canal {$canal}",
                'team' => $authInfo['team'] ?? 'Desconocido',
                'user' => $authInfo['user'] ?? 'Desconocido',
                'available_channels' => array_slice($availableChannels, 0, 10) // Mostrar solo los primeros 10
            ]);
        } else {
            $error = $responseData['error'] ?? 'Error desconocido';
            
            return response()->json([
                'success' => false,
                'message' => "❌ Error enviando mensaje: {$error}",
                'suggestions' => $this->getSuggestions($error, $availableChannels),
                'tried_channel' => $canal,
                'available_channels' => array_slice($availableChannels, 0, 10)
            ]);
        }

    } catch (\Exception $e) {
        Log::error('❌ Error probando Slack', [
            'error' => $e->getMessage(),
            'line' => $e->getLine()
        ]);
        
        return response()->json([
            'success' => false,
            'message' => 'Error inesperado: ' . $e->getMessage()
        ]);
    }
}

/**
 * Normalizar el formato del canal
 */
private function normalizarCanal($canal)
{
    // Si ya es un ID de canal (empieza con C), devolverlo tal como está
    if (preg_match('/^[C][A-Z0-9]+$/', $canal)) {
        return $canal;
    }
    
    // Si no tiene #, añadirlo
    if (!str_starts_with($canal, '#')) {
        $canal = '#' . $canal;
    }
    
    // Limpiar espacios y caracteres especiales
    $canal = strtolower(trim($canal));
    
    return $canal;
}

/**
 * Generar sugerencias basadas en el error
 */
private function getSuggestions($error, $availableChannels)
{
    $suggestions = [];
    
    switch ($error) {
        case 'channel_not_found':
            $suggestions[] = "El canal especificado no existe o el bot no tiene acceso";
            $suggestions[] = "Verifica que el canal existe y que el bot está añadido al canal";
            if (!empty($availableChannels)) {
                $suggestions[] = "Canales disponibles: " . collect($availableChannels)->pluck('name')->take(5)->implode(', ');
            }
            break;
            
        case 'not_in_channel':
            $suggestions[] = "El bot no está añadido al canal";
            $suggestions[] = "Invita al bot al canal escribiendo: /invite @nombre_del_bot";
            break;
            
        case 'invalid_auth':
            $suggestions[] = "Token inválido o expirado";
            $suggestions[] = "Regenera el token en tu aplicación de Slack";
            break;
            
        case 'missing_scope':
            $suggestions[] = "El token no tiene los permisos necesarios";
            $suggestions[] = "Asegúrate de que el token tiene el scope 'chat:write'";
            break;
    }
    
    return $suggestions;
}

    public function testGoogleSheets(Request $request)
    {
        $integration = auth()->user()->integrations()
            ->where('tipo', 'google_sheets')
            ->first();

        if (!$integration) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes configurada la integración con Google Sheets'
            ]);
        }

        try {
            $sheets = new GoogleSheetsService($integration->token);
            
            // Obtener configuración
            $config = json_decode($integration->config ?? '{}', true);
            $spreadsheetId = $config['spreadsheet_id'] ?? null;
            $sheetName = $config['sheet_name'] ?? 'Hoja 1';

            Log::info('🧪 Iniciando prueba de Google Sheets', [
                'user_id' => auth()->id(),
                'integration_id' => $integration->id,
                'spreadsheet_id' => $spreadsheetId,
                'sheet_name' => $sheetName,
                'token_exists' => !empty($integration->token),
                'token_length' => strlen($integration->token ?? '')
            ]);

            if (!$spreadsheetId) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se ha configurado el ID del spreadsheet. Ve a configuración de Google OAuth.'
                ]);
            }

            // Paso 1: Verificar el token
            $tokenVerification = $sheets->verificarToken();
            if (!$tokenVerification['success']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token de Google inválido o expirado: ' . $tokenVerification['error'],
                    'suggestion' => 'Vuelve a conectar tu cuenta de Google desde /oauth/google'
                ]);
            }

            Log::info('✅ Token válido', $tokenVerification);

            // Paso 2: Verificar acceso al spreadsheet
            $verification = $sheets->verificarAcceso($spreadsheetId);
            
            if (!$verification['success']) {
                return response()->json([
                    'success' => false,
                    'message' => $verification['error'],
                    'suggestion' => 'Verifica que el spreadsheet ID sea correcto y que hayas dado permisos de Google Sheets',
                    'spreadsheet_id' => $spreadsheetId,
                    'token_scope' => $tokenVerification['scope'] ?? 'No disponible'
                ]);
            }

            Log::info('✅ Acceso al spreadsheet verificado', $verification);

            // Paso 3: Configurar cabeceras si es necesario
            $headers = $sheets->configurarCabeceras($spreadsheetId, $sheetName);
            Log::info('📝 Resultado configuración cabeceras', $headers);

            // Paso 4: Añadir fila de prueba
            $resultado = $sheets->agregarResumenReunion(
                $spreadsheetId,
                $sheetName,
                '🧪 Prueba de integración - ' . now()->format('Y-m-d H:i'),
                'Esta es una prueba automática de la integración con Google Sheets desde Sumora.',
                ['Verificar que la integración funciona correctamente', 'Configurar hoja si es necesario']
            );

            return response()->json([
                'success' => $resultado['success'],
                'message' => $resultado['success'] 
                    ? "✅ Prueba añadida exitosamente a la hoja '{$sheetName}'" 
                    : '❌ Error añadiendo prueba: ' . $resultado['error'],
                'spreadsheet_title' => $verification['title'],
                'sheet_name' => $sheetName,
                'token_info' => [
                    'scope' => $tokenVerification['scope'] ?? 'No disponible',
                    'expires_in' => $tokenVerification['expires_in'] ?? 'No disponible'
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('❌ Error probando Google Sheets', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error inesperado: ' . $e->getMessage(),
                'suggestion' => 'Revisa los logs para más detalles'
            ]);
        }
    }

    public function testAll(Request $request)
    {
        $results = [];
        $user = auth()->user();

        // Probar cada integración configurada
        foreach (['notion', 'slack', 'google_sheets'] as $tipo) {
            $integration = $user->integrations()->where('tipo', $tipo)->first();
            
            if ($integration) {
                $methodName = 'test' . ucfirst(str_replace('_', '', $tipo));
                
                try {
                    $response = $this->$methodName($request);
                    $results[$tipo] = json_decode($response->getContent(), true);
                } catch (\Exception $e) {
                    $results[$tipo] = [
                        'success' => false,
                        'message' => 'Error: ' . $e->getMessage()
                    ];
                }
            } else {
                $results[$tipo] = [
                    'success' => false,
                    'message' => 'Integración no configurada'
                ];
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Prueba de todas las integraciones completada',
            'results' => $results
        ]);
    }
}