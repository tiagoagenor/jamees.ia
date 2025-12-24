<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Empresa;
use App\Helpers\AplicativoHelper;
use App\Helpers\PermissionHelper;

class CompanyComposer
{
    /**
     * Bind data to the view.
     */
    public function compose(View $view)
    {
        $currentCompany = null;

        if (Auth::check()) {
            $user = Auth::user();

            // Buscar empresa atual da sessão
            $empresaAtualId = session('empresa_atual_id');
            if ($empresaAtualId) {
                // Verificar se o usuário tem acesso à empresa da sessão
                $currentCompany = $user->empresas()->where('empresa.id', $empresaAtualId)->first();
            }

            // Se não há empresa selecionada, selecionar a primeira disponível
            if (!$currentCompany && $user->empresas->count() > 0) {
                // Primeiro, tentar encontrar empresa principal
                $empresaPrincipal = $user->empresas()->wherePivot('principal', 1)->first();

                if ($empresaPrincipal) {
                    $currentCompany = $empresaPrincipal;
                    session(['empresa_atual_id' => $empresaPrincipal->id]);
                    session(['whitelabel_atual_id' => $empresaPrincipal->whitelabel_id]);
                    session()->save(); // Force save
                } else {
                    // Se não existe empresa principal, pegar a primeira empresa da lista
                    $currentCompany = $user->empresas->first();
                    session(['empresa_atual_id' => $currentCompany->id]);
                    session(['whitelabel_atual_id' => $currentCompany->whitelabel_id]);
                    session()->save(); // Force save
                }
            }

            // Passar também a empresa principal para a view
            $empresaPrincipal = null;
            if ($user->empresas->count() > 0) {
                $empresaPrincipal = $user->empresas()->wherePivot('principal', 1)->first();
            }
        }

        // Gerar array do menu
        $menuItems = $this->getMenuItems();

        $view->with([
            'currentCompany' => $currentCompany,
            'empresaPrincipal' => $empresaPrincipal ?? null,
            'menuItems' => $menuItems
        ]);
    }

