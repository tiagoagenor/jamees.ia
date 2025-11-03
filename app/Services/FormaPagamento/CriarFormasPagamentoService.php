<?php

namespace App\Services\FormaPagamento;

use App\Models\FormaPagamento;
use Illuminate\Support\Str;

class CriarFormasPagamentoService
{
    /**
     * Criar formas de pagamento padrão para uma empresa
     */
    public function criar($empresaId, $contaEmpresaId = null)
    {
        $formas = $this->dadosFormasPagamento();

        foreach ($formas as $dados) {
            FormaPagamento::create([
                'id' => Str::uuid(),
                'empresa_id' => $empresaId,
                'conta_empresa_id' => $contaEmpresaId,
                'nome' => $dados['nome'],
                'numero_parcelas' => $dados['numero_parcelas'],
                'intercalo_parcelas' => $dados['intercalo_parcelas'],
                'primeira_parcela' => $dados['primeira_parcela'],
                'modalidade' => $dados['modalidade'],
                'taxa_banco' => $dados['taxa_banco'],
                'taxa_operadora' => $dados['taxa_operadora'],
                'juros_multa' => $dados['juros_multa'],
                'juros_mora' => $dados['juros_mora'],
                'disponivel' => $dados['disponivel'],
                'confirmacao_automatica' => $dados['confirmacao_automatica'],
                'permite_deletar' => $dados['permite_deletar'],
                'gerar_boleto' => $dados['gerar_boleto'],
                'criado_em' => now(),
                'atualizado_em' => now(),
            ]);
        }
    }

    /**
     * Dados das formas de pagamento padrão
     */
    private function dadosFormasPagamento(): array
    {
        return [
            [
                'nome' => 'A Combinar',
                'numero_parcelas' => 12,
                'intercalo_parcelas' => 0,
                'primeira_parcela' => 0,
                'modalidade' => 18,
                'taxa_banco' => 0,
                'taxa_operadora' => 0,
                'juros_multa' => 0,
                'juros_mora' => 0,
                'disponivel' => 1,
                'confirmacao_automatica' => 0,
                'permite_deletar' => true,
                'gerar_boleto' => 0
            ],
            [
                'nome' => 'Boleto Bancário',
                'numero_parcelas' => 12,
                'intercalo_parcelas' => 30,
                'primeira_parcela' => 0,
                'modalidade' => 13,
                'taxa_banco' => 0,
                'taxa_operadora' => 0,
                'juros_multa' => 0,
                'juros_mora' => 0,
                'disponivel' => 1,
                'confirmacao_automatica' => 0,
                'permite_deletar' => true,
                'gerar_boleto' => 0
            ],
            [
                'nome' => 'Carnê',
                'numero_parcelas' => 12,
                'intercalo_parcelas' => 30,
                'primeira_parcela' => 0,
                'modalidade' => 12,
                'taxa_banco' => 0,
                'taxa_operadora' => 0,
                'juros_multa' => 0,
                'juros_mora' => 0,
                'disponivel' => 1,
                'confirmacao_automatica' => 0,
                'permite_deletar' => true,
                'gerar_boleto' => 0
            ],
            [
                'nome' => 'Cartão de Crédito',
                'numero_parcelas' => 12,
                'intercalo_parcelas' => 30,
                'primeira_parcela' => 0,
                'modalidade' => 2,
                'taxa_banco' => 0,
                'taxa_operadora' => 0,
                'juros_multa' => 0,
                'juros_mora' => 0,
                'disponivel' => 1,
                'confirmacao_automatica' => 0,
                'permite_deletar' => true,
                'gerar_boleto' => 0
            ],
            [
                'nome' => 'Cartão de Débito',
                'numero_parcelas' => 1,
                'intercalo_parcelas' => 0,
                'primeira_parcela' => 0,
                'modalidade' => 3,
                'taxa_banco' => 0,
                'taxa_operadora' => 0,
                'juros_multa' => 0,
                'juros_mora' => 0,
                'disponivel' => 1,
                'confirmacao_automatica' => 0,
                'permite_deletar' => true,
                'gerar_boleto' => 0
            ],
            [
                'nome' => 'Cheque',
                'numero_parcelas' => 12,
                'intercalo_parcelas' => 30,
                'primeira_parcela' => 0,
                'modalidade' => 4,
                'taxa_banco' => 0,
                'taxa_operadora' => 0,
                'juros_multa' => 0,
                'juros_mora' => 0,
                'disponivel' => 1,
                'confirmacao_automatica' => 0,
                'permite_deletar' => true,
                'gerar_boleto' => 0
            ],
            [
                'nome' => 'Devolução de Mercadorias',
                'numero_parcelas' => 12,
                'intercalo_parcelas' => 0,
                'primeira_parcela' => 0,
                'modalidade' => 10,
                'taxa_banco' => 0,
                'taxa_operadora' => 0,
                'juros_multa' => 0,
                'juros_mora' => 0,
                'disponivel' => 1,
                'confirmacao_automatica' => 0,
                'permite_deletar' => false,
                'gerar_boleto' => 0
            ],
            [
                'nome' => 'Dinheiro à Vista',
                'numero_parcelas' => 1,
                'intercalo_parcelas' => 0,
                'primeira_parcela' => 0,
                'modalidade' => 1,
                'taxa_banco' => 0,
                'taxa_operadora' => 0,
                'juros_multa' => 0,
                'juros_mora' => 0,
                'disponivel' => 1,
                'confirmacao_automatica' => 1,
                'permite_deletar' => false,
                'gerar_boleto' => 0
            ],
            [
                'nome' => 'Dinheiro Parcelado',
                'numero_parcelas' => 12,
                'intercalo_parcelas' => 30,
                'primeira_parcela' => 0,
                'modalidade' => 8,
                'taxa_banco' => 0,
                'taxa_operadora' => 0,
                'juros_multa' => 0,
                'juros_mora' => 0,
                'disponivel' => 1,
                'confirmacao_automatica' => 0,
                'permite_deletar' => true,
                'gerar_boleto' => 0
            ],
            [
                'nome' => 'Duplicata Mercantil',
                'numero_parcelas' => 12,
                'intercalo_parcelas' => 30,
                'primeira_parcela' => 0,
                'modalidade' => 11,
                'taxa_banco' => 0,
                'taxa_operadora' => 0,
                'juros_multa' => 0,
                'juros_mora' => 0,
                'disponivel' => 1,
                'confirmacao_automatica' => 0,
                'permite_deletar' => true,
                'gerar_boleto' => 0
            ],
            [
                'nome' => 'PIX',
                'numero_parcelas' => 12,
                'intercalo_parcelas' => 0,
                'primeira_parcela' => 0,
                'modalidade' => 15,
                'taxa_banco' => 0,
                'taxa_operadora' => 0,
                'juros_multa' => 0,
                'juros_mora' => 0,
                'disponivel' => 1,
                'confirmacao_automatica' => 0,
                'permite_deletar' => true,
                'gerar_boleto' => 0
            ],
            [
                'nome' => 'Transferência Bancária',
                'numero_parcelas' => 12,
                'intercalo_parcelas' => 30,
                'primeira_parcela' => 0,
                'modalidade' => 16,
                'taxa_banco' => 0,
                'taxa_operadora' => 0,
                'juros_multa' => 0,
                'juros_mora' => 0,
                'disponivel' => 1,
                'confirmacao_automatica' => 0,
                'permite_deletar' => true,
                'gerar_boleto' => 0
            ],
        ];
    }
}
