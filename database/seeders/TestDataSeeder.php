<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\Usuario;
use App\Models\Empresa;
use App\Models\Plano;
use App\Models\EmpresaPlano;
use App\Models\Grupo;
use App\Models\Permissao;
use App\Models\Cliente;
use App\Models\Fornecedor;
use App\Models\Funcionario;
use App\Models\Transportadora;
use App\Models\Movimentacao;
use App\Models\PlanoConta;
use App\Models\FormaPagamento;
use App\Models\ContaEmpresa;
use App\Models\Banco;
use App\Models\Whitelabel;
use App\Enums\EmpresaTipoEnum;
use App\Enums\EmpresaStatusEnum;
use App\Enums\UsuarioStatusEnum;
use App\Enums\PlanoTipoEnum;
use App\Enums\PlanoStatusEnum;
use App\Enums\PlanoPeriodoEnum;
use App\Enums\MovimentacaoTipoEnum;
use App\Enums\MovimentacaoSituacaoEnum;
use App\Enums\EntidadeTipoEnum;
use App\Enums\ContaTipoEnum;
use App\Services\PlanoService;
use App\Services\Dre\CriarDreService;
use App\Services\FormaPagamento\CriarFormasPagamentoService;
use App\Services\PlanoConta\CriarPlanoContaService;
use Carbon\Carbon;
use Faker\Factory as Faker;

class TestDataSeeder extends Seeder
{
    protected $faker;