    /**
     * Retorna o array estruturado do menu
     */
    private function getMenuItems(): array
    {
        $menuItems = [
            [
                'nome' => 'Início',
                'rota' => 'dashboard',
                'icone' => 'fa-home',
                'submenu' => null,
            ],
            [
                'nome' => 'Cadastro',
                'rota' => null,
                'icone' => 'fa-user-plus',
                'submenu' => [
                    [
                        'nome' => 'Cliente',
                        'rota' => 'clientes.index',
                        'icone' => 'fa-user-tie',
                    ],
                    [
                        'nome' => 'Fornecedor',
                        'rota' => 'fornecedores.index',
                        'icone' => 'fa-truck',
                    ],
                    [
                        'nome' => 'Funcionário',
                        'rota' => 'funcionarios.index',
                        'icone' => 'fa-user',
                    ],
                    [
                        'nome' => 'Transportadora',
                        'rota' => 'transportadoras.index',
                        'icone' => 'fa-shipping-fast',
                    ],
                ],
            ],
            // Loteamento - condicional
            ...(AplicativoHelper::temAplicativo('loteamento') ? [[
                'nome' => 'Loteamento',
                'rota' => null,
                'icone' => 'fa-map',
                'submenu' => [
                    [
                        'nome' => 'Empreendimentos',
                        'rota' => 'empreendimentos.index',
                        'icone' => 'fa-building',
                    ],
                    [
                        'nome' => 'Status',
                        'rota' => 'lote-status.index',
                        'icone' => 'fa-tags',
                    ],
                    [
                        'nome' => 'Mapa',
                        'rota' => 'loteamentos.mapa.index',
                        'icone' => 'fa-map-marked-alt',
                    ],
                    [
                        'nome' => 'Vendas',
                        'rota' => 'loteamentos.vendas.index',
                        'icone' => 'fa-shopping-cart',
                    ],
                ],
            ]] : []),
            [
                'nome' => 'Financeiro',
                'rota' => null,
                'icone' => 'fa-dollar-sign',
                'submenu' => [
                    [
                        'nome' => 'Dashboard',
                        'rota' => 'dashboard.financeiro',
                        'icone' => 'fa-chart-line',
                    ],
                    [
                        'nome' => 'Gerenciar DRE',
                        'rota' => 'dre.index',
                        'icone' => 'fa-chart-pie',
                    ],
                    [
                        'nome' => 'Contas a Pagar',
                        'rota' => 'contas-a-pagar.index',
                        'icone' => 'fa-credit-card',
                    ],
                    [
                        'nome' => 'Contas a Receber',
                        'rota' => 'contas-a-receber.index',
                        'icone' => 'fa-money-bill-wave',
                    ],
                    [
                        'nome' => 'Opções auxiliares',
                        'rota' => null,
                        'icone' => 'fa-cogs',
                        'submenu' => array_filter([
                            [
                                'nome' => 'Contas bancárias',
                                'rota' => 'conta-empresa.index',
                                'icone' => 'fa-university',
                            ],
                            [
                                'nome' => 'Formas de pagamento',
                                'rota' => 'forma-pagamento.index',
                                'icone' => 'fa-credit-card',
                            ],
                            [
                                'nome' => 'Plano de conta',
                                'rota' => 'plano-conta.index',
                                'icone' => 'fa-list-alt',
                            ],
                            [
                                'nome' => 'Centro de custo',
                                'rota' => 'centro-custo.index',
                                'icone' => 'fa-building',
                            ],
                            PermissionHelper::can('conciliacao-bancaria', 'listar') ? [
                                'nome' => 'Conciliação bancária',
                                'rota' => 'conciliacao-bancaria.index',
                                'icone' => 'fa-balance-scale',
                            ] : null,
                        ], fn($item) => $item !== null),
                    ],
                ],
            ],
            [
                'nome' => 'Relatórios',
                'rota' => null,
                'icone' => 'fa-file-alt',
                'submenu' => array_filter([
                    [
                        'nome' => 'Cadastros',
                        'rota' => null,
                        'icone' => 'fa-address-book',
                    ],
                    [
                        'nome' => 'Financeiro',
                        'rota' => null,
                        'icone' => 'fa-chart-line',
                    ],
                    PermissionHelper::can('audit', 'listar') ? [
                        'nome' => 'Histórico de Alterações',
                        'rota' => 'audit.index',
                        'icone' => 'fa-history',
                    ] : null,
                ], fn($item) => $item !== null),
            ],
            [
                'nome' => 'Configurações',
                'rota' => null,
                'icone' => 'fa-cog',
                'submenu' => array_filter([
                    [
                        'nome' => 'Geral',
                        'rota' => 'configuracoes.gerais.index',
                        'icone' => 'fa-sliders-h',
                    ],
                    [
                        'nome' => 'Aplicativo',
                        'rota' => 'aplicativos.index',
                        'icone' => 'fa-mobile-alt',
                    ],
                    [
                        'nome' => 'Meu Plano',
                        'rota' => 'planos.index',
                        'icone' => 'fa-crown',
                    ],
                    PermissionHelper::can('usuarios', 'listar') ? [
                        'nome' => 'Usuário',
                        'rota' => 'usuarios.index',
                        'icone' => 'fa-user',
                    ] : null,
                    PermissionHelper::can('grupos', 'listar') ? [
                        'nome' => 'Grupo de Usuário',
                        'rota' => 'grupos.index',
                        'icone' => 'fa-users-cog',
                    ] : null,
                    PermissionHelper::can('empresas', 'listar') ? [
                        'nome' => 'Empresa/Loja',
                        'rota' => 'empresas.index',
                        'icone' => 'fa-building',
                    ] : null,
                ], fn($item) => $item !== null),
            ],
        ];

        // Marcar menus como abertos se algum submenu estiver ativo
        return $this->marcarMenusAbertos($menuItems);
    }

    /**
     * Percorre recursivamente o array de menu e marca como aberto os menus que contêm rotas ativas
     */
    private function marcarMenusAbertos(array &$menuItems): array
    {
        foreach ($menuItems as &$item) {
            // Verificar se a rota do item está ativa
            $item['ativo'] = false;
            $item['aberto'] = false;

            if ($item['rota']) {
                // Verificar se a rota está ativa
                $item['ativo'] = request()->routeIs($item['rota']) || request()->routeIs($item['rota'] . '.*');
            }

            // Processar submenu recursivamente
            if (isset($item['submenu']) && is_array($item['submenu'])) {
                $item['submenu'] = $this->marcarMenusAbertos($item['submenu']);

                // Verificar se algum submenu está ativo ou aberto
                foreach ($item['submenu'] as $subitem) {
                    if (($subitem['ativo'] ?? false) || ($subitem['aberto'] ?? false)) {
                        $item['aberto'] = true;
                        break;
                    }
                }
            }
        }

        return $menuItems;
    }
}
