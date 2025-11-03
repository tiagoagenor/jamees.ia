<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Recuperação de Senha - Sistema JAMEES</title>
    <style type="text/css">
        /* Reset styles */
        body, table, td, p, a, li, blockquote {
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }
        table, td {
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }
        img {
            -ms-interpolation-mode: bicubic;
        }

        /* Remove blue links for clients */
        a[x-apple-data-detectors] {
            color: inherit !important;
            text-decoration: none !important;
            font-size: inherit !important;
            font-family: inherit !important;
            font-weight: inherit !important;
            line-height: inherit !important;
        }

        /* Main styles */
        body {
            margin: 0;
            padding: 0;
            min-width: 100%;
            background-color: #f4f4f4;
            font-family: 'Roboto', Arial, sans-serif;
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
        }

        .email-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 30px;
            text-align: center;
            color: #ffffff;
        }

        .logo {
            font-size: 32px;
            font-weight: bold;
            margin: 0 0 10px 0;
            color: #ffffff;
        }

        .logo-subtitle {
            font-size: 14px;
            margin: 0;
            opacity: 0.9;
        }

        .email-content {
            padding: 40px 30px;
            color: #333333;
        }

        .security-badge {
            background: #e8f5e8;
            color: #2d5a2d;
            padding: 10px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
            border: 1px solid #c3e6c3;
        }

        .email-title {
            font-size: 24px;
            font-weight: bold;
            color: #2c3e50;
            margin: 0 0 20px 0;
            text-align: center;
        }

        .email-message {
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 30px;
        }

        .highlight-box {
            background: #f8f9fa;
            border-left: 4px solid #2c3e50;
            padding: 20px;
            margin: 20px 0;
            border-radius: 0 8px 8px 0;
        }

        .highlight-title {
            font-size: 18px;
            font-weight: bold;
            color: #2c3e50;
            margin: 0 0 10px 0;
        }

        .highlight-text {
            font-size: 14px;
            color: #666666;
            margin: 0;
        }

        .email-button {
            display: inline-block;
            padding: 15px 30px;
            background: #2c3e50;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 25px;
            font-weight: bold;
            font-size: 16px;
            text-align: center;
            margin: 20px 0;
            box-shadow: 0 4px 15px rgba(44, 62, 80, 0.3);
        }

        .email-button:hover {
            background: #34495e;
            color: #ffffff !important;
        }

        .security-warning {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
        }

        .security-warning-title {
            font-size: 14px;
            font-weight: bold;
            color: #856404;
            margin: 0 0 10px 0;
        }

        .security-warning-text {
            font-size: 13px;
            color: #856404;
            margin: 0;
            line-height: 1.4;
        }

        .request-info {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }

        .request-info-title {
            font-size: 16px;
            font-weight: bold;
            color: #2c3e50;
            margin: 0 0 15px 0;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .info-label {
            font-weight: bold;
            color: #666666;
        }

        .info-value {
            color: #333333;
        }

        .email-footer {
            background: #2c3e50;
            padding: 30px;
            text-align: center;
            color: #ffffff;
        }

        .email-footer p {
            margin: 0;
            font-size: 14px;
        }

        .email-footer a {
            color: #ffffff;
            text-decoration: underline;
        }

        /* Responsive */
        @media only screen and (max-width: 600px) {
            .email-container {
                width: 100% !important;
            }

            .email-header,
            .email-content,
            .email-footer {
                padding: 20px !important;
            }

            .email-title {
                font-size: 20px !important;
            }

            .email-button {
                display: block !important;
                width: 100% !important;
                text-align: center !important;
            }
        }
    </style>
    <!--[if gte mso 9]>
    <xml>
        <o:OfficeDocumentSettings>
        <o:AllowPNG/>
        <o:PixelsPerInch>96</o:PixelsPerInch>
        </o:OfficeDocumentSettings>
    </xml>
    <![endif]-->
</head>
<body style="margin: 0; padding: 0; min-width: 100%; background-color: #f4f4f4;">
    <center style="width: 100%; background-color: #f4f4f4;">
        <!--[if mso | IE]>
        <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f4f4f4;">
        <tr>
        <td align="center" valign="top">
        <![endif]-->
        <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" style="max-width: 600px; width: 100%; margin: 0 auto; background-color: #ffffff;">
            <tr>
                <td align="center">
                    <table role="presentation" cellspacing="0" cellpadding="0" border="0" class="email-container">
                        <!-- Header -->
                        <tr>
                            <td class="email-header">
                                <h1 class="logo">JAMEES</h1>
                                <p class="logo-subtitle">Sistema de Gestão Empresarial</p>
                            </td>
                        </tr>

                        <!-- Content -->
                        <tr>
                            <td class="email-content">
                                <div class="security-badge">🔐 Solicitação de Recuperação de Senha</div>

                                <h1 class="email-title">Recuperação de Senha</h1>

                                <div class="email-message">
                                    <p>Olá <strong>{{ $userName ?? 'Usuário' }}</strong>,</p>
                                    <p>Recebemos uma solicitação para redefinir a senha da sua conta no Sistema JAMEES.</p>
                                </div>

                                <div class="highlight-box">
                                    <h2 class="highlight-title">🔑 Redefina sua senha</h2>
                                    <p class="highlight-text">Clique no botão abaixo para criar uma nova senha segura para sua conta.</p>
                                </div>

                                <div style="text-align: center;">
                                    <a href="{{ $resetUrl }}" class="email-button">Redefinir Senha</a>
                                </div>

                                <div class="security-warning">
                                    <h3 class="security-warning-title">⚠️ Informações de Segurança</h3>
                                    <p class="security-warning-text">
                                        • Este link é válido por <strong>{{ $expirationTime ?? '15 minutos' }}</strong><br>
                                        • O link só pode ser usado uma vez<br>
                                        • Se você não solicitou esta recuperação, ignore este email<br>
                                        • Nunca compartilhe este link com outras pessoas
                                    </p>
                                </div>

                                <div class="request-info">
                                    <h3 class="request-info-title">📋 Detalhes da Solicitação</h3>
                                    <div class="info-item">
                                        <span class="info-label">Email:</span>
                                        <span class="info-value">{{ $userEmail ?? 'Não informado' }}</span>
                                    </div>
                                    <div class="info-item">
                                        <span class="info-label">Data/Hora:</span>
                                        <span class="info-value">{{ $requestedAt ?? 'Não informado' }}</span>
                                    </div>
                                    <div class="info-item">
                                        <span class="info-label">IP:</span>
                                        <span class="info-value">{{ $requestIp ?? 'Não informado' }}</span>
                                    </div>
                                </div>
                            </td>
                        </tr>

                        <!-- Footer -->
                        <tr>
                            <td class="email-footer">
                                <p style="margin: 0;">&copy; {{ date('Y') }} JAMEES. Todos os direitos reservados.</p>
                                <p style="margin: 5px 0 0 0;"><a href="#" style="color: #ffffff; text-decoration: underline;">Política de Privacidade</a> | <a href="#" style="color: #ffffff; text-decoration: underline;">Termos de Uso</a></p>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <!--[if mso | IE]>
        </td>
        </tr>
        </table>
        <![endif]-->
    </center>
</body>
</html>
