<?php

namespace App\Services\Dre;

use App\Models\Dre;
use App\Enums\DreTipoEnum;
use Carbon\Carbon;
use Illuminate\Support\Str;

class CriarDreService
{
    public function criar($empresaId)
    {
        $dados = $this->dadosFixos();

        // Primeiro, criar todos os itens raiz
        foreach ($dados as $item) {
            $this->inserirItem($item, $empresaId);
        }

        // Depois, criar todos os subitens
        foreach ($dados as $item) {
            if (isset($item['subitens']) && is_array($item['subitens'])) {
                $itemPai = Dre::where('empresa_id', $empresaId)
                    ->where('nome', $item['nome'])
                    ->whereNull('dre_id')
                    ->first();

                if ($itemPai) {
                    foreach ($item['subitens'] as $subitem) {
                        $this->inserirSubitem($subitem, $empresaId, $itemPai->id);
                    }
                }
            }
        }
    }

    private function inserirItem(array $item, string $empresaId)
    {
        Dre::create([
            'id'            => Str::uuid()->toString(),
            'empresa_id'    => $empresaId,
            'dre_id'        => null,
            'nome'          => $item['nome'],
            'tipo'          => $item['tipo']->value,
            'status'        => $item['ativo'] ? 1 : 0,
            'criado_em'     => Carbon::now(),
            'atualizado_em' => Carbon::now(),
        ]);
    }

    private function inserirSubitem(array $subitem, string $empresaId, string $parentId)
    {
        Dre::create([
            'id'            => Str::uuid()->toString(),
            'empresa_id'    => $empresaId,
            'dre_id'        => $parentId,
            'nome'          => $subitem['nome'],
            'tipo'          => $subitem['tipo']->value,
            'status'        => $subitem['ativo'] ? 1 : 0,
            'criado_em'     => Carbon::now(),
            'atualizado_em' => Carbon::now(),
        ]);
    }

    public function dadosFixos(): array
    {
        return [
            [
                "nome" => "Receita bruta",
                "tipo" => DreTipoEnum::RECEITA,
                "ativo" => true,
                "subitens" => [
                    ["nome" => "Receitas de vendas", "tipo" => DreTipoEnum::RECEITA, "ativo" => true]
                ]
            ],
            [
                "nome" => "Deduções",
                "tipo" => DreTipoEnum::DESPESA,
                "ativo" => true,
                "subitens" => [
                    ["nome" => "Impostos sobre vendas", "tipo" => DreTipoEnum::DESPESA, "ativo" => true],
                    ["nome" => "Comissões sobre vendas", "tipo" => DreTipoEnum::DESPESA, "ativo" => true],
                    ["nome" => "Devolução de vendas", "tipo" => DreTipoEnum::DESPESA, "ativo" => true]
                ]
            ],
            [
                "nome" => "Receita líquida",
                "tipo" => DreTipoEnum::TOTALIZADOR,
                "ativo" => true,
                "subitens" => []
            ],
            [
                "nome" => "Custos operacionais",
                "tipo" => DreTipoEnum::DESPESA,
                "ativo" => true,
                "subitens" => [
                    ["nome" => "Custo dos produtos vendidos", "tipo" => DreTipoEnum::DESPESA, "ativo" => true]
                ]
            ],
            [
                "nome" => "Despesas operacionais",
                "tipo" => DreTipoEnum::DESPESA,
                "ativo" => true,
                "subitens" => [
                    ["nome" => "Despesas administrativas", "tipo" => DreTipoEnum::DESPESA, "ativo" => true],
                    ["nome" => "Despesas operacionais", "tipo" => DreTipoEnum::DESPESA, "ativo" => true],
                    ["nome" => "Despesas comerciais", "tipo" => DreTipoEnum::DESPESA, "ativo" => true]
                ]
            ],
            [
                "nome" => "Lucro operacional",
                "tipo" => DreTipoEnum::TOTALIZADOR,
                "ativo" => true,
                "subitens" => []
            ],
            [
                "nome" => "Receitas financeiras",
                "tipo" => DreTipoEnum::RECEITA,
                "ativo" => true,
                "subitens" => [
                    ["nome" => "Rendimentos financeiros", "tipo" => DreTipoEnum::RECEITA, "ativo" => true],
                    ["nome" => "Juros/multas recebidos", "tipo" => DreTipoEnum::RECEITA, "ativo" => true],
                    ["nome" => "Descontos recebidos", "tipo" => DreTipoEnum::RECEITA, "ativo" => true]
                ]
            ],
            [
                "nome" => "Despesas financeiras",
                "tipo" => DreTipoEnum::DESPESA,
                "ativo" => true,
                "subitens" => [
                    ["nome" => "Empréstimos e dívidas", "tipo" => DreTipoEnum::DESPESA, "ativo" => true],
                    ["nome" => "Juros/multas pagos", "tipo" => DreTipoEnum::DESPESA, "ativo" => true],
                    ["nome" => "Descontos concedidos", "tipo" => DreTipoEnum::DESPESA, "ativo" => true],
                    ["nome" => "Taxas/tarifas bancárias", "tipo" => DreTipoEnum::DESPESA, "ativo" => true]
                ]
            ],
            [
                "nome" => "Outras receitas",
                "tipo" => DreTipoEnum::RECEITA,
                "ativo" => true,
                "subitens" => [
                    ["nome" => "Outras receitas", "tipo" => DreTipoEnum::RECEITA, "ativo" => true]
                ]
            ],
            [
                "nome" => "Outras despesas",
                "tipo" => DreTipoEnum::DESPESA,
                "ativo" => true,
                "subitens" => [
                    ["nome" => "Outras despesas", "tipo" => DreTipoEnum::DESPESA, "ativo" => true]
                ]
            ],
            [
                "nome" => "Lucro/prejuízo",
                "tipo" => DreTipoEnum::TOTALIZADOR,
                "ativo" => true,
                "subitens" => []
            ]
        ];
    }
}
