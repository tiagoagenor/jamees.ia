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
            // Capturar valores antigos antes da atualização
            $oldValues = $usuario->getAttributes();

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

            // Atualizar grupos
            $usuario->grupos()->sync($request->grupos);

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

            // Atualizar vínculo com funcionário
            $funcionarioAtual = $usuario->funcionario;

            // Se tinha funcionário vinculado e agora não tem, remover vínculo
            if ($funcionarioAtual && !$request->filled('funcionario_id')) {
                $funcionarioAtual->update(['usuario_id' => null]);
            }
            // Se tinha funcionário diferente, trocar vínculo
            elseif ($funcionarioAtual && $request->filled('funcionario_id') && $funcionarioAtual->id != $request->funcionario_id) {
                $novoFuncionario = Funcionario::find($request->funcionario_id);
                if ($novoFuncionario) {
                    // Verificar se o novo funcionário não está vinculado a outro usuário
                    if (!is_null($novoFuncionario->usuario_id) && $novoFuncionario->usuario_id != $usuario->id) {
                        throw new \Exception('Este funcionário já está vinculado a outro usuário.');
                    }
                    // Remover vínculo do funcionário atual
                    $funcionarioAtual->update(['usuario_id' => null]);
                    // Criar novo vínculo
                    $novoFuncionario->update(['usuario_id' => $usuario->id]);
                }
            }
            // Se não tinha funcionário e agora tem, criar vínculo
            elseif (!$funcionarioAtual && $request->filled('funcionario_id')) {
                $novoFuncionario = Funcionario::find($request->funcionario_id);
                if ($novoFuncionario) {
                    // Verificar se o funcionário não está vinculado a outro usuário
                    if (!is_null($novoFuncionario->usuario_id)) {
                        throw new \Exception('Este funcionário já está vinculado a outro usuário.');
                    }
                    $novoFuncionario->update(['usuario_id' => $usuario->id]);
                }
            }

            // Atualizar ou criar horário de acesso
            $horarioAcesso = $usuario->horarioAcesso;
            if ($request->filled('horario_acesso_ativo') && $request->horario_acesso_ativo) {
                if ($horarioAcesso) {
                    $horarioAcesso->update([
                        'ativo' => true,
                        'hora_entrada' => $request->hora_entrada,
                        'hora_almoco_inicio' => $request->hora_almoco_inicio,
                        'hora_almoco_fim' => $request->hora_almoco_fim,
                        'hora_saida' => $request->hora_saida,
                        'dias_permitidos' => $request->dias_permitidos ?? [],
                    ]);
                } else {
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
            } elseif ($horarioAcesso) {
                // Se desativado, apenas atualizar o flag ativo
                $horarioAcesso->update(['ativo' => false]);
            }

            // Registrar no audit log apenas se houve mudanças
            if ($usuario->wasChanged()) {
                AuditService::logUpdate($usuario, $oldValues, "Atualizou usuário: {$usuario->nome}");
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