    public function __construct()
    {
        $this->faker = Faker::create('pt_BR');
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Iniciando criação de dados de teste...');

        // Obter whitelabel padrão ou criar um
        $whitelabel = Whitelabel::first();
        if (!$whitelabel) {
            $whitelabel = Whitelabel::create([
                'id' => Str::uuid(),
                'nome' => $this->faker->company(),
                'dominio' => $this->faker->domainName(),
                'ativo' => true,
            ]);
        }

        // Obter planos existentes (exceto Personalizado)
        $planos = Plano::where('tipo', '!=', PlanoTipoEnum::PERSONALIZADO)->get();

        if ($planos->isEmpty()) {
            $this->command->error('Nenhum plano encontrado. Execute o PlanoSeeder primeiro.');
            return;
        }

        // Criar 10 empresas
        $empresas = [];
        for ($i = 1; $i <= 10; $i++) {
            $tipo = $i % 2 == 0 ? EmpresaTipoEnum::PF : EmpresaTipoEnum::PJ;
            $nomeFantasia = $this->faker->company();
            $razaoSocial = $tipo === EmpresaTipoEnum::PJ
                ? $nomeFantasia . ' ' . $this->faker->randomElement(['LTDA', 'EIRELI', 'ME'])
                : null;

            $empresa = Empresa::create([
                'id' => Str::uuid(),
                'whitelabel_id' => $whitelabel->id,
                'nome_fantasia' => $nomeFantasia,
                'razao_social' => $razaoSocial,
                'cnpj' => $tipo === EmpresaTipoEnum::PJ ? $this->faker->cnpj(false) : null,
                'cpf' => $tipo === EmpresaTipoEnum::PF ? $this->faker->cpf(false) : null,
                'tipo' => $tipo,
                'status' => EmpresaStatusEnum::ATIVA,
                'principal' => $i == 1 ? 1 : 0,
                'criado_em' => $this->faker->dateTimeBetween('-1 year', 'now'),
                'atualizado_em' => now(),
            ]);

            // Criar estrutura padrão para cada empresa
            $dreService = new CriarDreService();
            $dreService->criar($empresa->id);

            $formasPagamentoService = new CriarFormasPagamentoService();
            $formasPagamentoService->criar($empresa->id);

            $planoContaService = new CriarPlanoContaService();
            $planoContaService->criar($empresa->id);

            // Criar conta bancária padrão
            $banco = Banco::first();
            if ($banco) {
                ContaEmpresa::create([
                    'id' => Str::uuid(),
                    'banco_id' => $banco->id,
                    'empresa_id' => $empresa->id,
                    'tipo' => ContaTipoEnum::CORRENTE,
                    'nome' => "Conta Principal - " . $empresa->nome_fantasia,
                    'saldo_inicial' => $this->faker->randomFloat(2, 1000, 50000),
                    'status' => true,
                    'criado_em' => now(),
                    'atualizado_em' => now(),
                ]);
            }

            $empresas[] = $empresa;
        }

        // Criar 10 usuários
        $usuarios = [];
        for ($i = 1; $i <= 10; $i++) {
            $usuario = Usuario::create([
                'id' => Str::uuid(),
                'nome' => $this->faker->name(),
                'email' => $this->faker->unique()->safeEmail(),
                'senha' => Hash::make('123456'),
                'status' => UsuarioStatusEnum::ATIVO,
                'principal' => $i == 1,
                'criado_em' => $this->faker->dateTimeBetween('-1 year', 'now'),
                'atualizado_em' => now(),
            ]);

            // Vincular usuário à empresa correspondente
            $empresaIndex = ($i - 1) % count($empresas);
            $empresa = $empresas[$empresaIndex];

            $usuario->empresas()->attach($empresa->id, [
                'principal' => $i == 1 ? 1 : 0,
                'status' => UsuarioStatusEnum::ATIVO,
                'criado_em' => now(),
                'atualizado_em' => now(),
            ]);

            // Criar grupos variados para cada empresa
            $gruposNomes = ['Administrativo', 'Vendedor', 'Financeiro', 'Operacional', 'Gerente'];
            $grupoNome = $gruposNomes[($i - 1) % count($gruposNomes)];

            $grupo = Grupo::create([
                'id' => Str::uuid(),
                'empresa_id' => $empresa->id,
                'nome' => "{$grupoNome} - " . $empresa->nome_fantasia,
                'descricao' => $this->faker->sentence(),
                'administrativo' => $grupoNome === 'Administrativo',
                'ativo' => true,
            ]);

            // Vincular todas as permissões ao grupo administrativo, algumas aos outros
            $todasPermissoes = Permissao::ativas()->get();
            if ($grupo->administrativo) {
                $grupo->permissoes()->sync(
                    $todasPermissoes->mapWithKeys(function ($permissao) {
                        return [$permissao->id => ['concedida' => true]];
                    })
                );
            } else {
                // Vincular algumas permissões aleatórias
                $permissoesAleatorias = $todasPermissoes->random(rand(5, 15));
                $grupo->permissoes()->sync(
                    $permissoesAleatorias->mapWithKeys(function ($permissao) {
                        return [$permissao->id => ['concedida' => true]];
                    })
                );
            }

            // Vincular usuário ao grupo
            $usuario->grupos()->sync([$grupo->id]);

            $usuarios[] = $usuario;
        }

        // Criar um usuário para cada plano (exceto Personalizado)
        $planoService = new PlanoService();
        $planosDisponiveis = $planos->where('tipo', '!=', PlanoTipoEnum::TESTE)->take(3); // Base, Premium, Master

        foreach ($planosDisponiveis as $index => $plano) {
            $empresa = $empresas[$index % count($empresas)];

            // Ativar plano para a empresa
            $periodos = [PlanoPeriodoEnum::MENSAL, PlanoPeriodoEnum::TRIMESTRAL, PlanoPeriodoEnum::ANUAL];
            $periodo = $periodos[$index % count($periodos)];

            $planoService->ativarPlano($empresa, $plano, $periodo);
        }

        // Criar plano de teste para algumas empresas
        $planoTeste = $planos->where('tipo', PlanoTipoEnum::TESTE)->first();
        if ($planoTeste) {
            for ($i = 0; $i < 3; $i++) {
                $empresa = $empresas[$i];
                $planoService->ativarTesteGratuito($empresa);
            }
        }

        // Criar entidades (Clientes, Fornecedores, Funcionários, Transportadoras)
        $this->criarEntidades($empresas);

        // Criar movimentações (Contas a Pagar e Receber)
        $this->criarMovimentacoes($empresas);

        $this->command->info('Dados de teste criados com sucesso!');
    }

