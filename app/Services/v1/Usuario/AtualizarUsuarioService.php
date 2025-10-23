<?php

namespace App\Services\v1\Usuario;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;
use App\Models\UsuarioTelefone;
use App\Models\UsuarioGeral;
use App\Models\UsuarioEndereco;
use App\Enums\UsuarioStatusEnum;
use App\Enums\UsuarioTelefoneTipoEnum;
use Illuminate\Support\Str;

class AtualizarUsuarioService
{
    public function execute(Request $request, Usuario $usuario)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:usuario,email,' . $usuario->id,
            'senha' => 'nullable|string|min:6|confirmed',
            'telefones' => 'required|array|min:1',
            'telefones.*.numero' => 'required|string|max:20',
            'telefones.*.tipo' => 'required|integer|in:1,2,3,4',
            'empresas' => 'required|array|min:1',
            'empresas.*' => 'required|exists:empresa,id',
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
            $usuario->update([
                'nome' => $request->nome,
                'email' => $request->email,
                'senha' => $request->senha ? Hash::make($request->senha) : $usuario->senha,
                'status' => $request->status ?? $usuario->status,
                'atualizado_em' => now(),
            ]);

            // Atualizar vínculos com empresas
            $empresasData = [];
            foreach ($request->empresas as $index => $empresaId) {
                $empresasData[$empresaId] = [
                    'principal' => $index === 0 ? 1 : 0, // Primeira empresa é principal
                    'status' => UsuarioStatusEnum::ATIVO,
                    'criado_em' => now(),
                    'atualizado_em' => now(),
                ];
            }
            $usuario->empresas()->sync($empresasData);

            // Atualizar telefones
            $usuario->telefones()->delete(); // Remove todos os telefones existentes

            foreach ($request->telefones as $telefone) {
                // Remover máscara do telefone
                $numeroLimpo = preg_replace('/\D/', '', $telefone['numero']);

                UsuarioTelefone::create([
                    'id' => Str::uuid()->toString(),
                    'usuario_id' => $usuario->id,
                    'tipo' => UsuarioTelefoneTipoEnum::from($telefone['tipo']),
                    'ddd' => substr($numeroLimpo, 0, 2),
                    'numero' => substr($numeroLimpo, 2),
                    'criado_em' => now(),
                    'atualizado_em' => now(),
                ]);
            }

            // Atualizar informações pessoais
            $geral = $usuario->geral()->first();
            if ($geral) {
                $geral->update([
                    'cpf' => $request->cpf,
                    'rg' => $request->rg,
                    'data_nascimento' => $request->data_nascimento,
                    'sexo' => $request->sexo,
                    'comissao' => $request->comissao,
                    'desconto_maximo' => $request->desconto_maximo,
                    'obs' => $request->obs,
                    'atualizado_em' => now(),
                ]);
            } else if ($request->cpf || $request->rg || $request->data_nascimento || $request->sexo || $request->comissao || $request->desconto_maximo || $request->obs) {
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

            // Atualizar endereço
            $endereco = $usuario->enderecos()->first();
            if ($endereco) {
                $endereco->update([
                    'cep' => $request->cep,
                    'logradouro' => $request->logradouro,
                    'numero' => $request->numero,
                    'complemento' => $request->complemento,
                    'bairro' => $request->bairro,
                    'uf' => $request->uf,
                    'atualizado_em' => now(),
                ]);
            } else if ($request->cep || $request->logradouro || $request->numero || $request->complemento || $request->bairro || $request->uf) {
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

            return redirect()->route('usuarios.index')
                ->with('success', 'Usuário atualizado com sucesso!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erro ao atualizar usuário: ' . $e->getMessage());
        }
    }
}
