<?php

namespace App\Services\PlanoConta;

use App\Models\Dre;
use App\Models\PlanoConta;
use Illuminate\Support\Str;

class CriarPlanoContaService
{
    public function criar($empresaId)
    {
        $dados = [
            [
                "Classificação" => "1.1",
                "Nome" => "Despesas administrativas e comerciais",
                "Movimentação" => "Pagamentos",
                "Grupo do DRE" => "------",
                "ordem" => 1,
                "Filhos" => [
                    ["Classificação" => "1.1.1", "Nome" => "Aluguel", "Movimentação" => "Pagamentos", "Grupo do DRE" => "Despesas administrativas", "ordem" => 1],
                    ["Classificação" => "1.1.2", "Nome" => "Assessorias e associações", "Movimentação" => "Pagamentos", "Grupo do DRE" => "Despesas administrativas", "ordem" => 2],
                    ["Classificação" => "1.1.3", "Nome" => "Cartório", "Movimentação" => "Pagamentos", "Grupo do DRE" => "Despesas administrativas", "ordem" => 3],
                    ["Classificação" => "1.1.4", "Nome" => "Combustivel e translados", "Movimentação" => "Pagamentos", "Grupo do DRE" => "Despesas administrativas", "ordem" => 4],
                    ["Classificação" => "1.1.5", "Nome" => "Confraternizações", "Movimentação" => "Pagamentos", "Grupo do DRE" => "Despesas administrativas", "ordem" => 5],
                    ["Classificação" => "1.1.6", "Nome" => "Contabilidade", "Movimentação" => "Pagamentos", "Grupo do DRE" => "Despesas administrativas", "ordem" => 6],
                    ["Classificação" => "1.1.7", "Nome" => "Correios", "Movimentação" => "Pagamentos", "Grupo do DRE" => "Despesas administrativas", "ordem" => 7],
                    ["Classificação" => "1.1.8", "Nome" => "Cursos e treinamentos", "Movimentação" => "Pagamentos", "Grupo do DRE" => "Despesas administrativas", "ordem" => 8],
                    ["Classificação" => "1.1.9", "Nome" => "Distribuição de lucros", "Movimentação" => "Pagamentos", "Grupo do DRE" => "Outras despesas", "ordem" => 9],
                    ["Classificação" => "1.1.10", "Nome" => "Empréstimos", "Movimentação" => "Pagamentos", "Grupo do DRE" => "Empréstimos e dívidas", "ordem" => 10],
                    ["Classificação" => "1.1.11", "Nome" => "Encargos funcionários - 13o salário", "Movimentação" => "Pagamentos", "Grupo do DRE" => "Despesas administrativas", "ordem" => 11],
                    ["Classificação" => "1.1.12", "Nome" => "Encargos funcionários - alimentação", "Movimentação" => "Pagamentos", "Grupo do DRE" => "Despesas administrativas", "ordem" => 12],
                    ["Classificação" => "1.1.13", "Nome" => "Encargos funcionários - assist. médica e odontol.", "Movimentação" => "Pagamentos", "Grupo do DRE" => "Despesas administrativas", "ordem" => 13],
                    ["Classificação" => "1.1.14", "Nome" => "Encargos funcionários - exames pré e demissionais", "Movimentação" => "Pagamentos", "Grupo do DRE" => "Despesas administrativas", "ordem" => 14],
                    ["Classificação" => "1.1.15", "Nome" => "Encargos funcionários - FGTS", "Movimentação" => "Pagamentos", "Grupo do DRE" => "Despesas administrativas", "ordem" => 15],
                    ["Classificação" => "1.1.16", "Nome" => "Encargos funcionarios - horas extras", "Movimentação" => "Pagamentos", "Grupo do DRE" => "Despesas administrativas", "ordem" => 16],
                    ["Classificação" => "1.1.17", "Nome" => "Encargos funcionários - INSS", "Movimentação" => "Pagamentos", "Grupo do DRE" => "Despesas administrativas", "ordem" => 17],
                    ["Classificação" => "1.1.18", "Nome" => "Encargos funcionários - vale transporte", "Movimentação" => "Pagamentos", "Grupo do DRE" => "Despesas administrativas", "ordem" => 18],
                    ["Classificação" => "1.1.19", "Nome" => "Encargos - rescisões trabalhistas", "Movimentação" => "Pagamentos", "Grupo do DRE" => "Despesas administrativas", "ordem" => 19],
                    ["Classificação" => "1.1.20", "Nome" => "Energia elétrica + água", "Movimentação" => "Pagamentos", "Grupo do DRE" => "Despesas administrativas", "ordem" => 20],
                    ["Classificação" => "1.1.21", "Nome" => "Impostos - alvará", "Movimentação" => "Pagamentos", "Grupo do DRE" => "Despesas administrativas", "ordem" => 21],
                    ["Classificação" => "1.1.22", "Nome" => "Impostos - coleta de lixo", "Movimentação" => "Pagamentos", "Grupo do DRE" => "Despesas administrativas", "ordem" => 22],
                    ["Classificação" => "1.1.23", "Nome" => "Impostos - IPTU", "Movimentação" => "Pagamentos", "Grupo do DRE" => "Despesas administrativas", "ordem" => 23],
                    ["Classificação" => "1.1.24", "Nome" => "Impostos - PIS", "Movimentação" => "Pagamentos", "Grupo do DRE" => "Impostos sobre vendas", "ordem" => 24],
                    ["Classificação" => "1.1.25", "Nome" => "Licença ou aluguel de softwares", "Movimentação" => "Pagamentos", "Grupo do DRE" => "Despesas administrativas", "ordem" => 25],
                    ["Classificação" => "1.1.26", "Nome" => "Limpeza", "Movimentação" => "Pagamentos", "Grupo do DRE" => "Despesas administrativas", "ordem" => 26],
                    ["Classificação" => "1.1.27", "Nome" => "Manutenção equipamentos", "Movimentação" => "Pagamentos", "Grupo do DRE" => "Despesas administrativas", "ordem" => 27],
                    ["Classificação" => "1.1.28", "Nome" => "Marketing e publicidade", "Movimentação" => "Pagamentos", "Grupo do DRE" => "Despesas administrativas", "ordem" => 28],
                    ["Classificação" => "1.1.29", "Nome" => "Material de escritório", "Movimentação" => "Pagamentos", "Grupo do DRE" => "Despesas administrativas", "ordem" => 29],
                    ["Classificação" => "1.1.30", "Nome" => "Material reforma", "Movimentação" => "Pagamentos", "Grupo do DRE" => "Despesas administrativas", "ordem" => 30],
                    ["Classificação" => "1.1.31", "Nome" => "Remuneração funcionários", "Movimentação" => "Pagamentos", "Grupo do DRE" => "Despesas administrativas", "ordem" => 31],
                    ["Classificação" => "1.1.32", "Nome" => "Segurança", "Movimentação" => "Pagamentos", "Grupo do DRE" => "Despesas administrativas", "ordem" => 32],
                    ["Classificação" => "1.1.33", "Nome" => "Supermercado", "Movimentação" => "Pagamentos", "Grupo do DRE" => "Despesas administrativas", "ordem" => 33],
                    ["Classificação" => "1.1.34", "Nome" => "Telefonia e internet", "Movimentação" => "Pagamentos", "Grupo do DRE" => "Despesas administrativas", "ordem" => 34],
                    ["Classificação" => "1.1.35", "Nome" => "Transportadora", "Movimentação" => "Pagamentos", "Grupo do DRE" => "Despesas operacionais", "ordem" => 35],
                    ["Classificação" => "1.1.36", "Nome" => "Viagens", "Movimentação" => "Pagamentos", "Grupo do DRE" => "Despesas comerciais", "ordem" => 36],
                    ["Classificação" => "1.1.37", "Nome" => "Devolução de vendas", "Movimentação" => "Pagamentos", "Grupo do DRE" => "Devolução de vendas", "ordem" => 37],
                ],
            ],
            [
                "Classificação" => "1.2",
                "Nome" => "Despesas de produtos vendidos",
                "Movimentação" => "Pagamentos",
                "Grupo do DRE" => "------",
                "ordem" => 2,
                "Filhos" => [
                    ["Classificação" => "1.2.1", "Nome" => "Comissão de vendedores", "Movimentação" => "Pagamentos", "Grupo do DRE" => "Comissões sobre vendas", "ordem" => 1],
                    ["Classificação" => "1.2.2", "Nome" => "Compras", "Movimentação" => "Pagamentos", "Grupo do DRE" => "Não mostrar no DRE", "ordem" => 2],
                    ["Classificação" => "1.2.3", "Nome" => "Impostos - COFINS", "Movimentação" => "Pagamentos", "Grupo do DRE" => "Impostos sobre vendas", "ordem" => 3],
                    ["Classificação" => "1.2.4", "Nome" => "Impostos - CSSL", "Movimentação" => "Pagamentos", "Grupo do DRE" => "Impostos sobre vendas", "ordem" => 4],
                    ["Classificação" => "1.2.5", "Nome" => "Impostos - ICMS", "Movimentação" => "Pagamentos", "Grupo do DRE" => "Impostos sobre vendas", "ordem" => 5],
                    ["Classificação" => "1.2.6", "Nome" => "Impostos - importação IPI", "Movimentação" => "Pagamentos", "Grupo do DRE" => "Impostos sobre vendas", "ordem" => 6],
                    ["Classificação" => "1.2.7", "Nome" => "Impostos - IRPJ", "Movimentação" => "Pagamentos", "Grupo do DRE" => "Despesas administrativas", "ordem" => 7],
                    ["Classificação" => "1.2.8", "Nome" => "Impostos - ISS", "Movimentação" => "Pagamentos", "Grupo do DRE" => "Impostos sobre vendas", "ordem" => 8],
                ],
            ],
            [
                "Classificação" => "1.3",
                "Nome" => "Despesas financeiras",
                "Movimentação" => "Pagamentos",
                "Grupo do DRE" => "------",
                "ordem" => 3,
                "Filhos" => [
                    ["Classificação" => "1.3.1", "Nome" => "Despesas bancárias", "Movimentação" => "Pagamentos", "Grupo do DRE" => "Taxas/tarifas bancárias", "ordem" => 1],
                ],
            ],
            [
                "Classificação" => "1.4",
                "Nome" => "Investimentos",
                "Movimentação" => "Pagamentos",
                "Grupo do DRE" => "------",
                "ordem" => 4,
                "Filhos" => [
                    ["Classificação" => "1.4.1", "Nome" => "Aquisição de equipamentos", "Movimentação" => "Pagamentos", "Grupo do DRE" => "Outras despesas", "ordem" => 1],
                ],
            ],
            [
                "Classificação" => "1.5",
                "Nome" => "Outras despesas",
                "Movimentação" => "Pagamentos",
                "Grupo do DRE" => "------",
                "ordem" => 5,
                "Filhos" => [
                    ["Classificação" => "1.5.1", "Nome" => "Adiantamento - funcionários", "Movimentação" => "Pagamentos", "Grupo do DRE" => "Despesas administrativas", "ordem" => 1],
                    ["Classificação" => "1.5.2", "Nome" => "Ajuste de caixa", "Movimentação" => "Pagamentos", "Grupo do DRE" => "Outras despesas", "ordem" => 2],
                ],
            ],
            [
                "Classificação" => "2.1",
                "Nome" => "Receitas de vendas",
                "Movimentação" => "Recebimentos",
                "Grupo do DRE" => "------",
                "ordem" => 1,
                "Filhos" => [
                    ["Classificação" => "2.1.1", "Nome" => "Vendas de produtos", "Movimentação" => "Recebimentos", "Grupo do DRE" => "Receitas de vendas", "ordem" => 1],
                    ["Classificação" => "2.1.2", "Nome" => "Vendas no balcão", "Movimentação" => "Recebimentos", "Grupo do DRE" => "Receitas de vendas", "ordem" => 2],
                    ["Classificação" => "2.1.3", "Nome" => "Prestações de serviços", "Movimentação" => "Recebimentos", "Grupo do DRE" => "Receitas de vendas", "ordem" => 3],
                    ["Classificação" => "2.1.4", "Nome" => "Contratos de serviços", "Movimentação" => "Recebimentos", "Grupo do DRE" => "Receitas de vendas", "ordem" => 4],
                    ["Classificação" => "2.1.5", "Nome" => "Locação de equipamentos", "Movimentação" => "Recebimentos", "Grupo do DRE" => "Receitas de vendas", "ordem" => 5],
                ],
            ],
            [
                "Classificação" => "2.2",
                "Nome" => "Receitas financeiras",
                "Movimentação" => "Recebimentos",
                "Grupo do DRE" => "------",
                "ordem" => 2,
                "Filhos" => [
                    ["Classificação" => "2.2.1", "Nome" => "Aplicações financeiras", "Movimentação" => "Recebimentos", "Grupo do DRE" => "Rendimentos financeiros", "ordem" => 1],
                ],
            ],
            [
                "Classificação" => "2.3",
                "Nome" => "Outras receitas",
                "Movimentação" => "Recebimentos",
                "Grupo do DRE" => "------",
                "ordem" => 3,
                "Filhos" => [
                    ["Classificação" => "2.3.1", "Nome" => "Ajuste de caixa", "Movimentação" => "Recebimentos", "Grupo do DRE" => "Outras receitas", "ordem" => 1],
                    ["Classificação" => "2.3.2", "Nome" => "Devolução de adiantamento", "Movimentação" => "Recebimentos", "Grupo do DRE" => "Outras receitas", "ordem" => 2],
                ],
            ],
        ];

        $this->salvarPlanoContaRecursivo($dados, $empresaId, null, null);
    }

