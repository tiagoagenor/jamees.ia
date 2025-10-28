<?php

namespace App\Services\v1\Empresa;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Empresa;
use App\Models\EmpresaContato;
use App\Models\EmpresaEndereco;
use App\Enums\EmpresaStatusEnum;
use App\Enums\UsuarioStatusEnum;
use Illuminate\Support\Str;
use App\Services\AuditService;

class CriarEmpresaService
{
    public function execute(Request $request)
    {
        $request->validate([
            'nome_fantasia' => 'required|string|max:255',
            'razao_social' => 'nullable|string|max:255',
            'cnpj' => 'nullable|string|max:255',
            'tipo' => 'required|string|in:PJ,PF',
            'status' => 'nullable|integer',
            'principal' => 'nullable|integer',
            'nome_referencia' => 'nullable|string|max:255',
            'inscricao_estadual' => 'nullable|string|max:255',
            'inscricao_estadual_isenta' => 'nullable|string|max:255',
            'inscricao_municipal' => 'nullable|string|max:255',
            'cnae' => 'nullable|string|max:255',
            'regime_tributario' => 'nullable|string|max:255',
            'regime_especial' => 'nullable|string|max:255',
            'nome' => 'nullable|string|max:255',
            'cpf' => 'nullable|string|max:255',
            'rg' => 'nullable|string|max:255',
            // Contatos
            'contatos' => 'nullable|array',
            'contatos.*.tipo' => 'required_with:contatos|string|in:telefone,email,site,whatsapp',
            'contatos.*.dado' => 'required_with:contatos|string|max:255',
            // Endereços
            'enderecos' => 'nullable|array',
            'enderecos.*.cep' => 'nullable|string|max:255',
            'enderecos.*.logradouro' => 'nullable|string|max:255',
            'enderecos.*.numero' => 'nullable|string|max:255',
            'enderecos.*.complemento' => 'nullable|string|max:255',
            'enderecos.*.bairro' => 'nullable|string|max:255',
            'enderecos.*.uf' => 'nullable|string|max:2',
        ]);

        try {
            // Criar empresa
            $whitelabel = \App\Models\Whitelabel::first();

            $empresa = Empresa::create([
                'id' => Str::uuid()->toString(),
                'whitelabel_id' => $whitelabel->id,
                'nome_fantasia' => $request->nome_fantasia,
                'razao_social' => $request->razao_social,
                'cnpj' => $request->cnpj,
                'tipo' => $request->tipo,
                'status' => $request->status ?? EmpresaStatusEnum::ATIVA,
                'principal' => $request->principal ?? 0,
                'nome_referencia' => $request->nome_referencia,
                'inscricao_estadual' => $request->inscricao_estadual,
                'inscricao_estadual_isenta' => $request->inscricao_estadual_isenta,
                'inscricao_municipal' => $request->inscricao_municipal,
                'cnae' => $request->cnae,
                'regime_tributario' => $request->regime_tributario,
                'regime_especial' => $request->regime_especial,
                'nome' => $request->nome,
                'cpf' => $request->cpf,
                'rg' => $request->rg,
                'criado_em' => now(),
                'atualizado_em' => now(),
            ]);

            // Adicionar contatos
            if ($request->contatos) {
                foreach ($request->contatos as $contato) {
                    if ($contato['dado']) {
                        EmpresaContato::create([
                            'id' => Str::uuid()->toString(),
                            'empresa_id' => $empresa->id,
                            'tipo' => $contato['tipo'],
                            'dado' => $contato['dado'],
                            'criado_em' => now(),
                            'atualizado_em' => now(),
                        ]);
                    }
                }
            }

            // Adicionar endereços
            if ($request->enderecos) {
                foreach ($request->enderecos as $endereco) {
                    if ($endereco['cep'] || $endereco['logradouro'] || $endereco['bairro']) {
                        EmpresaEndereco::create([
                            'id' => Str::uuid()->toString(),
                            'empresa_id' => $empresa->id,
                            'cep' => $endereco['cep'],
                            'logradouro' => $endereco['logradouro'],
                            'numero' => $endereco['numero'],
                            'complemento' => $endereco['complemento'],
                            'bairro' => $endereco['bairro'],
                            'uf' => $endereco['uf'],
                            'criado_em' => now(),
                            'atualizado_em' => now(),
                        ]);
                    }
                }
            }

            // Vincular usuário logado à nova empresa
            $user = Auth::user();
            if ($user) {
                $user->empresas()->attach($empresa->id, [
                    'principal' => 0, // Não é empresa principal
                    'status' => UsuarioStatusEnum::ATIVO,
                    'criado_em' => now(),
                    'atualizado_em' => now(),
                ]);
            }

            // Registrar no audit log
            AuditService::logCreate($empresa, "Criou empresa: {$empresa->nome_fantasia}");

            return redirect()->route('empresas.index')
                ->with('success', 'Empresa criada com sucesso!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erro ao criar empresa: ' . $e->getMessage());
        }
    }
}
