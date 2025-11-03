<?php

namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use Illuminate\Support\Facades\Log;

class PHPMailerService
{
    /**
     * Configurações SMTP padrão do sistema
     */
    public static function getDefaultSmtpConfig(): array
    {
        return [
            'host' => env('SMTP_MODULE_HOST', 'mail.jamees.com'),
            'port' => (int) env('SMTP_MODULE_PORT', 465),
            'encryption' => env('SMTP_MODULE_ENCRYPTION', 'ssl'),
            'username' => env('SMTP_MODULE_USERNAME', 'contato@jamees.com'),
            'password' => env('SMTP_MODULE_PASSWORD', 'T9@genor104'),
            'timeout' => (int) env('SMTP_MODULE_TIMEOUT', 60),
            'from_address' => env('SMTP_MODULE_FROM_ADDRESS', 'contato@jamees.com'),
            'from_name' => env('SMTP_MODULE_FROM_NAME', 'Sistema Jamees'),
        ];
    }

    /**
     * Enviar email usando PHPMailer
     */
    public static function send(string $to, string $subject, string $body, bool $isHtml = true, array $attachments = []): array
    {
        $config = self::getDefaultSmtpConfig();

        try {
            $mail = new PHPMailer(true);

            // Configurações do servidor SMTP
            $mail->isSMTP();
            $mail->Host = $config['host'];
            $mail->SMTPAuth = true;
            $mail->Username = $config['username'];
            $mail->Password = $config['password'];
            $mail->SMTPSecure = $config['encryption'];
            $mail->Port = $config['port'];
            $mail->Timeout = $config['timeout'];

            // Configurações de debug (desabilitado para produção)
            $mail->SMTPDebug = 0;
            $mail->Debugoutput = 'error_log';

            // Configurações de codificação
            $mail->CharSet = 'UTF-8';
            $mail->Encoding = 'base64';

            // Configurações do remetente
            $mail->setFrom($config['from_address'], $config['from_name']);

            // Configurações do destinatário
            $mail->addAddress($to);

            // Configurações do conteúdo
            $mail->isHTML($isHtml);
            $mail->Subject = $subject;
            $mail->Body = $body;

            // Adicionar anexos se fornecidos
            foreach ($attachments as $attachment) {
                if (isset($attachment['path']) && isset($attachment['name'])) {
                    $mail->addAttachment($attachment['path'], $attachment['name']);
                }
            }

            // Enviar email
            $result = $mail->send();

            if ($result) {
                Log::info('Email enviado com sucesso via PHPMailer', [
                    'to' => $to,
                    'subject' => $subject,
                    'from' => $config['from_address']
                ]);

                return [
                    'success' => true,
                    'message' => 'Email enviado com sucesso',
                    'to' => $to,
                    'subject' => $subject,
                    'from' => $config['from_address']
                ];
            } else {
                throw new Exception('Falha ao enviar email - resultado falso');
            }

        } catch (Exception $e) {
            Log::error('Erro ao enviar email via PHPMailer', [
                'to' => $to,
                'subject' => $subject,
                'error' => $e->getMessage(),
                'error_code' => $e->getCode()
            ]);

            return [
                'success' => false,
                'message' => 'Erro ao enviar email: ' . $e->getMessage(),
                'error' => $e->getMessage(),
                'to' => $to,
                'subject' => $subject
            ];
        }
    }

    /**
     * Enviar email de teste com template HTML
     */
    public static function sendTestEmail(string $to, string $customMessage = null): array
    {
        $subject = 'Teste de Email - Sistema Jamees';
        $body = self::getTestEmailTemplate($customMessage);

        return self::send($to, $subject, $body, true);
    }

    /**
     * Template HTML para email de teste usando o template responsivo
     */
    public static function getTestEmailTemplate(string $message = null): string
    {
        $customMessage = $message ?: 'ricardo teste';

        // Dados para o template de boas-vindas
        $emailData = [
            'userName' => 'Usuário de Teste',
            'userEmail' => 'teste@exemplo.com',
            'companyName' => 'Sistema JAMEES',
            'createdAt' => now()->format('d/m/Y H:i'),
            'loginUrl' => url('/login'),
            'customMessage' => $customMessage
        ];

        // Renderizar template de boas-vindas
        return view('emails.templates.welcome', $emailData)->render();
    }

    /**
     * Testar conexão SMTP
     */
    public static function testSmtpConnection(array $config = null): array
    {
        $config = $config ?: self::getDefaultSmtpConfig();

        try {
            $mail = new PHPMailer(true);

            // Configurações do servidor SMTP
            $mail->isSMTP();
            $mail->Host = $config['host'];
            $mail->SMTPAuth = true;
            $mail->Username = $config['username'];
            $mail->Password = $config['password'];
            $mail->SMTPSecure = $config['encryption'];
            $mail->Port = $config['port'];
            $mail->Timeout = $config['timeout'];

            // Configurações de debug
            $mail->SMTPDebug = 0;

            // Configurações de codificação
            $mail->CharSet = 'UTF-8';
            $mail->Encoding = 'base64';

            // Testar conexão
            $result = $mail->smtpConnect();

            if ($result) {
                $mail->smtpClose();

                return [
                    'valid' => true,
                    'message' => 'Conexão SMTP estabelecida com sucesso',
                    'host' => $config['host'],
                    'port' => $config['port'],
                    'encryption' => $config['encryption']
                ];
            } else {
                return [
                    'valid' => false,
                    'message' => 'Falha ao estabelecer conexão SMTP',
                    'host' => $config['host'],
                    'port' => $config['port'],
                    'encryption' => $config['encryption']
                ];
            }

        } catch (Exception $e) {
            return [
                'valid' => false,
                'message' => 'Erro na conexão SMTP: ' . $e->getMessage(),
                'error' => $e->getMessage(),
                'host' => $config['host'],
                'port' => $config['port'],
                'encryption' => $config['encryption']
            ];
        }
    }

