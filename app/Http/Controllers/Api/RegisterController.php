<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Usuario;
use App\Models\Empresa;
use App\Models\Whitelabel;
use App\Models\UsuarioTelefone;
use App\Models\Grupo;
use App\Models\Permissao;
use App\Enums\UsuarioStatusEnum;
use App\Enums\EmpresaStatusEnum;
use App\Enums\UsuarioTelefoneTipoEnum;
use Illuminate\Support\Str;

class RegisterController extends Controller
{

    public function register(Request $request)
    {
        $request->validate([
            'empresa_nome' => 'required|string|max:255',
            'nome' => 'required|string|max:255',
            'telefone' => 'required|string|max:20',
            'email' => 'required|string|email|max:255|unique:usuario,email',
            'senha' => 'required|string|min:6',
        ]);

        try {
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
            $empresa = Empresa::where('principal', 1)->first();

            if (!$empresa) {
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
            }

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

            return response()->json([
                'success' => true,
                'message' => 'Usuário registrado com sucesso!',
                'data' => [
                    'usuario' => [
                        'id' => $usuario->id,
                        'nome' => $usuario->nome,
                        'email' => $usuario->email,
                    ],
                    'empresa' => [
                        'id' => $empresa->id,
                        'nome_fantasia' => $empresa->nome_fantasia,
                    ]
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao registrar usuário: ' . $e->getMessage()
            ], 500);
        }
    }
}
