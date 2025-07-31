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
            
            // Primero intentamos listar las bases de datos
            $databases = $notion->listarBaseDatos();
            
            if (!$databases['success']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error conectando con Notion: ' . $databases['error']
                ]);
            }

            // Enviar un mensaje de prueba si hay database_id configurado
            $config = json_decode($integration->config ?? '{}', true);
            $databaseId = $config['database_id'] ?? null;
            if ($databaseId) {
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
                    'databases_found' => count($databases['databases'])
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Conexión con Notion exitosa. Se encontraron ' . count($databases['databases']) . ' bases de datos.',
                'databases' => array_slice($databases['databases'], 0, 5) // Mostrar solo las primeras 5
            ]);

        } catch (\Exception $e) {
            Log::error('Error probando Notion', ['error' => $e->getMessage()]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error inesperado: ' . $e->getMessage()
            ]);
        }
    }

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
            $slack = new SlackService($integration->token);
            
            // Verificar token
            $verification = $slack->verificarToken();
            
            if (!$verification['success']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token de Slack inválido: ' . $verification['error']
                ]);
            }

            // Obtener configuración del canal
            $config = json_decode($integration->config ?? '{}', true);
            $canal = $config['channel'] ?? '#general';

            // Enviar mensaje de prueba
            $resultado = $slack->enviarResumenReunion(
                $canal,
                '🧪 Prueba de integración - ' . now()->format('Y-m-d H:i'),
                'Esta es una prueba automática de la integración con Slack desde Sumora.',
                ['Verificar que la integración funciona correctamente', 'Configurar canal si es necesario']
            );

            return response()->json([
                'success' => $resultado['success'],
                'message' => $resultado['success'] 
                    ? "Prueba enviada exitosamente al canal {$canal}" 
                    : 'Error enviando prueba: ' . $resultado['error'],
                'team' => $verification['team'] ?? null,
                'channel' => $canal
            ]);

        } catch (\Exception $e) {
            Log::error('Error probando Slack', ['error' => $e->getMessage()]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error inesperado: ' . $e->getMessage()
            ]);
        }
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