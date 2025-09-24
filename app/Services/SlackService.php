<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SlackService
{
    private $botToken;
    private $baseUrl = 'https://slack.com/api';

    public function __construct($botToken)
    {
        $this->botToken = $botToken;
    }

    /**
     * Envía un resumen de reunión a un canal de Slack
     */
    public function enviarResumenReunion($canal, $titulo, $resumen, $tareas = [])
    {
        try {
            Log::info('📱 Enviando resumen a Slack', [
                'canal' => $canal,
                'titulo' => $titulo
            ]);

            // Formatear mensaje para Slack
            $mensaje = "*🎯 {$titulo}*\n\n";
            $mensaje .= "*Resumen:*\n{$resumen}\n\n";

            if (!empty($tareas)) {
                $mensaje .= "*📋 Tareas identificadas:*\n";
                foreach ($tareas as $index => $tarea) {
                    $mensaje .= ($index + 1) . ". {$tarea}\n";
                }
            }

            $mensaje .= "\n_Generado automáticamente por Syntal_ ⚡";

            $payload = [
                'channel' => $canal,
                'text' => "Nueva reunión procesada: {$titulo}",
                'blocks' => [
                    [
                        'type' => 'header',
                        'text' => [
                            'type' => 'plain_text',
                            'text' => "🎯 {$titulo}"
                        ]
                    ],
                    [
                        'type' => 'section',
                        'text' => [
                            'type' => 'mrkdwn',
                            'text' => "*Resumen:*\n{$resumen}"
                        ]
                    ]
                ]
            ];

            // Añadir bloque de tareas si existen
            if (!empty($tareas)) {
                $tareasTexto = "*📋 Tareas identificadas:*\n";
                foreach ($tareas as $index => $tarea) {
                    $tareasTexto .= ($index + 1) . ". {$tarea}\n";
                }

                $payload['blocks'][] = [
                    'type' => 'section',
                    'text' => [
                        'type' => 'mrkdwn',
                        'text' => $tareasTexto
                    ]
                ];
            }

            // Añadir footer
            $payload['blocks'][] = [
                'type' => 'context',
                'elements' => [
                    [
                        'type' => 'mrkdwn',
                        'text' => "_Generado automáticamente por Syntal_ ⚡"
                    ]
                ]
            ];

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->botToken,
                'Content-Type' => 'application/json'
            ])->post($this->baseUrl . '/chat.postMessage', $payload);

            if ($response->successful() && $response->json('ok')) {
                Log::info('✅ Resumen enviado a Slack exitosamente', [
                    'message_ts' => $response->json('ts'),
                    'channel' => $response->json('channel')
                ]);
                return [
                    'success' => true,
                    'message_ts' => $response->json('ts')
                ];
            } else {
                Log::error('❌ Error al enviar a Slack', [
                    'error' => $response->json('error'),
                    'body' => $response->body()
                ]);
                return [
                    'success' => false,
                    'error' => $response->json('error') ?? 'Error desconocido'
                ];
            }

        } catch (\Exception $e) {
            Log::error('❌ Excepción al enviar a Slack', [
                'mensaje' => $e->getMessage(),
                'linea' => $e->getLine()
            ]);
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Lista los canales disponibles
     */
    public function listarCanales()
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->botToken
            ])->get($this->baseUrl . '/conversations.list', [
                'types' => 'public_channel,private_channel'
            ]);

            if ($response->successful() && $response->json('ok')) {
                return [
                    'success' => true,
                    'channels' => $response->json('channels')
                ];
            } else {
                return [
                    'success' => false,
                    'error' => $response->json('error') ?? 'Error desconocido'
                ];
            }

        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Verifica si el token es válido
     */
    public function verificarToken()
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->botToken
            ])->get($this->baseUrl . '/auth.test');

            if ($response->successful() && $response->json('ok')) {
                return [
                    'success' => true,
                    'team' => $response->json('team'),
                    'user' => $response->json('user')
                ];
            } else {
                return [
                    'success' => false,
                    'error' => $response->json('error') ?? 'Token inválido'
                ];
            }

        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
}