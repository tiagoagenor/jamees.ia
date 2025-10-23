<?php

namespace App\Services\v1\Usuario;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;
use App\Models\UsuarioTelefone;
use App\Models\UsuarioGeral;
use App\Models\UsuarioEndereco;
use Illuminate\Support\Str;

class CriarUsuarioService
{
    public function execute(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:usuario,email',
            'senha' => 'required|string|min:6|confirmed',
            'telefones' => 'required|array|min:1',
            'telefones.*.numero' => 'required|string|max:20',
            'telefones.*.tipo' => 'required|string|in:celular,residencial,comercial,whatsapp',
            'empresa_id' => 'required|exists:empresa,id',
            'status' => 'nullable|integer',
            // Campos pessoais
            'cpf' => 'nullable|string|max:255',
            'rg' => 'nullable|string|max:255',
            'data_nascimento' => 'nullable|date',
            'sexo' => 'nullable|string|in:M,F,O',
            'comissao' => 'nullable|numeric|min:0|max:100',
            'desconto_maximo' => 'nullable|numeric|min:0|max:100',
            'obs' => 'nullable|string|max:1000',
            // Campos de endereço
            'cep' => 'nullable|string|max:255',
            'logradouro' => 'nullable|string|max:255',
            'numero' => 'nullable|string|max:255',
            'complemento' => 'nullable|string|max:255',
            'bairro' => 'nullable|string|max:255',
            'uf' => 'nullable|string|max:2',
        ]);

        try {
            // Criar usuário
            $usuario = Usuario::create([
                'id' => Str::uuid()->toString(),
                'nome' => $request->nome,
                'email' => $request->email,
                'senha' => Hash::make($request->senha),
                'status' => $request->status ?? 1,
                'criado_em' => now(),
                'atualizado_em' => now(),
            ]);

            // Vincular usuário à empresa
            $usuario->empresas()->attach($request->empresa_id, [
                'principal' => 1,
                'status' => 1,
                'criado_em' => now(),
                'atualizado_em' => now(),
            ]);

            // Adicionar telefones do usuário
            foreach ($request->telefones as $telefone) {
                // Remover máscara do telefone
                $numeroLimpo = preg_replace('/\D/', '', $telefone['numero']);

                UsuarioTelefone::create([
                    'id' => Str::uuid()->toString(),
                    'usuario_id' => $usuario->id,
                    'tipo' => $telefone['tipo'],
                    'ddd' => substr($numeroLimpo, 0, 2),
                    'numero' => substr($numeroLimpo, 2),
                    'criado_em' => now(),
                    'atualizado_em' => now(),
                ]);
            }

            // Adicionar informações pessoais
            if ($request->cpf || $request->rg || $request->data_nascimento || $request->sexo || $request->comissao || $request->desconto_maximo || $request->obs) {
                UsuarioGeral::create([
                    'id' => Str::uuid()->toString(),
                    'usuario_id' => $usuario->id,
                    'cpf' => $request->cpf,
                    'rg' => $request->rg,
                    'data_nascimento' => $request->data_nascimento,
                    'sexo' => $request->sexo,
                    'comissao' => $request->comissao,
                    'desconto_maximo' => $request->desconto_maximo,
                    'obs' => $request->obs,
                    'criado_em' => now(),
                    'atualizado_em' => now(),
                ]);
            }

            // Adicionar endereço
            if ($request->cep || $request->logradouro || $request->numero || $request->complemento || $request->bairro || $request->uf) {
                UsuarioEndereco::create([
                    'id' => Str::uuid()->toString(),
                    'usuario_id' => $usuario->id,
                    'cep' => $request->cep,
                    'logradouro' => $request->logradouro,
                    'numero' => $request->numero,
                    'complemento' => $request->complemento,
                    'bairro' => $request->bairro,
                    'uf' => $request->uf,
                    'criado_em' => now(),
                    'atualizado_em' => now(),
                ]);
            }

            return [
                'success' => true,
                'message' => 'Usuário criado com sucesso!',
                'usuario' => $usuario
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Erro ao criar usuário: ' . $e->getMessage()
            ];
        }
    }
}
