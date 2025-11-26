<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permissao;

class PermissaoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissoes = [
            // Módulo: Usuários (baseado nas rotas existentes)
            ['modulo' => 'usuarios', 'acao' => 'listar', 'nome' => 'Listar usuários', 'descricao' => 'Visualizar lista de usuários'],
            ['modulo' => 'usuarios', 'acao' => 'criar', 'nome' => 'Criar usuário', 'descricao' => 'Adicionar novos usuários'],
            ['modulo' => 'usuarios', 'acao' => 'editar', 'nome' => 'Editar usuário', 'descricao' => 'Modificar dados de usuários'],
            ['modulo' => 'usuarios', 'acao' => 'visualizar', 'nome' => 'Visualizar usuário', 'descricao' => 'Ver detalhes de usuários'],
            ['modulo' => 'usuarios', 'acao' => 'deletar', 'nome' => 'Deletar usuário', 'descricao' => 'Remover usuários do sistema'],

            // Módulo: Empresas (baseado nas rotas existentes)
            ['modulo' => 'empresas', 'acao' => 'listar', 'nome' => 'Listar empresas', 'descricao' => 'Visualizar lista de empresas'],
            ['modulo' => 'empresas', 'acao' => 'criar', 'nome' => 'Criar empresa', 'descricao' => 'Adicionar novas empresas'],
            ['modulo' => 'empresas', 'acao' => 'editar', 'nome' => 'Editar empresa', 'descricao' => 'Modificar dados de empresas'],
            ['modulo' => 'empresas', 'acao' => 'visualizar', 'nome' => 'Visualizar empresa', 'descricao' => 'Ver detalhes de empresas'],
            ['modulo' => 'empresas', 'acao' => 'deletar', 'nome' => 'Deletar empresa', 'descricao' => 'Remover empresas do sistema'],

            // Módulo: Grupos (baseado nas rotas existentes)
            ['modulo' => 'grupos', 'acao' => 'listar', 'nome' => 'Listar grupos', 'descricao' => 'Visualizar lista de grupos de usuários'],
            ['modulo' => 'grupos', 'acao' => 'criar', 'nome' => 'Criar grupo', 'descricao' => 'Adicionar novos grupos de usuários'],
            ['modulo' => 'grupos', 'acao' => 'editar', 'nome' => 'Editar grupo', 'descricao' => 'Modificar dados de grupos'],
            ['modulo' => 'grupos', 'acao' => 'visualizar', 'nome' => 'Visualizar grupo', 'descricao' => 'Ver detalhes de grupos'],
            ['modulo' => 'grupos', 'acao' => 'deletar', 'nome' => 'Deletar grupo', 'descricao' => 'Remover grupos do sistema'],

            // Módulo: Permissões (baseado nas rotas existentes)
            ['modulo' => 'permissoes', 'acao' => 'listar', 'nome' => 'Listar permissões', 'descricao' => 'Visualizar lista de permissões do sistema'],
            ['modulo' => 'permissoes', 'acao' => 'criar', 'nome' => 'Criar permissão', 'descricao' => 'Adicionar novas permissões'],
            ['modulo' => 'permissoes', 'acao' => 'editar', 'nome' => 'Editar permissão', 'descricao' => 'Modificar dados de permissões'],
            ['modulo' => 'permissoes', 'acao' => 'visualizar', 'nome' => 'Visualizar permissão', 'descricao' => 'Ver detalhes de permissões'],
            ['modulo' => 'permissoes', 'acao' => 'deletar', 'nome' => 'Deletar permissão', 'descricao' => 'Remover permissões do sistema'],

            // Módulo: Perfil (baseado nas rotas existentes)
            ['modulo' => 'perfil', 'acao' => 'visualizar', 'nome' => 'Visualizar perfil', 'descricao' => 'Ver dados do próprio perfil'],
            ['modulo' => 'perfil', 'acao' => 'editar', 'nome' => 'Editar perfil', 'descricao' => 'Modificar dados do próprio perfil'],
            ['modulo' => 'perfil', 'acao' => 'deletar', 'nome' => 'Deletar perfil', 'descricao' => 'Remover próprio perfil do sistema'],

            // Módulo: Trocar Empresa (funcionalidade existente)
            ['modulo' => 'empresa', 'acao' => 'trocar', 'nome' => 'Trocar empresa', 'descricao' => 'Alterar empresa ativa na sessão'],

            // Módulo: Entidades (clientes, fornecedores, funcionários, transportadoras)
            ['modulo' => 'entidades', 'acao' => 'listar', 'nome' => 'Listar entidades', 'descricao' => 'Visualizar lista de entidades (clientes, fornecedores, funcionários, transportadoras)'],
            ['modulo' => 'entidades', 'acao' => 'criar', 'nome' => 'Criar entidade', 'descricao' => 'Adicionar novas entidades'],
            ['modulo' => 'entidades', 'acao' => 'editar', 'nome' => 'Editar entidade', 'descricao' => 'Modificar dados de entidades'],
            ['modulo' => 'entidades', 'acao' => 'visualizar', 'nome' => 'Visualizar entidade', 'descricao' => 'Ver detalhes de entidades'],
            ['modulo' => 'entidades', 'acao' => 'deletar', 'nome' => 'Deletar entidade', 'descricao' => 'Remover entidades do sistema'],

            // Módulo: DRE (Demonstrativo de Resultado do Exercício)
            ['modulo' => 'dre', 'acao' => 'listar', 'nome' => 'Listar DRE', 'descricao' => 'Visualizar lista de itens DRE'],
            ['modulo' => 'dre', 'acao' => 'criar', 'nome' => 'Criar DRE', 'descricao' => 'Adicionar novos itens DRE'],
            ['modulo' => 'dre', 'acao' => 'editar', 'nome' => 'Editar DRE', 'descricao' => 'Modificar dados de itens DRE'],
            ['modulo' => 'dre', 'acao' => 'visualizar', 'nome' => 'Visualizar DRE', 'descricao' => 'Ver detalhes de itens DRE'],
            ['modulo' => 'dre', 'acao' => 'deletar', 'nome' => 'Deletar DRE', 'descricao' => 'Remover itens DRE do sistema'],

            // Módulo: Contas Bancárias
            ['modulo' => 'contas-bancarias', 'acao' => 'listar', 'nome' => 'Listar contas bancárias', 'descricao' => 'Visualizar lista de contas bancárias'],
            ['modulo' => 'contas-bancarias', 'acao' => 'criar', 'nome' => 'Criar conta bancária', 'descricao' => 'Adicionar novas contas bancárias'],
            ['modulo' => 'contas-bancarias', 'acao' => 'editar', 'nome' => 'Editar conta bancária', 'descricao' => 'Modificar dados de contas bancárias'],
            ['modulo' => 'contas-bancarias', 'acao' => 'visualizar', 'nome' => 'Visualizar conta bancária', 'descricao' => 'Ver detalhes de contas bancárias'],
            ['modulo' => 'contas-bancarias', 'acao' => 'deletar', 'nome' => 'Deletar conta bancária', 'descricao' => 'Remover contas bancárias do sistema'],

            // Módulo: Formas de Pagamento
            ['modulo' => 'formas-pagamento', 'acao' => 'listar', 'nome' => 'Listar formas de pagamento', 'descricao' => 'Visualizar lista de formas de pagamento'],
            ['modulo' => 'formas-pagamento', 'acao' => 'criar', 'nome' => 'Criar forma de pagamento', 'descricao' => 'Adicionar novas formas de pagamento'],
            ['modulo' => 'formas-pagamento', 'acao' => 'editar', 'nome' => 'Editar forma de pagamento', 'descricao' => 'Modificar dados de formas de pagamento'],
            ['modulo' => 'formas-pagamento', 'acao' => 'visualizar', 'nome' => 'Visualizar forma de pagamento', 'descricao' => 'Ver detalhes de formas de pagamento'],
            ['modulo' => 'formas-pagamento', 'acao' => 'deletar', 'nome' => 'Deletar forma de pagamento', 'descricao' => 'Remover formas de pagamento do sistema'],

            // Módulo: Plano de Conta
            ['modulo' => 'plano-conta', 'acao' => 'listar', 'nome' => 'Listar plano de contas', 'descricao' => 'Visualizar lista de plano de contas'],
            ['modulo' => 'plano-conta', 'acao' => 'criar', 'nome' => 'Criar plano de conta', 'descricao' => 'Adicionar novos plano de contas'],
            ['modulo' => 'plano-conta', 'acao' => 'editar', 'nome' => 'Editar plano de conta', 'descricao' => 'Modificar dados de plano de contas'],
            ['modulo' => 'plano-conta', 'acao' => 'visualizar', 'nome' => 'Visualizar plano de conta', 'descricao' => 'Ver detalhes de plano de contas'],
            ['modulo' => 'plano-conta', 'acao' => 'deletar', 'nome' => 'Deletar plano de conta', 'descricao' => 'Remover plano de contas do sistema'],

            // Módulo: Central de Custo
            ['modulo' => 'central-custo', 'acao' => 'listar', 'nome' => 'Listar centros de custo', 'descricao' => 'Visualizar lista de centros de custo'],
            ['modulo' => 'central-custo', 'acao' => 'criar', 'nome' => 'Criar centro de custo', 'descricao' => 'Adicionar novos centros de custo'],
            ['modulo' => 'central-custo', 'acao' => 'editar', 'nome' => 'Editar centro de custo', 'descricao' => 'Modificar dados de centros de custo'],
            ['modulo' => 'central-custo', 'acao' => 'visualizar', 'nome' => 'Visualizar centro de custo', 'descricao' => 'Ver detalhes de centros de custo'],
            ['modulo' => 'central-custo', 'acao' => 'deletar', 'nome' => 'Deletar centro de custo', 'descricao' => 'Remover centros de custo do sistema'],

            // Módulo: Conciliação Bancária
            ['modulo' => 'conciliacao-bancaria', 'acao' => 'listar', 'nome' => 'Listar conciliações bancárias', 'descricao' => 'Visualizar lista de conciliações bancárias'],
            ['modulo' => 'conciliacao-bancaria', 'acao' => 'criar', 'nome' => 'Criar conciliação bancária', 'descricao' => 'Adicionar novas conciliações bancárias'],
            ['modulo' => 'conciliacao-bancaria', 'acao' => 'editar', 'nome' => 'Editar conciliação bancária', 'descricao' => 'Modificar dados de conciliações bancárias'],
            ['modulo' => 'conciliacao-bancaria', 'acao' => 'visualizar', 'nome' => 'Visualizar conciliação bancária', 'descricao' => 'Ver detalhes de conciliações bancárias'],
            ['modulo' => 'conciliacao-bancaria', 'acao' => 'deletar', 'nome' => 'Deletar conciliação bancária', 'descricao' => 'Remover conciliações bancárias do sistema'],

            // Módulo: Movimentação Financeira
            ['modulo' => 'movimentacao', 'acao' => 'listar', 'nome' => 'Listar movimentações', 'descricao' => 'Visualizar lista de movimentações financeiras'],
            ['modulo' => 'movimentacao', 'acao' => 'criar', 'nome' => 'Criar movimentação', 'descricao' => 'Adicionar novas movimentações financeiras'],
            ['modulo' => 'movimentacao', 'acao' => 'editar', 'nome' => 'Editar movimentação', 'descricao' => 'Modificar dados de movimentações financeiras'],
            ['modulo' => 'movimentacao', 'acao' => 'visualizar', 'nome' => 'Visualizar movimentação', 'descricao' => 'Ver detalhes de movimentações financeiras'],
            ['modulo' => 'movimentacao', 'acao' => 'deletar', 'nome' => 'Deletar movimentação', 'descricao' => 'Remover movimentações financeiras do sistema'],
        ];

        foreach ($permissoes as $permissao) {
            Permissao::create(array_merge($permissao, [
                'id' => \Illuminate\Support\Str::uuid()->toString(),
            ]));
        }
    }
}
