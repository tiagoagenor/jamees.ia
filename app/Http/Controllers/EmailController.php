<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PHPMailerService;
use Illuminate\Support\Facades\Validator;

class EmailController extends Controller
{
    /**
     * Exibe a tela de teste de emails
     */
    public function test()
    {
        return view('emails.test');
    }

    /**
     * Envia email de teste
     */
    public function sendTest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'to' => 'required|email',
            'message' => 'nullable|string|max:1000',
            'subject' => 'nullable|string|max:255',
            'use_custom_smtp' => 'boolean',
            'smtp_host' => 'required_if:use_custom_smtp,true|string',
            'smtp_port' => 'required_if:use_custom_smtp,true|integer|min:1|max:65535',
            'smtp_username' => 'required_if:use_custom_smtp,true|email',
            'smtp_password' => 'required_if:use_custom_smtp,true|string',
            'smtp_encryption' => 'nullable|in:tls,ssl,none',
        ], [
            'to.required' => 'O email de destino é obrigatório',
            'to.email' => 'O email de destino deve ser válido',
            'message.max' => 'A mensagem não pode ter mais de 1000 caracteres',
            'subject.max' => 'O assunto não pode ter mais de 255 caracteres',
            'smtp_host.required_if' => 'O host SMTP é obrigatório quando usar configurações personalizadas',
            'smtp_port.required_if' => 'A porta SMTP é obrigatória quando usar configurações personalizadas',
            'smtp_username.required_if' => 'O usuário SMTP é obrigatório quando usar configurações personalizadas',
            'smtp_password.required_if' => 'A senha SMTP é obrigatória quando usar configurações personalizadas',
            'smtp_encryption.in' => 'A criptografia deve ser tls, ssl ou none',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $to = $request->input('to');
        $message = $request->input('message');
        $subject = $request->input('subject', 'Teste de Email - Sistema Jamees');

        // Configurações SMTP
        $smtpConfig = null;
        if ($request->boolean('use_custom_smtp')) {
            $smtpConfig = [
                'host' => $request->input('smtp_host'),
                'port' => (int) $request->input('smtp_port'),
                'username' => $request->input('smtp_username'),
                'password' => $request->input('smtp_password'),
                'encryption' => $request->input('smtp_encryption', 'tls'),
            ];
        }

        // Enviar email
        if ($smtpConfig) {
            $result = PHPMailerService::sendCustomEmail($to, $subject, PHPMailerService::getTestEmailTemplate($message), $smtpConfig);
        } else {
            $result = PHPMailerService::sendTestEmail($to, $message);
        }

        if ($result['success']) {
            return redirect()->back()->with('success', $result['message']);
        } else {
            return redirect()->back()
                ->with('error', $result['message'])
                ->withInput();
        }
    }

    /**
     * Testa conexão SMTP
     */
    public function testConnection(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'smtp_host' => 'required|string',
            'smtp_port' => 'required|integer|min:1|max:65535',
            'smtp_username' => 'required|email',
            'smtp_password' => 'required|string',
            'smtp_encryption' => 'nullable|in:tls,ssl,none',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dados inválidos',
                'errors' => $validator->errors()
            ], 422);
        }

        $smtpConfig = [
            'host' => $request->input('smtp_host'),
            'port' => (int) $request->input('smtp_port'),
            'username' => $request->input('smtp_username'),
            'password' => $request->input('smtp_password'),
            'encryption' => $request->input('smtp_encryption', 'tls'),
        ];

        $result = PHPMailerService::testSmtpConnection($smtpConfig);

        return response()->json($result);
    }

    /**
     * Envia email personalizado
     */
    public function sendCustom(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'to' => 'required|email',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
            'is_html' => 'boolean',
            'from_email' => 'nullable|email',
            'from_name' => 'nullable|string|max:255',
            'use_custom_smtp' => 'boolean',
            'smtp_host' => 'required_if:use_custom_smtp,true|string',
            'smtp_port' => 'required_if:use_custom_smtp,true|integer|min:1|max:65535',
            'smtp_username' => 'required_if:use_custom_smtp,true|email',
            'smtp_password' => 'required_if:use_custom_smtp,true|string',
            'smtp_encryption' => 'nullable|in:tls,ssl,none',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $to = $request->input('to');
        $subject = $request->input('subject');
        $body = $request->input('body');
        $isHtml = $request->boolean('is_html', true);

        $from = null;
        if ($request->filled('from_email')) {
            $from = [
                'email' => $request->input('from_email'),
                'name' => $request->input('from_name', 'Sistema Jamees')
            ];
        }

        // Configurações SMTP
        $smtpConfig = null;
        if ($request->boolean('use_custom_smtp')) {
            $smtpConfig = [
                'host' => $request->input('smtp_host'),
                'port' => (int) $request->input('smtp_port'),
                'username' => $request->input('smtp_username'),
                'password' => $request->input('smtp_password'),
                'encryption' => $request->input('smtp_encryption', 'tls'),
            ];
        }

        if ($smtpConfig) {
            $result = PHPMailerService::sendCustomEmail($to, $subject, $body, $smtpConfig, $isHtml);
        } else {
            $result = PHPMailerService::send($to, $subject, $body, $isHtml);
        }

        if ($result['success']) {
            return redirect()->back()->with('success', $result['message']);
        } else {
            return redirect()->back()
                ->with('error', $result['message'])
                ->withInput();
        }
    }

    }
}