    private function salvarPlanoContaRecursivo(array $items, string $empresaId, ?int $order_pai = null, ?string $parentId = null)
    {
        foreach ($items as $item) {
            $dreId = $this->getDreIdByName($item['Grupo do DRE'], $empresaId);
            $movimentacao = $this->converterMovimentacao($item['Movimentação']);

            $planoConta = PlanoConta::create([
                'id' => (string) Str::uuid(),
                'empresa_id' => $empresaId,
                'plano_conta_id' => $parentId,
                'dre_id' => $dreId,
                'nome' => $item['Nome'],
                'movimentacao' => $movimentacao,
                'ordem_pai' => $parentId == null ? $item['ordem'] : $order_pai,
                'ordem_filho' => $parentId == null ? null : $item['ordem'],
                'criado_em' => now(),
                'atualizado_em' => now(),
            ]);

            if (!empty($item['Filhos'])) {
                $this->salvarPlanoContaRecursivo($item['Filhos'], $empresaId, $item['ordem'], $planoConta->id);
            }
        }
    }

    private function getDreIdByName(string $nome, $empresaId): ?string
    {
        if ($nome === '------') {
            return null;
        }

        $dre = Dre::where('nome', $nome)->where('empresa_id', $empresaId)->first();
        return $dre ? $dre->id : null;
    }

    private function converterMovimentacao(string $mov): int
    {
        return match ($mov) {
            'Pagamentos' => 1, // Débito
            'Recebimentos' => 2, // Crédito
            default => 1, // Default para Débito
        };
    }
}
