<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NotionService
{
    private $apiKey;
    private $baseUrl = 'https://api.notion.com/v1';

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * Envía un resumen de reunión a una página de Notion
     */
    public function enviarResumenReunion($databaseId, $titulo, $resumen, $tareas = [])
    {
        try {
            Log::info('📝 Enviando resumen a Notion', [
                'database_id' => $databaseId,
                'titulo' => $titulo
            ]);

            // Formatear tareas como texto
            $tareasTexto = '';
            if (!empty($tareas)) {
                $tareasTexto = "\n\n**Tareas identificadas:**\n";
                foreach ($tareas as $index => $tarea) {
                    $tareasTexto .= ($index + 1) . ". " . $tarea . "\n";
                }
            }

            $payload = [
                'parent' => [
                    'database_id' => $databaseId
                ],
                'properties' => [
                    'Título' => [
                        'title' => [
                            [
                                'text' => [
                                    'content' => $titulo
                                ]
                            ]
                        ]
                    ],
                    'Fecha' => [
                        'date' => [
                            'start' => now()->toDateString()
                        ]
                    ],
                    'Estado' => [
                        'select' => [
                            'name' => 'Procesada'
                        ]
                    ]
                ],
                'children' => [
                    [
                        'object' => 'block',
                        'type' => 'paragraph',
                        'paragraph' => [
                            'rich_text' => [
                                [
                                    'type' => 'text',
                                    'text' => [
                                        'content' => $resumen . $tareasTexto
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ];

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Notion-Version' => '2022-06-28',
                'Content-Type' => 'application/json'
            ])->post($this->baseUrl . '/pages', $payload);

            if ($response->successful()) {
                Log::info('✅ Resumen enviado a Notion exitosamente', [
                    'page_id' => $response->json('id')
                ]);
                return [
                    'success' => true,
                    'page_id' => $response->json('id')
                ];
            } else {
                Log::error('❌ Error al enviar a Notion', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return [
                    'success' => false,
                    'error' => $response->body()
                ];
            }

        } catch (\Exception $e) {
            Log::error('❌ Excepción al enviar a Notion', [
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
     * Lista las bases de datos disponibles para el usuario
     */
    public function listarBaseDatos()
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Notion-Version' => '2022-06-28'
            ])->post($this->baseUrl . '/search', [
                'filter' => [
                    'value' => 'database',
                    'property' => 'object'
                ]
            ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'databases' => $response->json('results')
                ];
            } else {
                return [
                    'success' => false,
                    'error' => $response->body()
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
     * Crea una base de datos para reuniones si no existe
     */
    public function crearBaseDatosReuniones($pageId = null)
    {
        try {
            $payload = [
                'parent' => [
                    'type' => 'page_id',
                    'page_id' => $pageId ?? $this->obtenerPaginaPrincipal()
                ],
                'title' => [
                    [
                        'type' => 'text',
                        'text' => [
                            'content' => 'Reuniones Sumora'
                        ]
                    ]
                ],
                'properties' => [
                    'Título' => [
                        'title' => []
                    ],
                    'Fecha' => [
                        'date' => []
                    ],
                    'Estado' => [
                        'select' => [
                            'options' => [
                                [
                                    'name' => 'Procesada',
                                    'color' => 'green'
                                ],
                                [
                                    'name' => 'Pendiente',
                                    'color' => 'yellow'
                                ]
                            ]
                        ]
                    ]
                ]
            ];

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Notion-Version' => '2022-06-28',
                'Content-Type' => 'application/json'
            ])->post($this->baseUrl . '/databases', $payload);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'database_id' => $response->json('id')
                ];
            } else {
                return [
                    'success' => false,
                    'error' => $response->body()
                ];
            }

        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    private function obtenerPaginaPrincipal()
    {
        // Implementar lógica para obtener página principal del usuario
        // Por ahora retornamos null para que use la página raíz
        return null;
    }
}