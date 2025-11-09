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
use App\Services\AuditService;
use App\Models\Funcionario;
use App\Models\UsuarioHorarioAcesso;

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
            'telefones.*.tipo' => 'required|integer|in:1,2,3,4',
            'empresas' => 'required|array|min:1',
            'empresas.*' => 'required|exists:empresa,id',
            'grupos' => 'required|array|min:1',
            'grupos.*' => 'required|exists:grupos,id',
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
            'funcionario_id' => 'nullable|exists:funcionario,id',
            // Horários de acesso
            'horario_acesso_ativo' => 'nullable|boolean',
            'hora_entrada' => 'nullable|required_with:horario_acesso_ativo|date_format:H:i',
            'hora_almoco_inicio' => 'nullable|required_with:horario_acesso_ativo|date_format:H:i|after:hora_entrada',
            'hora_almoco_fim' => 'nullable|required_with:horario_acesso_ativo|date_format:H:i|after:hora_almoco_inicio',
            'hora_saida' => 'nullable|required_with:horario_acesso_ativo|date_format:H:i|after:hora_almoco_fim',
            'dias_permitidos' => 'nullable|required_with:horario_acesso_ativo|array|min:1',
            'dias_permitidos.*' => 'nullable|in:domingo,segunda,terça,quarta,quinta,sexta,sabado',
        ]);

        try {
            // Criar usuário
            $usuario = Usuario::create([
                'id' => Str::uuid()->toString(),
                'nome' => $request->nome,
                'email' => $request->email,
                'senha' => Hash::make($request->senha),
                'status' => $request->status ?? UsuarioStatusEnum::ATIVO,
                'criado_em' => now(),
                'atualizado_em' => now(),
            ]);

            // Vincular usuário às empresas
            foreach ($request->empresas as $index => $empresaId) {
                $usuario->empresas()->attach($empresaId, [
                    'principal' => $index === 0 ? 1 : 0, // Primeira empresa é principal
                    'status' => UsuarioStatusEnum::ATIVO,
                    'criado_em' => now(),
                    'atualizado_em' => now(),
                ]);
            }

            // Vincular usuário aos grupos
            $usuario->grupos()->sync($request->grupos);

            // Adicionar telefones do usuário
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

            // Vincular funcionário se fornecido
            if ($request->filled('funcionario_id')) {
                $funcionario = Funcionario::find($request->funcionario_id);
                if ($funcionario && is_null($funcionario->usuario_id)) {
                    // Verificar se não há outro usuário vinculado a este funcionário
                    if (!$funcionario->usuario_id) {
                        $funcionario->update(['usuario_id' => $usuario->id]);
                    } else {
                        throw new \Exception('Este funcionário já está vinculado a outro usuário.');
                    }
                }
            }

            // Criar horário de acesso se fornecido
            if ($request->filled('horario_acesso_ativo') && $request->horario_acesso_ativo) {
                UsuarioHorarioAcesso::create([
                    'id' => Str::uuid()->toString(),
                    'usuario_id' => $usuario->id,
                    'ativo' => true,
                    'hora_entrada' => $request->hora_entrada,
                    'hora_almoco_inicio' => $request->hora_almoco_inicio,
                    'hora_almoco_fim' => $request->hora_almoco_fim,
                    'hora_saida' => $request->hora_saida,
                    'dias_permitidos' => $request->dias_permitidos ?? [],
                ]);
            }

            // Registrar no audit log
            AuditService::logCreate($usuario, "Criou usuário: {$usuario->nome}");

            return redirect()->route('usuarios.index')
                ->with('success', 'Usuário criado com sucesso!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erro ao criar usuário: ' . $e->getMessage());
        }
    }
}