    private function criarEntidades($empresas)
    {
        $this->command->info('Criando entidades...');

        foreach ($empresas as $empresa) {
            // Criar 20 Clientes
            for ($i = 1; $i <= 20; $i++) {
                $tipoPessoa = $this->faker->randomElement([1, 2]); // 1 = PF, 2 = PJ
                $nomeFantasia = $tipoPessoa == 2 ? $this->faker->company() : null;
                $razaoSocial = $tipoPessoa == 2 ? $nomeFantasia . ' ' . $this->faker->randomElement(['LTDA', 'EIRELI', 'ME']) : null;

                Cliente::create([
                    'id' => Str::uuid(),
                    'empresa_id' => $empresa->id,
                    'nome' => $tipoPessoa == 1 ? $this->faker->name() : null,
                    'nome_fantasia' => $nomeFantasia,
                    'razao_social' => $razaoSocial,
                    'documento' => $tipoPessoa == 1 ? $this->faker->cpf(false) : $this->faker->cnpj(false),
                    'email' => $this->faker->unique()->safeEmail(),
                    'telefone_comercial' => $this->faker->phoneNumber(),
                    'celular' => $this->faker->phoneNumber(),
                    'tipo_pessoa' => $tipoPessoa,
                    'status' => $this->faker->randomElement([0, 1]),
                    'nascimento' => $tipoPessoa == 1 ? $this->faker->dateTimeBetween('-80 years', '-18 years') : null,
                    'rg' => $tipoPessoa == 1 ? $this->faker->numerify('########') : null,
                ]);
            }

            // Criar 20 Fornecedores
            for ($i = 1; $i <= 20; $i++) {
                $tipoPessoa = $this->faker->randomElement([1, 2]);
                $nomeFantasia = $tipoPessoa == 2 ? $this->faker->company() : null;
                $razaoSocial = $tipoPessoa == 2 ? $nomeFantasia . ' ' . $this->faker->randomElement(['LTDA', 'EIRELI', 'ME']) : null;

                Fornecedor::create([
                    'id' => Str::uuid(),
                    'empresa_id' => $empresa->id,
                    'nome' => $tipoPessoa == 1 ? $this->faker->name() : null,
                    'nome_fantasia' => $nomeFantasia,
                    'razao_social' => $razaoSocial,
                    'documento' => $tipoPessoa == 1 ? $this->faker->cpf(false) : $this->faker->cnpj(false),
                    'email' => $this->faker->unique()->safeEmail(),
                    'telefone_comercial' => $this->faker->phoneNumber(),
                    'celular' => $this->faker->phoneNumber(),
                    'tipo_pessoa' => $tipoPessoa,
                    'status' => $this->faker->randomElement([0, 1]),
                    'nascimento' => $tipoPessoa == 1 ? $this->faker->dateTimeBetween('-80 years', '-18 years') : null,
                    'rg' => $tipoPessoa == 1 ? $this->faker->numerify('########') : null,
                ]);
            }

            // Criar 20 Funcionários
            for ($i = 1; $i <= 20; $i++) {
                $tipoPessoa = $this->faker->randomElement([1, 2]);
                $nomeFantasia = $tipoPessoa == 2 ? $this->faker->company() : null;
                $razaoSocial = $tipoPessoa == 2 ? $nomeFantasia . ' ' . $this->faker->randomElement(['LTDA', 'EIRELI', 'ME']) : null;

                Funcionario::create([
                    'id' => Str::uuid(),
                    'empresa_id' => $empresa->id,
                    'nome' => $tipoPessoa == 1 ? $this->faker->name() : null,
                    'nome_fantasia' => $nomeFantasia,
                    'razao_social' => $razaoSocial,
                    'documento' => $tipoPessoa == 1 ? $this->faker->cpf(false) : $this->faker->cnpj(false),
                    'email' => $this->faker->unique()->safeEmail(),
                    'telefone_comercial' => $this->faker->phoneNumber(),
                    'celular' => $this->faker->phoneNumber(),
                    'tipo_pessoa' => $tipoPessoa,
                    'status' => $this->faker->randomElement([0, 1]),
                    'nascimento' => $tipoPessoa == 1 ? $this->faker->dateTimeBetween('-80 years', '-18 years') : null,
                    'rg' => $tipoPessoa == 1 ? $this->faker->numerify('########') : null,
                ]);
            }

            // Criar 20 Transportadoras
            for ($i = 1; $i <= 20; $i++) {
                $tipoPessoa = $this->faker->randomElement([1, 2]);
                $nomeFantasia = $tipoPessoa == 2 ? $this->faker->company() : null;
                $razaoSocial = $tipoPessoa == 2 ? $nomeFantasia . ' ' . $this->faker->randomElement(['LTDA', 'EIRELI', 'ME']) : null;

                Transportadora::create([
                    'id' => Str::uuid(),
                    'empresa_id' => $empresa->id,
                    'nome' => $tipoPessoa == 1 ? $this->faker->name() : null,
                    'nome_fantasia' => $nomeFantasia,
                    'razao_social' => $razaoSocial,
                    'documento' => $tipoPessoa == 1 ? $this->faker->cpf(false) : $this->faker->cnpj(false),
                    'email' => $this->faker->unique()->safeEmail(),
                    'telefone_comercial' => $this->faker->phoneNumber(),
                    'celular' => $this->faker->phoneNumber(),
                    'tipo_pessoa' => $tipoPessoa,
                    'status' => $this->faker->randomElement([0, 1]),
                    'nascimento' => $tipoPessoa == 1 ? $this->faker->dateTimeBetween('-80 years', '-18 years') : null,
                    'rg' => $tipoPessoa == 1 ? $this->faker->numerify('########') : null,
                ]);
            }
        }
    }

