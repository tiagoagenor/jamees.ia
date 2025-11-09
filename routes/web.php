<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LandingController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

// Landing page
Route::get('/', [LandingController::class, 'index'])->name('landing');

// Demo page
Route::get('/demo', function () {
    return view('demo');
})->name('demo');

Route::get('/home', function () {
    if (Auth::check()) {
        $user = Auth::user();
        if ($user instanceof \App\Models\Usuario) {
            $empresaPrincipal = $user->empresas()->wherePivot('principal', 1)->first();
            if ($empresaPrincipal) {
                $planoAtual = $empresaPrincipal->getPlanoAtual();
                if (!$planoAtual) {
                    return redirect()->route('planos.index');
                }
                if ($planoAtual->isExpirado()) {
                    return redirect()->route('planos.index');
                }
                // Se está em teste ou tem plano ativo, ir para dashboard
                return redirect()->route('dashboard');
            }
        }
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

// Rota de teste sem middleware
Route::get('/test', function () {
    return 'Teste funcionando! Usuário: ' . (Auth::check() ? Auth::user()->nome : 'Não logado');
});

// Rota de informações do PHP (protegida por autenticação)
Route::get('/phpinfo', function () {
    if (!Auth::check()) {
        return redirect()->route('login');
    }
    return view('phpinfo');
})->middleware('auth')->name('phpinfo');

// Rota de login manual para teste
Route::get('/login-manual', function () {
    $user = App\Models\Usuario::where('email', 'maria@teste.com')->first();
    if ($user) {
        Auth::login($user);
        return 'Login realizado com sucesso! Usuário: ' . $user->nome . ' | Admin: ' . ($user->isAdmin() ? 'Sim' : 'Não');
    }
    return 'Usuário não encontrado';
});

// Rota de API para registro (sem CSRF)
Route::post('/api/register', [App\Http\Controllers\Api\RegisterController::class, 'register'])->withoutMiddleware(['web']);

// Rotas básicas autenticadas (perfil, troca de empresa)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rota para trocar empresa
    Route::post('/switch-company', [App\Http\Controllers\CompanySwitchController::class, 'switchCompany'])->name('switch.company');

    // Rotas de planos (sempre acessíveis)
    Route::get('/planos', [App\Http\Controllers\PlanoController::class, 'index'])->name('planos.index');
    Route::get('/planos/{plano}', [App\Http\Controllers\PlanoController::class, 'show'])->name('planos.show');
    Route::post('/planos/{plano}/ativar', [App\Http\Controllers\PlanoController::class, 'ativar'])->name('planos.ativar');
    Route::post('/planos/cancelar', [App\Http\Controllers\PlanoController::class, 'cancelar'])->name('planos.cancelar');
    Route::get('/planos/historico', [App\Http\Controllers\PlanoController::class, 'historico'])->name('planos.historico');
    Route::get('/api/plano-info', [App\Http\Controllers\PlanoController::class, 'info'])->name('planos.info');
});

// Rotas protegidas por plano ativo
Route::middleware(['auth', 'plano.ativo'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Rotas de usuários
    Route::resource('usuarios', App\Http\Controllers\UsuarioController::class)
        ->middleware('permission:usuarios,listar');

    // Rotas de empresas
    Route::resource('empresas', App\Http\Controllers\EmpresaController::class)
        ->middleware('permission:empresas,listar');

    // Rotas de grupos
    Route::resource('grupos', App\Http\Controllers\GrupoController::class)
        ->middleware('permission:grupos,listar');

    // Rotas de permissões
    Route::resource('permissoes', App\Http\Controllers\PermissaoController::class)
        ->middleware('permission:permissoes,listar');

    // Rotas de clientes
    Route::get('/clientes', [App\Http\Controllers\ClienteController::class, 'index'])->name('clientes.index');
    Route::get('/clientes/create', [App\Http\Controllers\ClienteController::class, 'create'])->name('clientes.create');
    Route::post('/clientes', [App\Http\Controllers\ClienteController::class, 'store'])->name('clientes.store');
    Route::get('/clientes/{cliente}', [App\Http\Controllers\ClienteController::class, 'show'])->name('clientes.show');
    Route::get('/clientes/{cliente}/edit', [App\Http\Controllers\ClienteController::class, 'edit'])->name('clientes.edit');
    Route::put('/clientes/{cliente}', [App\Http\Controllers\ClienteController::class, 'update'])->name('clientes.update');
    Route::delete('/clientes/{cliente}', [App\Http\Controllers\ClienteController::class, 'destroy'])->name('clientes.destroy');
    Route::patch('/clientes/{cliente}/toggle-status', [App\Http\Controllers\ClienteController::class, 'toggleStatus'])->name('clientes.toggle-status');

    // Rotas de fornecedores
    Route::get('/fornecedores', [App\Http\Controllers\FornecedorController::class, 'index'])->name('fornecedores.index');
    Route::get('/fornecedores/create', [App\Http\Controllers\FornecedorController::class, 'create'])->name('fornecedores.create');
    Route::post('/fornecedores', [App\Http\Controllers\FornecedorController::class, 'store'])->name('fornecedores.store');
    Route::get('/fornecedores/{fornecedor}', [App\Http\Controllers\FornecedorController::class, 'show'])->name('fornecedores.show');
    Route::get('/fornecedores/{fornecedor}/edit', [App\Http\Controllers\FornecedorController::class, 'edit'])->name('fornecedores.edit');
    Route::put('/fornecedores/{fornecedor}', [App\Http\Controllers\FornecedorController::class, 'update'])->name('fornecedores.update');
    Route::delete('/fornecedores/{fornecedor}', [App\Http\Controllers\FornecedorController::class, 'destroy'])->name('fornecedores.destroy');
    Route::patch('/fornecedores/{fornecedor}/toggle-status', [App\Http\Controllers\FornecedorController::class, 'toggleStatus'])->name('fornecedores.toggle-status');

    // Rotas de funcionários
    Route::get('/funcionarios', [App\Http\Controllers\FuncionarioController::class, 'index'])->name('funcionarios.index');
    Route::get('/funcionarios/create', [App\Http\Controllers\FuncionarioController::class, 'create'])->name('funcionarios.create');
    Route::post('/funcionarios', [App\Http\Controllers\FuncionarioController::class, 'store'])->name('funcionarios.store');
    Route::get('/funcionarios/{funcionario}', [App\Http\Controllers\FuncionarioController::class, 'show'])->name('funcionarios.show');
    Route::get('/funcionarios/{funcionario}/edit', [App\Http\Controllers\FuncionarioController::class, 'edit'])->name('funcionarios.edit');
    Route::put('/funcionarios/{funcionario}', [App\Http\Controllers\FuncionarioController::class, 'update'])->name('funcionarios.update');
    Route::delete('/funcionarios/{funcionario}', [App\Http\Controllers\FuncionarioController::class, 'destroy'])->name('funcionarios.destroy');
    Route::patch('/funcionarios/{funcionario}/toggle-status', [App\Http\Controllers\FuncionarioController::class, 'toggleStatus'])->name('funcionarios.toggle-status');

    // Rotas de transportadoras
    Route::get('/transportadoras', [App\Http\Controllers\TransportadoraController::class, 'index'])->name('transportadoras.index');
    Route::get('/transportadoras/create', [App\Http\Controllers\TransportadoraController::class, 'create'])->name('transportadoras.create');
    Route::post('/transportadoras', [App\Http\Controllers\TransportadoraController::class, 'store'])->name('transportadoras.store');
    Route::get('/transportadoras/{transportadora}', [App\Http\Controllers\TransportadoraController::class, 'show'])->name('transportadoras.show');
    Route::get('/transportadoras/{transportadora}/edit', [App\Http\Controllers\TransportadoraController::class, 'edit'])->name('transportadoras.edit');
    Route::put('/transportadoras/{transportadora}', [App\Http\Controllers\TransportadoraController::class, 'update'])->name('transportadoras.update');
    Route::delete('/transportadoras/{transportadora}', [App\Http\Controllers\TransportadoraController::class, 'destroy'])->name('transportadoras.destroy');
    Route::patch('/transportadoras/{transportadora}/toggle-status', [App\Http\Controllers\TransportadoraController::class, 'toggleStatus'])->name('transportadoras.toggle-status');

    // Rotas para contatos das entidades
    Route::post('/entidades/{entidade}/contatos', [App\Http\Controllers\EntidadeController::class, 'storeContato'])->name('entidades.contatos.store');
    Route::put('/entidades/{entidade}/contatos/{contato}', [App\Http\Controllers\EntidadeController::class, 'updateContato'])->name('entidades.contatos.update');
    Route::delete('/entidades/{entidade}/contatos/{contato}', [App\Http\Controllers\EntidadeController::class, 'destroyContato'])->name('entidades.contatos.destroy');

    // Rotas para endereços das entidades
    Route::post('/entidades/{entidade}/enderecos', [App\Http\Controllers\EntidadeController::class, 'storeEndereco'])->name('entidades.enderecos.store');
    Route::put('/entidades/{entidade}/enderecos/{endereco}', [App\Http\Controllers\EntidadeController::class, 'updateEndereco'])->name('entidades.enderecos.update');
    Route::delete('/entidades/{entidade}/enderecos/{endereco}', [App\Http\Controllers\EntidadeController::class, 'destroyEndereco'])->name('entidades.enderecos.destroy');

    // Rotas de DRE
    Route::get('/dre', [App\Http\Controllers\DreController::class, 'index'])->name('dre.index');
    Route::get('/dre/create', [App\Http\Controllers\DreController::class, 'create'])->name('dre.create');
    Route::post('/dre', [App\Http\Controllers\DreController::class, 'store'])->name('dre.store');
    Route::get('/dre/{dre}', [App\Http\Controllers\DreController::class, 'show'])->name('dre.show');
    Route::get('/dre/{dre}/edit', [App\Http\Controllers\DreController::class, 'edit'])->name('dre.edit');
    Route::put('/dre/{dre}', [App\Http\Controllers\DreController::class, 'update'])->name('dre.update');
    Route::delete('/dre/{dre}', [App\Http\Controllers\DreController::class, 'destroy'])->name('dre.destroy');
    Route::patch('/dre/{dre}/toggle-status', [App\Http\Controllers\DreController::class, 'toggleStatus'])->name('dre.toggle-status');

    // Rotas de Contas Bancárias
    Route::get('/contas-bancarias', [App\Http\Controllers\ContaEmpresaController::class, 'index'])->name('conta-empresa.index');
    Route::get('/contas-bancarias/create', [App\Http\Controllers\ContaEmpresaController::class, 'create'])->name('conta-empresa.create');
    Route::post('/contas-bancarias', [App\Http\Controllers\ContaEmpresaController::class, 'store'])->name('conta-empresa.store');
    Route::get('/contas-bancarias/{contaEmpresa}', [App\Http\Controllers\ContaEmpresaController::class, 'show'])->name('conta-empresa.show');
    Route::get('/contas-bancarias/{contaEmpresa}/edit', [App\Http\Controllers\ContaEmpresaController::class, 'edit'])->name('conta-empresa.edit');
    Route::put('/contas-bancarias/{contaEmpresa}', [App\Http\Controllers\ContaEmpresaController::class, 'update'])->name('conta-empresa.update');
    Route::delete('/contas-bancarias/{contaEmpresa}', [App\Http\Controllers\ContaEmpresaController::class, 'destroy'])->name('conta-empresa.destroy');
    Route::patch('/contas-bancarias/{contaEmpresa}/toggle-status', [App\Http\Controllers\ContaEmpresaController::class, 'toggleStatus'])->name('conta-empresa.toggle-status');

    // Rotas de Formas de Pagamento
    Route::get('/formas-pagamento', [App\Http\Controllers\FormaPagamentoController::class, 'index'])->name('forma-pagamento.index')->middleware('permission:formas-pagamento,listar');
    Route::get('/formas-pagamento/create', [App\Http\Controllers\FormaPagamentoController::class, 'create'])->name('forma-pagamento.create')->middleware('permission:formas-pagamento,criar');
    Route::post('/formas-pagamento', [App\Http\Controllers\FormaPagamentoController::class, 'store'])->name('forma-pagamento.store')->middleware('permission:formas-pagamento,criar');
    Route::get('/formas-pagamento/{formaPagamento}', [App\Http\Controllers\FormaPagamentoController::class, 'show'])->name('forma-pagamento.show')->middleware('permission:formas-pagamento,visualizar');
    Route::get('/formas-pagamento/{formaPagamento}/edit', [App\Http\Controllers\FormaPagamentoController::class, 'edit'])->name('forma-pagamento.edit')->middleware('permission:formas-pagamento,editar');
    Route::put('/formas-pagamento/{formaPagamento}', [App\Http\Controllers\FormaPagamentoController::class, 'update'])->name('forma-pagamento.update')->middleware('permission:formas-pagamento,editar');
    Route::delete('/formas-pagamento/{formaPagamento}', [App\Http\Controllers\FormaPagamentoController::class, 'destroy'])->name('forma-pagamento.destroy')->middleware('permission:formas-pagamento,deletar');
    Route::patch('/formas-pagamento/{formaPagamento}/toggle-disponibilidade', [App\Http\Controllers\FormaPagamentoController::class, 'toggleDisponibilidade'])->name('forma-pagamento.toggle-disponibilidade')->middleware('permission:formas-pagamento,editar');

    // Rotas de Plano de Conta
    Route::get('/plano-conta', [App\Http\Controllers\PlanoContaController::class, 'index'])->name('plano-conta.index')->middleware('permission:plano-conta,listar');
    Route::get('/plano-conta/create', [App\Http\Controllers\PlanoContaController::class, 'create'])->name('plano-conta.create')->middleware('permission:plano-conta,criar');
    Route::post('/plano-conta', [App\Http\Controllers\PlanoContaController::class, 'store'])->name('plano-conta.store')->middleware('permission:plano-conta,criar');
    Route::get('/plano-conta/{planoConta}', [App\Http\Controllers\PlanoContaController::class, 'show'])->name('plano-conta.show')->middleware('permission:plano-conta,visualizar');
    Route::get('/plano-conta/{planoConta}/edit', [App\Http\Controllers\PlanoContaController::class, 'edit'])->name('plano-conta.edit')->middleware('permission:plano-conta,editar');
    Route::put('/plano-conta/{planoConta}', [App\Http\Controllers\PlanoContaController::class, 'update'])->name('plano-conta.update')->middleware('permission:plano-conta,editar');
    Route::delete('/plano-conta/{planoConta}', [App\Http\Controllers\PlanoContaController::class, 'destroy'])->name('plano-conta.destroy')->middleware('permission:plano-conta,deletar');

    // Rotas de Centro de Custo
    Route::get('/centro-custo', [App\Http\Controllers\CentroCustoController::class, 'index'])->name('centro-custo.index')->middleware('permission:central-custo,listar');
    Route::get('/centro-custo/create', [App\Http\Controllers\CentroCustoController::class, 'create'])->name('centro-custo.create')->middleware('permission:central-custo,criar');
    Route::post('/centro-custo', [App\Http\Controllers\CentroCustoController::class, 'store'])->name('centro-custo.store')->middleware('permission:central-custo,criar');
    Route::get('/centro-custo/{centroCusto}', [App\Http\Controllers\CentroCustoController::class, 'show'])->name('centro-custo.show')->middleware('permission:central-custo,visualizar');
    Route::get('/centro-custo/{centroCusto}/edit', [App\Http\Controllers\CentroCustoController::class, 'edit'])->name('centro-custo.edit')->middleware('permission:central-custo,editar');
    Route::put('/centro-custo/{centroCusto}', [App\Http\Controllers\CentroCustoController::class, 'update'])->name('centro-custo.update')->middleware('permission:central-custo,editar');
    Route::delete('/centro-custo/{centroCusto}', [App\Http\Controllers\CentroCustoController::class, 'destroy'])->name('centro-custo.destroy')->middleware('permission:central-custo,deletar');
    Route::patch('/centro-custo/{centroCusto}/toggle-status', [App\Http\Controllers\CentroCustoController::class, 'toggleStatus'])->name('centro-custo.toggle-status')->middleware('permission:central-custo,editar');

    // Dashboard Financeiro
    Route::get('/dashboard-financeiro', [App\Http\Controllers\DashboardFinanceiroController::class, 'index'])->name('dashboard.financeiro')->middleware('permission:movimentacao,listar');

    // Rotas de Movimentação Financeira - Contas a Pagar
    Route::get('/contas-a-pagar', [App\Http\Controllers\MovimentacaoController::class, 'index'])->name('contas-a-pagar.index')->middleware('permission:movimentacao,listar')->defaults('tipo', 1);
    Route::get('/contas-a-pagar/create', [App\Http\Controllers\MovimentacaoController::class, 'create'])->name('contas-a-pagar.create')->middleware('permission:movimentacao,criar')->defaults('tipo', 1);
    Route::post('/contas-a-pagar', [App\Http\Controllers\MovimentacaoController::class, 'store'])->name('contas-a-pagar.store')->middleware('permission:movimentacao,criar')->defaults('tipo', 1);
    Route::post('/movimentacao/gerar-parcelas', [App\Http\Controllers\MovimentacaoController::class, 'gerarParcelas'])->name('movimentacao.gerar-parcelas')->middleware('permission:movimentacao,criar');
    Route::get('/contas-a-pagar/{movimentacao}', [App\Http\Controllers\MovimentacaoController::class, 'show'])->name('contas-a-pagar.show')->middleware('permission:movimentacao,visualizar')->defaults('tipo', 1);
    Route::get('/contas-a-pagar/{movimentacao}/edit', [App\Http\Controllers\MovimentacaoController::class, 'edit'])->name('contas-a-pagar.edit')->middleware('permission:movimentacao,editar')->defaults('tipo', 1);
    Route::put('/contas-a-pagar/{movimentacao}', [App\Http\Controllers\MovimentacaoController::class, 'update'])->name('contas-a-pagar.update')->middleware('permission:movimentacao,editar')->defaults('tipo', 1);
    Route::delete('/contas-a-pagar/{movimentacao}', [App\Http\Controllers\MovimentacaoController::class, 'destroy'])->name('contas-a-pagar.destroy')->middleware('permission:movimentacao,deletar')->defaults('tipo', 1);
    Route::patch('/contas-a-pagar/{movimentacao}/toggle-status', [App\Http\Controllers\MovimentacaoController::class, 'toggleStatus'])->name('contas-a-pagar.toggle-status')->middleware('permission:movimentacao,editar')->defaults('tipo', 1);
    Route::get('/contas-a-pagar/{movimentacao}/gerar-jwt-confirmacao', [App\Http\Controllers\MovimentacaoController::class, 'gerarJwtConfirmacao'])->name('contas-a-pagar.gerar-jwt-confirmacao')->middleware('permission:movimentacao,editar')->defaults('tipo', 1);
    Route::post('/contas-a-pagar/{movimentacao}/confirmar-pagamento', [App\Http\Controllers\MovimentacaoController::class, 'confirmarPagamento'])->name('contas-a-pagar.confirmar-pagamento')->defaults('tipo', 1);
    Route::post('/contas-a-pagar/{movimentacao}/marcar-pendente', [App\Http\Controllers\MovimentacaoController::class, 'marcarPendente'])->name('contas-a-pagar.marcar-pendente')->middleware('permission:movimentacao,editar')->defaults('tipo', 1);
    Route::post('/contas-a-pagar/{movimentacao}/reativar', [App\Http\Controllers\MovimentacaoController::class, 'reativar'])->name('contas-a-pagar.reativar')->middleware('permission:movimentacao,editar')->defaults('tipo', 1);

    // Rotas de Movimentação Financeira - Contas a Receber
    Route::get('/contas-a-receber', [App\Http\Controllers\MovimentacaoController::class, 'index'])->name('contas-a-receber.index')->middleware('permission:movimentacao,listar')->defaults('tipo', 2);
    Route::get('/contas-a-receber/create', [App\Http\Controllers\MovimentacaoController::class, 'create'])->name('contas-a-receber.create')->middleware('permission:movimentacao,criar')->defaults('tipo', 2);
    Route::post('/contas-a-receber', [App\Http\Controllers\MovimentacaoController::class, 'store'])->name('contas-a-receber.store')->middleware('permission:movimentacao,criar')->defaults('tipo', 2);
    Route::get('/contas-a-receber/{movimentacao}', [App\Http\Controllers\MovimentacaoController::class, 'show'])->name('contas-a-receber.show')->middleware('permission:movimentacao,visualizar')->defaults('tipo', 2);
    Route::get('/contas-a-receber/{movimentacao}/edit', [App\Http\Controllers\MovimentacaoController::class, 'edit'])->name('contas-a-receber.edit')->middleware('permission:movimentacao,editar')->defaults('tipo', 2);
    Route::put('/contas-a-receber/{movimentacao}', [App\Http\Controllers\MovimentacaoController::class, 'update'])->name('contas-a-receber.update')->middleware('permission:movimentacao,editar')->defaults('tipo', 2);
    Route::delete('/contas-a-receber/{movimentacao}', [App\Http\Controllers\MovimentacaoController::class, 'destroy'])->name('contas-a-receber.destroy')->middleware('permission:movimentacao,deletar')->defaults('tipo', 2);
    Route::patch('/contas-a-receber/{movimentacao}/toggle-status', [App\Http\Controllers\MovimentacaoController::class, 'toggleStatus'])->name('contas-a-receber.toggle-status')->middleware('permission:movimentacao,editar')->defaults('tipo', 2);
    Route::get('/contas-a-receber/{movimentacao}/gerar-jwt-confirmacao', [App\Http\Controllers\MovimentacaoController::class, 'gerarJwtConfirmacao'])->name('contas-a-receber.gerar-jwt-confirmacao')->middleware('permission:movimentacao,editar')->defaults('tipo', 2);
    Route::post('/contas-a-receber/{movimentacao}/confirmar-pagamento', [App\Http\Controllers\MovimentacaoController::class, 'confirmarPagamento'])->name('contas-a-receber.confirmar-pagamento')->defaults('tipo', 2);
    Route::post('/contas-a-receber/{movimentacao}/marcar-pendente', [App\Http\Controllers\MovimentacaoController::class, 'marcarPendente'])->name('contas-a-receber.marcar-pendente')->middleware('permission:movimentacao,editar')->defaults('tipo', 2);
    Route::post('/contas-a-receber/{movimentacao}/reativar', [App\Http\Controllers\MovimentacaoController::class, 'reativar'])->name('contas-a-receber.reativar')->middleware('permission:movimentacao,editar')->defaults('tipo', 2);

    // Rotas de Histórico de Alterações (Audit Log)
    Route::get('/historico-alteracoes', [App\Http\Controllers\AuditLogController::class, 'index'])->name('audit.index')->middleware('permission:audit,listar');
    Route::get('/historico-alteracoes/{auditLog}', [App\Http\Controllers\AuditLogController::class, 'show'])->name('audit.show')->middleware('permission:audit,visualizar');
    Route::get('/historico-alteracoes/stats', [App\Http\Controllers\AuditLogController::class, 'stats'])->name('audit.stats')->middleware('permission:audit,visualizar');
    Route::get('/historico-alteracoes/usuario/{userId}', [App\Http\Controllers\AuditLogController::class, 'byUser'])->name('audit.by-user')->middleware('permission:audit,listar');
    Route::get('/historico-alteracoes/modelo/{modelType}/{modelId?}', [App\Http\Controllers\AuditLogController::class, 'byModel'])->name('audit.by-model')->middleware('permission:audit,listar');

    // Rotas de Meus Dados
    Route::get('/meus-dados', [App\Http\Controllers\MeusDadosController::class, 'index'])->name('meus-dados.index');
    Route::put('/meus-dados', [App\Http\Controllers\MeusDadosController::class, 'update'])->name('meus-dados.update');

    // Rotas de Configurações Gerais
    Route::get('/configuracoes/gerais', [App\Http\Controllers\ConfiguracoesGeraisController::class, 'index'])->name('configuracoes.gerais.index');
    Route::post('/configuracoes/gerais', [App\Http\Controllers\ConfiguracoesGeraisController::class, 'update'])->name('configuracoes.gerais.update');

    // Loteamento - Empreendimentos
    Route::resource('empreendimentos', App\Http\Controllers\EmpreendimentoController::class);
    Route::get('/empreendimentos/{empreendimento}/mapa', [App\Http\Controllers\EmpreendimentoController::class, 'mapa'])->name('empreendimentos.mapa');
    Route::post('/empreendimentos/{empreendimento}/salvar-posicoes-pinos', [App\Http\Controllers\EmpreendimentoController::class, 'salvarPosicoesPinos'])->name('empreendimentos.salvar-posicoes-pinos');

    // Loteamento - Mapa (visualização)
    Route::get('/loteamentos/mapa', [App\Http\Controllers\EmpreendimentoController::class, 'mapaIndex'])->name('loteamentos.mapa.index');
    Route::get('/loteamentos/{empreendimento}/mapa', [App\Http\Controllers\EmpreendimentoController::class, 'mapaView'])->name('loteamentos.mapa.view');
    Route::post('/loteamentos/{empreendimento}/salvar-posicoes-pinos', [App\Http\Controllers\EmpreendimentoController::class, 'salvarPosicoesPinos'])->name('loteamentos.salvar-posicoes-pinos');

    // Loteamento - Vender e Reservar
    Route::get('/loteamentos/{empreendimento}/lote/{lote}/vender', [App\Http\Controllers\EmpreendimentoController::class, 'venderLote'])->name('loteamentos.lote.vender');
    Route::post('/loteamentos/{empreendimento}/lote/{lote}/gerar-parcelas', [App\Http\Controllers\EmpreendimentoController::class, 'gerarParcelasVenda'])->name('loteamentos.lote.gerar-parcelas');
    Route::get('/loteamentos/{empreendimento}/lote/{lote}/reservar', [App\Http\Controllers\EmpreendimentoController::class, 'reservarLote'])->name('loteamentos.lote.reservar');
    Route::post('/loteamentos/{empreendimento}/lote/{lote}/reservar', [App\Http\Controllers\EmpreendimentoController::class, 'salvarReserva'])->name('loteamentos.lote.salvar-reserva');
    
    // Loteamento - Comentários
    Route::get('/loteamentos/{empreendimento}/lote/{lote}/comentarios', [App\Http\Controllers\EmpreendimentoController::class, 'comentariosLote'])->name('loteamentos.lote.comentarios');
    Route::get('/loteamentos/{empreendimento}/lote/{lote}/comentarios/cliente/{cliente}', [App\Http\Controllers\EmpreendimentoController::class, 'comentariosLotePorCliente'])->name('loteamentos.lote.comentarios.cliente');
    Route::post('/loteamentos/{empreendimento}/lote/{lote}/comentarios', [App\Http\Controllers\EmpreendimentoController::class, 'salvarComentario'])->name('loteamentos.lote.salvar-comentario');

    // Loteamento - Status de Lotes
    Route::resource('lote-status', App\Http\Controllers\LoteStatusController::class);

    // Loteamento - Quadras (dentro do empreendimento)
    Route::get('/empreendimentos/{empreendimento}/quadras', [App\Http\Controllers\QuadraController::class, 'index'])->name('quadras.index');
    Route::post('/empreendimentos/{empreendimento}/quadras', [App\Http\Controllers\QuadraController::class, 'store'])->name('quadras.store');
    Route::delete('/quadras/{quadra}', [App\Http\Controllers\QuadraController::class, 'destroy'])->name('quadras.destroy');

    // Loteamento - Lotes (dentro do empreendimento)
    Route::get('/empreendimentos/{empreendimento}/lotes', [App\Http\Controllers\LoteController::class, 'index'])->name('lotes.index');
    Route::get('/empreendimentos/{empreendimento}/lotes/create', [App\Http\Controllers\LoteController::class, 'create'])->name('lotes.create');
    Route::post('/empreendimentos/{empreendimento}/lotes', [App\Http\Controllers\LoteController::class, 'store'])->name('lotes.store');
    Route::get('/empreendimentos/{empreendimento}/lotes/import', [App\Http\Controllers\LoteController::class, 'import'])->name('lotes.import');
    Route::get('/empreendimentos/{empreendimento}/lotes/download-exemplo-csv', [App\Http\Controllers\LoteController::class, 'downloadExemploCsv'])->name('lotes.download-exemplo-csv');
    Route::post('/empreendimentos/{empreendimento}/lotes/import-csv', [App\Http\Controllers\LoteController::class, 'importCsv'])->name('lotes.import-csv');
    Route::delete('/empreendimentos/{empreendimento}/lotes/destroy-massa', [App\Http\Controllers\LoteController::class, 'destroyMassa'])->name('lotes.destroy-massa');
    Route::get('/lotes/{lote}', [App\Http\Controllers\LoteController::class, 'show'])->name('lotes.show');
    Route::get('/lotes/{lote}/edit', [App\Http\Controllers\LoteController::class, 'edit'])->name('lotes.edit');
    Route::put('/lotes/{lote}', [App\Http\Controllers\LoteController::class, 'update'])->name('lotes.update');
    Route::delete('/lotes/{lote}', [App\Http\Controllers\LoteController::class, 'destroy'])->name('lotes.destroy');

});


// Rotas de recuperação de senha
Route::get('/recuperar-senha', [App\Http\Controllers\Auth\PasswordResetController::class, 'showForgotPasswordForm'])->name('forgot-password');
Route::post('/recuperar-senha', [App\Http\Controllers\Auth\PasswordResetController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password', [App\Http\Controllers\Auth\PasswordResetController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [App\Http\Controllers\Auth\PasswordResetController::class, 'reset'])->name('password.update');
Route::post('/password/check-token', [App\Http\Controllers\Auth\PasswordResetController::class, 'checkTokenStatus'])->name('password.check-token');

require __DIR__.'/auth.php';