    /**
     * Validar configurações SMTP
     */
    public static function validateSmtpConfig(array $config = null): array
    {
        $config = $config ?: self::getDefaultSmtpConfig();

        $errors = [];

        if (empty($config['host'])) {
            $errors[] = 'Host SMTP não pode estar vazio';
        }

        if (empty($config['port']) || !is_numeric($config['port'])) {
            $errors[] = 'Porta SMTP deve ser um número válido';
        }

        if (empty($config['username'])) {
            $errors[] = 'Usuário SMTP não pode estar vazio';
        }

        if (empty($config['password'])) {
            $errors[] = 'Senha SMTP não pode estar vazia';
        }

        if (!in_array($config['encryption'], ['ssl', 'tls'])) {
            $errors[] = 'Criptografia deve ser ssl ou tls';
        }

        if (empty($config['from_address'])) {
            $errors[] = 'Endereço do remetente não pode estar vazio';
        }

        if (!filter_var($config['from_address'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Endereço do remetente deve ser um email válido';
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors,
            'config' => $config
        ];
    }

    /**
     * Enviar email usando template específico
     */
    public static function sendTemplateEmail(string $to, string $template, string $subject, array $data = []): array
    {
        // Renderizar template Blade
        $body = view($template, $data)->render();

        return self::send($to, $subject, $body, true);
    }

    /**
     * Enviar email de boas-vindas
     */
    public static function sendWelcomeEmail(string $to, array $data = []): array
    {
        $subject = 'Bem-vindo ao Sistema JAMEES!';

        // Dados padrão
        $defaultData = [
            'userName' => 'Usuário',
            'userEmail' => $to,
            'companyName' => 'Empresa',
            'createdAt' => now()->format('d/m/Y H:i'),
            'loginUrl' => env('JAMEES_SITE_URL', 'https://jamees.com/'),
            'customMessage' => 'O JAMEES foi desenvolvido para simplificar e otimizar os processos da sua empresa, oferecendo ferramentas poderosas e intuitivas para o seu dia a dia.'
        ];

        // Mesclar dados fornecidos com os padrão
        $emailData = array_merge($defaultData, $data);

        return self::sendTemplateEmail($to, 'emails.templates.welcome', $subject, $emailData);
    }

    /**
     * Enviar email de notificação
     */
    public static function sendNotificationEmail(string $to, string $title, string $message, array $data = []): array
    {
        // Dados padrão
        $defaultData = [
            'title' => $title,
            'message' => $message,
            'status' => $data['status'] ?? 'info',
            'details' => $data['details'] ?? null,
            'actionUrl' => $data['actionUrl'] ?? null,
            'actionText' => $data['actionText'] ?? null,
            'additionalInfo' => $data['additionalInfo'] ?? null
        ];

        // Mesclar dados fornecidos com os padrão
        $emailData = array_merge($defaultData, $data);

        return self::sendTemplateEmail($to, 'emails.templates.notification', $title, $emailData);
    }

    /**
     * Enviar email de recuperação de senha
     */
    public static function sendPasswordResetEmail(string $to, string $resetUrl, array $data = []): array
    {
        $subject = 'Recuperação de Senha - Sistema JAMEES';

        // Dados padrão
        $defaultData = [
            'userName' => 'Usuário',
            'userEmail' => $to,
            'resetUrl' => $resetUrl,
            'expirationTime' => '15 minutos',
            'requestedAt' => now()->format('d/m/Y H:i'),
            'requestIp' => request()->ip() ?? 'Não informado'
        ];

        // Mesclar dados fornecidos com os padrão
        $emailData = array_merge($defaultData, $data);

        return self::sendTemplateEmail($to, 'emails.templates.password-reset', $subject, $emailData);
    }

    /**
     * Enviar email personalizado com configurações customizadas
     */
    public static function sendCustomEmail(string $to, string $subject, string $body, array $customConfig = [], bool $isHtml = true): array
    {
        $config = array_merge(self::getDefaultSmtpConfig(), $customConfig);

        try {
            $mail = new PHPMailer(true);

            // Configurações do servidor SMTP
            $mail->isSMTP();
            $mail->Host = $config['host'];
            $mail->SMTPAuth = true;
            $mail->Username = $config['username'];
            $mail->Password = $config['password'];
            $mail->SMTPSecure = $config['encryption'];
            $mail->Port = $config['port'];
            $mail->Timeout = $config['timeout'];

            // Configurações de debug
            $mail->SMTPDebug = 0;

            // Configurações de codificação
            $mail->CharSet = 'UTF-8';
            $mail->Encoding = 'base64';

            // Configurações do remetente
            $mail->setFrom($config['from_address'], $config['from_name']);

            // Configurações do destinatário
            $mail->addAddress($to);

            // Configurações do conteúdo
            $mail->isHTML($isHtml);
            $mail->Subject = $subject;
            $mail->Body = $body;

            // Enviar email
            $result = $mail->send();

            if ($result) {
                return [
                    'success' => true,
                    'message' => 'Email personalizado enviado com sucesso',
                    'to' => $to,
                    'subject' => $subject,
                    'from' => $config['from_address']
                ];
            } else {
                throw new Exception('Falha ao enviar email personalizado');
            }

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Erro ao enviar email personalizado: ' . $e->getMessage(),
                'error' => $e->getMessage(),
                'to' => $to,
                'subject' => $subject
            ];
        }
    }
}
