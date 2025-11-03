<?php

namespace App\Services;

/**
 * Exemplos de uso dos templates de email
 *
 * Este arquivo contém exemplos de como usar os templates de email
 * criados para o sistema JAMEES.
 */
class EmailTemplateExamples
{
    /**
     * Exemplo 1: Enviar email de boas-vindas para novo usuário
     */
    public static function exemploBoasVindas()
    {
        $destinatario = 'usuario@exemplo.com';
        $dadosUsuario = [
            'userName' => 'João Silva',
            'userEmail' => 'joao.silva@exemplo.com',
            'companyName' => 'Empresa ABC Ltda',
            'createdAt' => now()->format('d/m/Y H:i'),
            'loginUrl' => url('/login')
        ];

        return PHPMailerService::sendWelcomeEmail($destinatario, $dadosUsuario);
    }

    /**
     * Exemplo 2: Enviar email de boas-vindas com dados mínimos
     */
    public static function exemploBoasVindasSimples()
    {
        $destinatario = 'usuario@exemplo.com';

        // Usa dados padrão
        return PHPMailerService::sendWelcomeEmail($destinatario);
    }

    /**
     * Exemplo 3: Como usar em um Controller
     */
    public static function exemploNoController()
    {
        // Exemplo de uso em um controller de criação de usuário
        /*
        public function store(Request $request)
        {
            // ... código para criar usuário ...

            $usuario = Usuario::create($dados);

            // Enviar email de boas-vindas
            $dadosEmail = [
                'userName' => $usuario->nome,
                'userEmail' => $usuario->email,
                'companyName' => $usuario->empresa->nome_fantasia,
                'createdAt' => $usuario->created_at->format('d/m/Y H:i'),
                'loginUrl' => url('/login')
            ];

            $resultado = PHPMailerService::sendWelcomeEmail($usuario->email, $dadosEmail);

            if ($resultado['success']) {
                return redirect()->back()->with('success', 'Usuário criado e email de boas-vindas enviado!');
            } else {
                return redirect()->back()->with('warning', 'Usuário criado, mas falha ao enviar email: ' . $resultado['message']);
            }
        }
        */
    }

    /**
     * Exemplo 4: Como usar em um Service
     */
    public static function exemploNoService()
    {
        // Exemplo de uso em um service de criação de usuário
        /*
        public function execute(array $dados): array
        {
            // ... código para criar usuário ...

            $usuario = Usuario::create($dados);

            // Enviar email de boas-vindas
            try {
                $dadosEmail = [
                    'userName' => $usuario->nome,
                    'userEmail' => $usuario->email,
                    'companyName' => $usuario->empresa->nome_fantasia,
                    'createdAt' => $usuario->created_at->format('d/m/Y H:i'),
                    'loginUrl' => url('/login')
                ];

                PHPMailerService::sendWelcomeEmail($usuario->email, $dadosEmail);

                Log::info('Email de boas-vindas enviado para usuário', [
                    'usuario_id' => $usuario->id,
                    'email' => $usuario->email
                ]);

            } catch (Exception $e) {
                Log::error('Falha ao enviar email de boas-vindas', [
                    'usuario_id' => $usuario->id,
                    'email' => $usuario->email,
                    'erro' => $e->getMessage()
                ]);
            }

            return [
                'success' => true,
                'usuario' => $usuario
            ];
        }
        */
    }

    /**
     * Exemplo 5: Template personalizado
     */
    public static function exemploTemplatePersonalizado()
    {
        $destinatario = 'usuario@exemplo.com';
        $assunto = 'Bem-vindo ao Sistema JAMEES!';

        // Renderizar template manualmente
        $dados = [
            'userName' => 'Maria Santos',
            'userEmail' => 'maria.santos@exemplo.com',
            'companyName' => 'Empresa XYZ',
            'createdAt' => now()->format('d/m/Y H:i'),
            'loginUrl' => url('/login')
        ];

        $body = view('emails.templates.welcome', $dados)->render();

        return PHPMailerService::send($destinatario, $assunto, $body, true);
    }

    /**
     * Exemplo 6: Envio em lote
     */
    public static function exemploEnvioEmLote()
    {
        $usuarios = [
            ['nome' => 'João Silva', 'email' => 'joao@exemplo.com', 'empresa' => 'Empresa A'],
            ['nome' => 'Maria Santos', 'email' => 'maria@exemplo.com', 'empresa' => 'Empresa B'],
            ['nome' => 'Pedro Costa', 'email' => 'pedro@exemplo.com', 'empresa' => 'Empresa C'],
        ];

        $resultados = [];

        foreach ($usuarios as $usuario) {
            $dadosEmail = [
                'userName' => $usuario['nome'],
                'userEmail' => $usuario['email'],
                'companyName' => $usuario['empresa'],
                'createdAt' => now()->format('d/m/Y H:i'),
                'loginUrl' => url('/login')
            ];

            $resultados[] = PHPMailerService::sendWelcomeEmail($usuario['email'], $dadosEmail);
        }

        return $resultados;
    }
}