    private function criarMovimentacoes($empresas)
    {
        $this->command->info('Criando movimentações...');

        foreach ($empresas as $empresa) {
            // Obter dados necessários
            $planoConta = PlanoConta::where('empresa_id', $empresa->id)->first();
            $formaPagamento = FormaPagamento::where('empresa_id', $empresa->id)->first();
            $contaEmpresa = ContaEmpresa::where('empresa_id', $empresa->id)->first();
            $clientes = Cliente::where('empresa_id', $empresa->id)->get();
            $fornecedores = Fornecedor::where('empresa_id', $empresa->id)->get();

            if (!$planoConta || !$formaPagamento || !$contaEmpresa) {
                $this->command->warn("Empresa {$empresa->nome_fantasia} não tem dados necessários para criar movimentações.");
                continue;
            }

            // Criar 100 Contas a Pagar
            for ($i = 1; $i <= 100; $i++) {
                $fornecedor = $fornecedores->random();
                $valor = $this->faker->randomFloat(2, 100, 10000);
                $situacao = $this->faker->randomElement([1, 2, 3, 4]); // 1=Pendente, 2=Paga, 3=Vencida, 4=Cancelada
                $vencimento = $this->faker->dateTimeBetween('-30 days', '+60 days');
                $juros = $situacao == 3 ? $this->faker->randomFloat(2, 10, 100) : null;
                $desconto = $this->faker->boolean(30) ? $this->faker->randomFloat(2, 5, 50) : null;
                $valorTotal = $valor + ($juros ?? 0) - ($desconto ?? 0);

                Movimentacao::create([
                    'id' => Str::uuid(),
                    'empresa_id' => $empresa->id,
                    'plano_conta_id' => $planoConta->id,
                    'forma_pagamento_id' => $formaPagamento->id,
                    'conta_empresa_id' => $contaEmpresa->id,
                    'situacao' => $situacao,
                    'tipo' => MovimentacaoTipoEnum::PAGAR,
                    'entidade_tipo' => EntidadeTipoEnum::FORNECEDOR,
                    'entidade_id' => $fornecedor->id,
                    'descricao' => $this->faker->sentence(4) . ' - ' . ($fornecedor->nome ?? $fornecedor->nome_fantasia),
                    'vencimento' => $vencimento,
                    'valor' => $valor,
                    'juros' => $juros,
                    'desconto' => $desconto,
                    'valor_total' => $valorTotal,
                    'data_compensacao' => $situacao == 2 ? $this->faker->dateTimeBetween($vencimento, $vencimento->format('Y-m-d') . ' +5 days') : null,
                    'observacao' => $this->faker->optional()->sentence(),
                    'criado_em' => $this->faker->dateTimeBetween('-1 year', 'now'),
                    'atualizado_em' => now(),
                ]);
            }

            // Criar 100 Contas a Receber
            for ($i = 1; $i <= 100; $i++) {
                $cliente = $clientes->random();
                $valor = $this->faker->randomFloat(2, 100, 10000);
                $situacao = $this->faker->randomElement([1, 2, 3, 4]);
                $vencimento = $this->faker->dateTimeBetween('-30 days', '+60 days');
                $juros = $situacao == 3 ? $this->faker->randomFloat(2, 10, 100) : null;
                $desconto = $this->faker->boolean(30) ? $this->faker->randomFloat(2, 5, 50) : null;
                $valorTotal = $valor + ($juros ?? 0) - ($desconto ?? 0);

                Movimentacao::create([
                    'id' => Str::uuid(),
                    'empresa_id' => $empresa->id,
                    'plano_conta_id' => $planoConta->id,
                    'forma_pagamento_id' => $formaPagamento->id,
                    'conta_empresa_id' => $contaEmpresa->id,
                    'situacao' => $situacao,
                    'tipo' => MovimentacaoTipoEnum::RECEBER,
                    'entidade_tipo' => EntidadeTipoEnum::CLIENTE,
                    'entidade_id' => $cliente->id,
                    'descricao' => $this->faker->sentence(4) . ' - ' . ($cliente->nome ?? $cliente->nome_fantasia),
                    'vencimento' => $vencimento,
                    'valor' => $valor,
                    'juros' => $juros,
                    'desconto' => $desconto,
                    'valor_total' => $valorTotal,
                    'data_compensacao' => $situacao == 2 ? $this->faker->dateTimeBetween($vencimento, $vencimento->format('Y-m-d') . ' +5 days') : null,
                    'observacao' => $this->faker->optional()->sentence(),
                    'criado_em' => $this->faker->dateTimeBetween('-1 year', 'now'),
                    'atualizado_em' => now(),
                ]);
            }
        }
    }

}
