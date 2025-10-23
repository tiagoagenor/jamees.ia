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
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    public function __construct()
    {
        $this->middleware('throttle:60,1');
    }

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
            // Criar usuário
            $usuario = Usuario::create([
                'id' => Str::uuid()->toString(),
                'nome' => $request->nome,
                'email' => $request->email,
                'senha' => Hash::make($request->senha),
                'status' => 1,
                'criado_em' => now(),
                'atualizado_em' => now(),
            ]);

            // Criar empresa
            $whitelabel = Whitelabel::first(); // Usar o whitelabel principal
            $empresa = Empresa::create([
                'id' => Str::uuid()->toString(),
                'whitelabel_id' => $whitelabel->id,
                'nome_fantasia' => $request->empresa_nome,
                'razao_social' => $request->empresa_nome,
                'tipo' => 'PJ',
                'status' => 1,
                'principal' => 1,
                'criado_em' => now(),
                'atualizado_em' => now(),
            ]);

            // Vincular usuário à empresa
            $usuario->empresas()->attach($empresa->id, [
                'principal' => 1,
                'status' => 1,
                'criado_em' => now(),
                'atualizado_em' => now(),
            ]);

            // Adicionar telefone do usuário
            UsuarioTelefone::create([
                'id' => Str::uuid()->toString(),
                'usuario_id' => $usuario->id,
                'tipo' => 'celular',
                'ddd' => substr($request->telefone, 0, 2),
                'numero' => substr($request->telefone, 2),
                'criado_em' => now(),
                'atualizado_em' => now(),
            ]);

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
