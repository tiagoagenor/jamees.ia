<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Migrar Clientes (tipo_relacionamento = 1)
        $clientes = DB::table('entidade')
            ->where('tipo_relacionamento', 1)
            ->get();

        foreach ($clientes as $cliente) {
            $clienteData = (array) $cliente;
            unset($clienteData['tipo_relacionamento']);
            DB::table('cliente')->insert($clienteData);

            // Migrar contatos
            $contatos = DB::table('entidade_contato')
                ->where('entidade_id', $cliente->id)
                ->get();

            foreach ($contatos as $contato) {
                $contatoData = (array) $contato;
                $contatoData['cliente_id'] = $contatoData['entidade_id'];
                unset($contatoData['entidade_id']);
                DB::table('cliente_contato')->insert($contatoData);
            }

            // Migrar endereços
            $enderecos = DB::table('entidade_endereco')
                ->where('entidade_id', $cliente->id)
                ->get();

            foreach ($enderecos as $endereco) {
                $enderecoData = (array) $endereco;
                $enderecoData['cliente_id'] = $enderecoData['entidade_id'];
                unset($enderecoData['entidade_id']);
                DB::table('cliente_endereco')->insert($enderecoData);
            }
        }

        // Migrar Fornecedores (tipo_relacionamento = 2)
        $fornecedores = DB::table('entidade')
            ->where('tipo_relacionamento', 2)
            ->get();

        foreach ($fornecedores as $fornecedor) {
            $fornecedorData = (array) $fornecedor;
            unset($fornecedorData['tipo_relacionamento']);
            DB::table('fornecedor')->insert($fornecedorData);

            // Migrar contatos
            $contatos = DB::table('entidade_contato')
                ->where('entidade_id', $fornecedor->id)
                ->get();

            foreach ($contatos as $contato) {
                $contatoData = (array) $contato;
                $contatoData['fornecedor_id'] = $contatoData['entidade_id'];
                unset($contatoData['entidade_id']);
                DB::table('fornecedor_contato')->insert($contatoData);
            }

            // Migrar endereços
            $enderecos = DB::table('entidade_endereco')
                ->where('entidade_id', $fornecedor->id)
                ->get();

            foreach ($enderecos as $endereco) {
                $enderecoData = (array) $endereco;
                $enderecoData['fornecedor_id'] = $enderecoData['entidade_id'];
                unset($enderecoData['entidade_id']);
                DB::table('fornecedor_endereco')->insert($enderecoData);
            }
        }

        // Migrar Funcionários (tipo_relacionamento = 3)
        $funcionarios = DB::table('entidade')
            ->where('tipo_relacionamento', 3)
            ->get();

        foreach ($funcionarios as $funcionario) {
            $funcionarioData = (array) $funcionario;
            unset($funcionarioData['tipo_relacionamento']);
            DB::table('funcionario')->insert($funcionarioData);

            // Migrar contatos
            $contatos = DB::table('entidade_contato')
                ->where('entidade_id', $funcionario->id)
                ->get();

            foreach ($contatos as $contato) {
                $contatoData = (array) $contato;
                $contatoData['funcionario_id'] = $contatoData['entidade_id'];
                unset($contatoData['entidade_id']);
                DB::table('funcionario_contato')->insert($contatoData);
            }

            // Migrar endereços
            $enderecos = DB::table('entidade_endereco')
                ->where('entidade_id', $funcionario->id)
                ->get();

            foreach ($enderecos as $endereco) {
                $enderecoData = (array) $endereco;
                $enderecoData['funcionario_id'] = $enderecoData['entidade_id'];
                unset($enderecoData['entidade_id']);
                DB::table('funcionario_endereco')->insert($enderecoData);
            }
        }

        // Migrar Transportadoras (tipo_relacionamento = 4)
        $transportadoras = DB::table('entidade')
            ->where('tipo_relacionamento', 4)
            ->get();

        foreach ($transportadoras as $transportadora) {
            $transportadoraData = (array) $transportadora;
            unset($transportadoraData['tipo_relacionamento']);
            DB::table('transportadora')->insert($transportadoraData);

            // Migrar contatos
            $contatos = DB::table('entidade_contato')
                ->where('entidade_id', $transportadora->id)
                ->get();

            foreach ($contatos as $contato) {
                $contatoData = (array) $contato;
                $contatoData['transportadora_id'] = $contatoData['entidade_id'];
                unset($contatoData['entidade_id']);
                DB::table('transportadora_contato')->insert($contatoData);
            }

            // Migrar endereços
            $enderecos = DB::table('entidade_endereco')
                ->where('entidade_id', $transportadora->id)
                ->get();

            foreach ($enderecos as $endereco) {
                $enderecoData = (array) $endereco;
                $enderecoData['transportadora_id'] = $enderecoData['entidade_id'];
                unset($enderecoData['entidade_id']);
                DB::table('transportadora_endereco')->insert($enderecoData);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Limpar dados das novas tabelas
        DB::table('cliente_contato')->truncate();
        DB::table('cliente_endereco')->truncate();
        DB::table('cliente')->truncate();

        DB::table('fornecedor_contato')->truncate();
        DB::table('fornecedor_endereco')->truncate();
        DB::table('fornecedor')->truncate();

        DB::table('funcionario_contato')->truncate();
        DB::table('funcionario_endereco')->truncate();
        DB::table('funcionario')->truncate();

        DB::table('transportadora_contato')->truncate();
        DB::table('transportadora_endereco')->truncate();
        DB::table('transportadora')->truncate();
    }
};
