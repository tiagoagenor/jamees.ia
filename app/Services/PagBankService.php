<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PagBankService
{
    private string $token;
    private string $baseUrl;
    private bool $isSandbox;

    public function __construct()
    {
        $this->token = config('services.pagbank.token');
        $this->isSandbox = config('services.pagbank.sandbox', true);
        $this->baseUrl = $this->isSandbox
            ? 'https://sandbox.api.pagseguro.com'
            : 'https://api.pagseguro.com';
    }

    /**
     * Limpa CPF/CNPJ removendo caracteres especiais
     */
    private function limparCPF(string $cpf): string
    {
        return preg_replace('/[^0-9]/', '', $cpf);
    }

    /**
     * Limpa telefone removendo caracteres especiais
     */
    private function limparTelefone(string $telefone): array
    {
        $telefoneLimpo = preg_replace('/[^0-9]/', '', $telefone);

        // Se tiver 11 dígitos, assume DDD de 2 dígitos
        if (strlen($telefoneLimpo) == 11) {
            return [
                'area' => substr($telefoneLimpo, 0, 2),
                'number' => substr($telefoneLimpo, 2),
            ];
        }

        // Se tiver 10 dígitos, assume DDD de 2 dígitos
        if (strlen($telefoneLimpo) == 10) {
            return [
                'area' => substr($telefoneLimpo, 0, 2),
                'number' => substr($telefoneLimpo, 2),
            ];
        }

        // Default: assume que os 2 primeiros são DDD
        return [
            'area' => substr($telefoneLimpo, 0, 2),
            'number' => substr($telefoneLimpo, 2),
        ];
    }

    /**
     * Cria um pagamento PIX
     *
     * @param array $dadosCliente ['nome', 'email', 'cpf', 'telefone']
     * @param array $dadosProduto ['id', 'nome', 'quantidade', 'valor']
     * @param string|null $referenceId ID de referência do pedido (opcional)
     * @return array
     */
    public function criarPagamentoPix(array $dadosCliente, array $dadosProduto, ?string $referenceId = null): array
    {
        try {
            $telefone = $this->limparTelefone($dadosCliente['telefone']);

            $data = [
                'reference_id' => $referenceId ?? uniqid('pedido_'),
                'customer' => [
                    'name' => $dadosCliente['nome'],
                    'email' => $dadosCliente['email'],
                    'tax_id' => $this->limparCPF($dadosCliente['cpf']),
                    'phones' => [
                        [
                            'country' => '55',
                            'area' => $telefone['area'],
                            'number' => $telefone['number'],
                            'type' => 'MOBILE'
                        ]
                    ]
                ],
                'items' => [
                    [
                        'reference_id' => $dadosProduto['id'],
                        'name' => $dadosProduto['nome'],
                        'quantity' => $dadosProduto['quantidade'],
                        'unit_amount' => (int)($dadosProduto['valor'] * 100) // valor em centavos
                    ]
                ],
                'qr_codes' => [
                    [
                        'amount' => [
                            'value' => (int)($dadosProduto['valor'] * 100 * $dadosProduto['quantidade'])
                        ]
                    ]
                ],
                'notification_urls' => [
                    url(route('pagbank.webhook')) // URL absoluta para receber notificações
                ]
            ];

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->token
            ])->post($this->baseUrl . '/orders', $data);

            if ($response->successful()) {
                $responseData = $response->json();

                // Verificar se a resposta contém os dados necessários
                if (!isset($responseData['qr_codes']) || empty($responseData['qr_codes'])) {
                    Log::error('Resposta do PagBank não contém QR Code', [
                        'response' => $responseData
                    ]);

                    return [
                        'success' => false,
                        'error' => 'Resposta da API não contém dados do QR Code PIX'
                    ];
                }

                return [
                    'success' => true,
                    'data' => $responseData
                ];
            }

            $errorBody = $response->body();
            $errorJson = $response->json();

            Log::error('Erro ao criar pagamento PIX no PagBank', [
                'status' => $response->status(),
                'error_body' => $errorBody,
                'error_json' => $errorJson
            ]);

            return [
                'success' => false,
                'error' => $errorJson['error_messages'][0]['description'] ?? $errorBody ?? 'Erro desconhecido ao processar pagamento',
                'status' => $response->status()
            ];

        } catch (\Exception $e) {
            Log::error('Erro ao criar pagamento PIX no PagBank', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Consulta o status de um pedido
     *
     * @param string $orderId ID do pedido
     * @return array
     */
    public function consultarPedido(string $orderId): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->token
            ])->get($this->baseUrl . '/orders/' . $orderId);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json()
                ];
            }

            return [
                'success' => false,
                'error' => $response->body(),
                'status' => $response->status()
            ];

        } catch (\Exception $e) {
            Log::error('Erro ao consultar pedido no PagBank', [
                'error' => $e->getMessage(),
                'order_id' => $orderId
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
}

