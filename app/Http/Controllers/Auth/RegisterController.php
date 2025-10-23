<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Usuario;
use App\Models\Empresa;
use App\Models\Whitelabel;
use App\Models\UsuarioTelefone;
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

        // Criar usuário
        $usuario = Usuario::create([
            'id' => Str::uuid()->toString(),
            'nome' => $request->nome,
            'email' => $request->email,
            'senha' => Hash::make($request->senha),
                'status' => UsuarioStatusEnum::ATIVO,
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

        // Fazer login do usuário
        Auth::login($usuario);

        return redirect()->route('dashboard')->with('success', 'Usuário registrado com sucesso!');
    }
}
