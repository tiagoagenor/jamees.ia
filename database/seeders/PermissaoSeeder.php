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
        ];

        foreach ($permissoes as $permissao) {
            Permissao::create(array_merge($permissao, [
                'id' => \Illuminate\Support\Str::uuid()->toString(),
            ]));
        }
    }
}
