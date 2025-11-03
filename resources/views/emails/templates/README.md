# 📧 Templates de Email - Sistema JAMEES

Este diretório contém os templates de email HTML responsivos para o sistema JAMEES, baseados no template do GitHub [leemunroe/responsive-html-email-template](https://github.com/leemunroe/responsive-html-email-template).

## 📁 Estrutura

```
resources/views/emails/templates/
├── welcome.blade.php      # Template de boas-vindas responsivo
├── notification.blade.php # Template de notificações responsivo
├── responsive.blade.php   # Template base responsivo
└── README.md             # Este arquivo
```

## 🎯 Templates Disponíveis

### 1. Template de Boas-vindas (`welcome.blade.php`)

**Uso:**
```php
use App\Services\PHPMailerService;

// Envio simples
PHPMailerService::sendWelcomeEmail('usuario@exemplo.com');

// Envio com dados personalizados
$dados = [
    'userName' => 'João Silva',
    'userEmail' => 'joao@exemplo.com',
    'companyName' => 'Empresa ABC',
    'createdAt' => now()->format('d/m/Y H:i'),
    'loginUrl' => url('/login')
];

PHPMailerService::sendWelcomeEmail('usuario@exemplo.com', $dados);
```

**Variáveis disponíveis:**
- `$userName` - Nome do usuário
- `$userEmail` - Email do usuário
- `$companyName` - Nome da empresa
- `$createdAt` - Data de criação da conta
- `$loginUrl` - URL para login

### 2. Template de Notificação (`notification.blade.php`)

**Uso:**
```php
use App\Services\PHPMailerService;

// Notificação simples
PHPMailerService::sendNotificationEmail(
    'admin@exemplo.com',
    'Nova Conta Criada',
    'Uma nova conta foi criada no sistema.'
);

// Notificação com detalhes
$dados = [
    'status' => 'info', // success, warning, danger, info
    'details' => [
        'Usuário' => 'João Silva',
        'Email' => 'joao@exemplo.com',
        'Empresa' => 'Empresa ABC'
    ],
    'actionUrl' => url('/usuarios'),
    'actionText' => 'Ver Usuários',
    'additionalInfo' => 'Por favor, revise os dados.'
];

PHPMailerService::sendNotificationEmail(
    'admin@exemplo.com',
    'Nova Conta Criada',
    'Uma nova conta foi criada no sistema.',
    $dados
);
```

**Variáveis disponíveis:**
- `$title` - Título da notificação
- `$message` - Mensagem principal
- `$status` - Status (success, warning, danger, info)
- `$details` - Array com detalhes adicionais
- `$actionUrl` - URL para ação
- `$actionText` - Texto do botão de ação
- `$additionalInfo` - Informação adicional

## 🎨 Características dos Templates

### ✅ Design Responsivo
- **Baseado no template do GitHub** [leemunroe/responsive-html-email-template](https://github.com/leemunroe/responsive-html-email-template)
- **Estrutura XHTML 1.0 Transitional** para máxima compatibilidade
- **Mobile-first design** com media queries otimizadas
- **Table-based layout** para funcionar em todos os clientes de email

### ✅ Compatibilidade Avançada
- **Testado em todos os principais clientes** de email
- **Suporte a Outlook** (incluindo versões antigas)
- **Funciona em Gmail, Yahoo, Apple Mail** e outros
- **Reset CSS** para normalizar estilos entre clientes

### ✅ Branding JAMEES
- **Cores e identidade visual** do sistema
- **Logo e elementos gráficos** consistentes
- **Gradientes modernos** com fallbacks
- **Typography** otimizada para email

### ✅ Acessibilidade
- **Contraste adequado** para leitura
- **Estrutura semântica** com roles apropriados
- **Suporte a leitores de tela**
- **Alt text** para imagens

### ✅ Recursos Técnicos
- **CSS inline** para máxima compatibilidade
- **MSO conditional comments** para Outlook
- **Apple data detectors** removidos
- **Viewport meta tag** para mobile

## 🔧 Como Criar Novos Templates

1. **Crie o arquivo Blade:**
```php
// resources/views/emails/templates/meu-template.blade.php
<!DOCTYPE html>
<html>
<head>
    <title>{{ $title ?? 'Email JAMEES' }}</title>
    <!-- Seus estilos CSS -->
</head>
<body>
    <!-- Seu conteúdo HTML -->
    <h1>{{ $title }}</h1>
    <p>{{ $message }}</p>
</body>
</html>
```

2. **Adicione método no PHPMailerService:**
```php
public static function sendMeuTemplate(string $to, array $data = []): array
{
    $subject = $data['subject'] ?? 'Assunto Padrão';
    $body = view('emails.templates.meu-template', $data)->render();
    
    return self::send($to, $subject, $body, true);
}
```

3. **Teste o template:**
```php
// Teste via Tinker
$resultado = PHPMailerService::sendMeuTemplate('teste@exemplo.com', [
    'title' => 'Meu Título',
    'message' => 'Minha mensagem'
]);
```

## 📋 Exemplos de Uso

### Em Controllers
```php
public function store(Request $request)
{
    $usuario = Usuario::create($request->all());
    
    // Enviar email de boas-vindas
    PHPMailerService::sendWelcomeEmail($usuario->email, [
        'userName' => $usuario->nome,
        'companyName' => $usuario->empresa->nome_fantasia
    ]);
    
    return redirect()->back()->with('success', 'Usuário criado!');
}
```

### Em Services
```php
public function execute(array $dados): array
{
    // ... lógica de negócio ...
    
    // Notificar administradores
    PHPMailerService::sendNotificationEmail(
        'admin@empresa.com',
        'Nova Solicitação',
        'Uma nova solicitação foi criada.',
        [
            'status' => 'info',
            'details' => $dados,
            'actionUrl' => url('/solicitacoes')
        ]
    );
    
    return ['success' => true];
}
```

### Em Jobs/Queues
```php
public function handle()
{
    // Enviar emails em lote
    foreach ($this->usuarios as $usuario) {
        PHPMailerService::sendWelcomeEmail($usuario->email, [
            'userName' => $usuario->nome,
            'companyName' => $usuario->empresa->nome_fantasia
        ]);
    }
}
```

## 🎯 Status dos Templates

- ✅ **Template de Boas-vindas** - Funcionando
- ✅ **Template de Notificação** - Funcionando
- 🔄 **Template de Recuperação de Senha** - Em desenvolvimento
- 🔄 **Template de Relatório** - Em desenvolvimento
- 🔄 **Template de Cobrança** - Em desenvolvimento

## 📞 Suporte

Para dúvidas sobre os templates de email, consulte:
- Documentação do PHPMailerService
- Exemplos em `EmailTemplateExamples.php`
- Testes em `http://localhost:8000/emails/test`

---

**Sistema JAMEES** - Templates de Email Profissionais ✨
