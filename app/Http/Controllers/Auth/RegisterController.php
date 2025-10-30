<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Usuario;
use App\Models\Empresa;
use App\Models\Whitelabel;
use App\Models\UsuarioTelefone;
use App\Models\Grupo;
use App\Models\Permissao;
use App\Models\Plano;
use App\Services\PlanoService;
use App\Services\Dre\CriarDreService;
use App\Services\FormaPagamento\CriarFormasPagamentoService;
use App\Services\PlanoConta\CriarPlanoContaService;
use App\Services\PHPMailerService;
use App\Enums\UsuarioStatusEnum;
use App\Enums\EmpresaStatusEnum;
use App\Enums\UsuarioTelefoneTipoEnum;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'empresa_nome' => 'required|string|max:255',
            'nome' => 'required|string|max:255',
            'telefone' => 'required|string|max:20',
            'email' => 'required|string|email:rfc|max:255|unique:usuario,email',
            'senha' => 'required|string|min:6|confirmed',
        ], [
            'email.email' => 'O email deve ter um formato válido.',
            'email.unique' => 'Este email já está sendo usado por outro usuário.',
            'senha.min' => 'A senha deve ter pelo menos 6 caracteres.',
            'senha.confirmed' => 'A confirmação da senha não confere.',
        ]);

        // Verificar se já existe um usuário principal
        $usuarioPrincipalExistente = Usuario::where('principal', true)->first();

        // Criar usuário
        $usuario = Usuario::create([
            'id' => Str::uuid()->toString(),
            'nome' => $request->nome,
            'email' => $request->email,
            'senha' => Hash::make($request->senha),
            'status' => UsuarioStatusEnum::ATIVO,
            'principal' => !$usuarioPrincipalExistente, // Primeiro usuário é principal
            'criado_em' => now(),
            'atualizado_em' => now(),
        ]);

        // Usar a empresa principal existente ou criar uma nova se não existir
            // Se não há empresa principal, criar uma
            // Selecionar whitelabel pelo domínio do host ou fallback para dominio NULL
            $whitelabel = Whitelabel::resolveByRequestDomain();
            $empresa = Empresa::create([
                'id' => Str::uuid()->toString(),
                'whitelabel_id' => $whitelabel->id,
                'nome_fantasia' => $request->empresa_nome,
                'razao_social' => $request->empresa_nome,
                'tipo' => 'PJ',
                'status' => EmpresaStatusEnum::ATIVA,
                'principal' => 1,
                'criado_em' => now(),
                'atualizado_em' => now(),
            ]);
        // Vincular usuário à empresa
        $usuario->empresas()->attach($empresa->id, [
            'principal' => 1,
                'status' => UsuarioStatusEnum::ATIVO,
            'criado_em' => now(),
            'atualizado_em' => now(),
        ]);

        // Adicionar telefone do usuário
        UsuarioTelefone::create([
            'id' => Str::uuid()->toString(),
            'usuario_id' => $usuario->id,
                'tipo' => UsuarioTelefoneTipoEnum::CELULAR,
            'ddd' => substr($request->telefone, 0, 2),
            'numero' => substr($request->telefone, 2),
            'criado_em' => now(),
            'atualizado_em' => now(),
        ]);

        // Criar grupo Administrativo para a empresa
        $grupoAdmin = Grupo::create([
            'id' => Str::uuid()->toString(),
            'empresa_id' => $empresa->id,
            'nome' => 'Administrativo',
            'descricao' => 'Grupo com acesso total ao sistema',
            'administrativo' => true,
            'ativo' => true,
            'criado_em' => now(),
            'atualizado_em' => now(),
        ]);

        // Vincular todas as permissões ao grupo administrativo
        $todasPermissoes = Permissao::ativas()->get();
        $grupoAdmin->permissoes()->sync(
            $todasPermissoes->mapWithKeys(function ($permissao) {
                return [$permissao->id => ['concedida' => true]];
            })
        );

        // Vincular usuário ao grupo administrativo
        $usuario->grupos()->sync([$grupoAdmin->id]);

        // Criar plano de teste de 10 dias para a empresa
        $planoService = new PlanoService();
        $planoTeste = $planoService->ativarTesteGratuito($empresa, 10);

        // Criar estrutura DRE padrão para a empresa
        $dreService = new CriarDreService();
        $dreService->criar($empresa->id);

        // Criar formas de pagamento padrão para a empresa
        $formasPagamentoService = new CriarFormasPagamentoService();
        $formasPagamentoService->criar($empresa->id);

        // Criar plano de contas padrão para a empresa
        $planoContaService = new CriarPlanoContaService();
        $planoContaService->criar($empresa->id);

        // Fazer login do usuário
        Auth::login($usuario);

        // Enviar email de boas-vindas
        try {
            $dadosUsuario = [
                'userName' => $usuario->nome,
                'userEmail' => $usuario->email,
                'companyName' => $empresa->nome_fantasia,
                'createdAt' => now()->format('d/m/Y H:i'),
                'customMessage' => 'Bem-vindo ao Sistema JAMEES! Sua conta foi criada com sucesso e você já pode começar a usar todas as funcionalidades disponíveis.'
            ];

            PHPMailerService::sendWelcomeEmail($usuario->email, $dadosUsuario);
        } catch (\Exception $e) {
            // Log do erro mas não interrompe o fluxo de registro
            Log::error('Erro ao enviar email de boas-vindas: ' . $e->getMessage());
        }

        return redirect()->route('dashboard')->with('success', 'Usuário registrado com sucesso! Você tem 10 dias de teste gratuito.');
    }
}
