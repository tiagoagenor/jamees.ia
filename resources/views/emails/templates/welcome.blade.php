<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Bem-vindo ao Sistema JAMEES</title>
    <style>
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

        /* Remove blue links for clients that don't support them */
        a[x-apple-data-detectors] {
            color: inherit !important;
            text-decoration: none !important;
            font-size: inherit !important;
            font-family: inherit !important;
            font-weight: inherit !important;
            line-height: inherit !important;
        }

        /* Base styles */
        body {
            margin: 0;
            padding: 0;
            width: 100% !important;
            min-width: 100%;
            height: 100%;
            background-color: #f4f4f4;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333333;
        }

        /* Email wrapper */
        .email-wrapper {
            width: 100%;
            table-layout: fixed;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        /* Email container */
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
        }

        /* Header */
        .email-header {
            background: #2c3e50;
            padding: 40px 20px;
            text-align: center;
        }

        .logo {
            font-size: 32px;
            font-weight: bold;
            color: #ffffff;
            margin: 0;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .logo-subtitle {
            font-size: 16px;
            color: #ffffff;
            margin: 10px 0 0 0;
            opacity: 0.9;
        }

        /* Content */
        .email-content {
            padding: 40px 20px;
        }

        .email-title {
            font-size: 28px;
            font-weight: 600;
            color: #2c3e50;
            margin: 0 0 20px 0;
            text-align: center;
        }

        .email-message {
            font-size: 16px;
            color: #555555;
            margin: 0 0 30px 0;
            line-height: 1.6;
        }

        /* Button */
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

        /* Features section */
        .features-section {
            margin: 30px 0;
        }

        .features-title {
            font-size: 22px;
            font-weight: 600;
            color: #2c3e50;
            margin: 0 0 20px 0;
            text-align: center;
        }

        .feature-item {
            background-color: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 15px 20px;
            margin: 10px 0;
            border-radius: 0 8px 8px 0;
        }

        .feature-icon {
            font-size: 20px;
            margin-right: 10px;
            color: #667eea;
        }

        .feature-text {
            font-size: 16px;
            color: #555555;
        }

        /* Info box */
        .info-box {
            background-color: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 20px;
            margin: 30px 0;
            border-radius: 0 8px 8px 0;
        }

        .info-title {
            font-size: 18px;
            font-weight: 600;
            color: #2c3e50;
            margin: 0 0 15px 0;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding: 8px 0;
            border-bottom: 1px solid #e9ecef;
        }

        .info-label {
            font-weight: 600;
            color: #555555;
        }

        .info-value {
            color: #667eea;
            font-weight: 500;
        }

        /* Highlight box */
        .highlight-box {
            background: #f8f9fa;
            color: #2c3e50;
            padding: 25px;
            border-radius: 10px;
            margin: 30px 0;
            text-align: center;
            border-left: 4px solid #2c3e50;
        }

        .highlight-title {
            font-size: 20px;
            font-weight: bold;
            margin: 0 0 10px 0;
            color: #2c3e50;
        }

        .highlight-text {
            font-size: 16px;
            margin: 0;
            color: #555555;
        }

        /* Footer */
        .email-footer {
            background-color: #2c3e50;
            color: #ffffff;
            padding: 30px 20px;
            text-align: center;
        }

        .footer-title {
            font-size: 20px;
            font-weight: bold;
            margin: 0 0 15px 0;
        }

        .footer-text {
            font-size: 14px;
            opacity: 0.8;
            margin: 0 0 20px 0;
        }

        .footer-bottom {
            font-size: 12px;
            opacity: 0.6;
            margin: 20px 0 0 0;
            padding: 20px 0 0 0;
            border-top: 1px solid #34495e;
        }

        /* Success badge */
        .success-badge {
            display: inline-block;
            background-color: #28a745;
            color: #ffffff;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        /* Responsive styles */
        @media only screen and (max-width: 600px) {
            .email-container {
                width: 100% !important;
                max-width: 100% !important;
            }

            .email-header,
            .email-content,
            .email-footer {
                padding: 20px !important;
            }

            .logo {
                font-size: 24px !important;
            }

            .email-title {
                font-size: 24px !important;
            }

            .email-button {
                display: block !important;
                width: 100% !important;
                box-sizing: border-box !important;
            }

            .info-item {
                flex-direction: column !important;
                align-items: flex-start !important;
            }

            .info-value {
                margin-top: 5px !important;
            }
        }
    </style>
</head>
<body>
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" class="email-wrapper">
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
                            <div class="success-badge">✅ Conta Criada com Sucesso!</div>

                            <h1 class="email-title">Bem-vindo ao Sistema JAMEES!</h1>

                            <div class="email-message">
                                <p>Olá <strong>{{ $userName ?? 'Usuário' }}</strong>,</p>
                                <p>{{ $customMessage ?? 'O JAMEES foi desenvolvido para simplificar e otimizar os processos da sua empresa, oferecendo ferramentas poderosas e intuitivas para o seu dia a dia.' }}</p>
                            </div>

                            <div class="highlight-box">
                                <h2 class="highlight-title">🚀 Sua jornada começa agora!</h2>
                                <p class="highlight-text">Acesse sua conta e explore todas as funcionalidades disponíveis para sua empresa.</p>
                            </div>

                            <div style="text-align: center;">
                                <a href="{{ $loginUrl ?? env('JAMEES_SITE_URL', 'https://jamees.com/') }}" class="email-button">Acessar Sistema JAMEES</a>
                            </div>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td class="email-footer">
                            <h2 class="footer-title">Sistema JAMEES</h2>
                            <p class="footer-text">
                                Sua plataforma completa de gestão empresarial.<br>
                                Simplificando processos, maximizando resultados.
                            </p>

                            <div class="footer-bottom">
                                <p>Este é um email automático do Sistema JAMEES.</p>
                                <p>Se você não criou esta conta, ignore este email.</p>
                                <p>© {{ date('Y') }} JAMEES. Todos os direitos reservados.</p>
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
