<?php

namespace App\Http\Controllers;

use App\Enums\MovimentacaoSituacaoEnum;
use App\Enums\MovimentacaoTipoEnum;
use App\Helpers\PermissionHelper;
use App\Models\ContaEmpresa;
use App\Models\Movimentacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RelatorioController extends Controller
{
    /**
     * Exibe a página de relatórios de cadastros
     */
    public function cadastros()
    {
        return view('relatorios.cadastros');
    }

    /**
     * Exibe a página de detalhes de um relatório de cadastro específico
     */
    public function cadastrosDetalhes(Request $request, $tipo)
    {
        $tiposPermitidos = ['clientes', 'aniversariantes', 'funcionarios', 'fornecedores', 'transportadoras'];
        
        if (!in_array($tipo, $tiposPermitidos)) {
            abort(404);
        }

        $empresaPrincipal = PermissionHelper::getEmpresaPrincipal();
        if (!$empresaPrincipal) {
            abort(403, 'Empresa principal não encontrada.');
        }

        // Se for clientes, implementar lógica específica
        if ($tipo === 'clientes') {
            return $this->relatorioClientes($request, $empresaPrincipal);
        }

        // Se for funcionarios, implementar lógica específica
        if ($tipo === 'funcionarios') {
            return $this->relatorioFuncionarios($request, $empresaPrincipal);
        }

        // Se for fornecedores, implementar lógica específica
        if ($tipo === 'fornecedores') {
            return $this->relatorioFornecedores($request, $empresaPrincipal);
        }

        // Se for transportadoras, implementar lógica específica
        if ($tipo === 'transportadoras') {
            return $this->relatorioTransportadoras($request, $empresaPrincipal);
        }

        // Para outros tipos, retornar view genérica por enquanto
        return view('relatorios.cadastros.detalhes', compact('tipo'));
    }

    /**
     * Relatório de Clientes
     */
    private function relatorioClientes(Request $request, $empresaPrincipal)
    {
        // Filtros
        $filtroTipo = $request->get('tipo', ''); // tipo_pessoa: 1=PF, 2=PJ
        $filtroSituacao = $request->get('situacao', ''); // status: 1=Ativo, 0=Inativo
        $filtroNome = $request->get('nome', '');
        $filtroTelefone = $request->get('telefone', '');
        $filtroEmail = $request->get('email', '');
        $filtroDataInicio = $request->get('data_inicio', '');
        $filtroDataFim = $request->get('data_fim', '');

        // Query base
        $query = \App\Models\Cliente::where('empresa_id', $empresaPrincipal->id);

        // Aplicar filtro de tipo (tipo_pessoa)
        if (!empty($filtroTipo)) {
            $query->where('tipo_pessoa', $filtroTipo);
        }

        // Aplicar filtro de situação (status)
        if ($filtroSituacao !== '') {
            $query->where('status', $filtroSituacao);
        }

        // Aplicar filtro de nome
        if (!empty($filtroNome)) {
            $query->where(function($q) use ($filtroNome) {
                $q->where('nome', 'LIKE', '%' . $filtroNome . '%')
                  ->orWhere('nome_fantasia', 'LIKE', '%' . $filtroNome . '%')
                  ->orWhere('razao_social', 'LIKE', '%' . $filtroNome . '%');
            });
        }

        // Aplicar filtro de telefone
        if (!empty($filtroTelefone)) {
            $query->where(function($q) use ($filtroTelefone) {
                $q->where('telefone_comercial', 'LIKE', '%' . $filtroTelefone . '%')
                  ->orWhere('celular', 'LIKE', '%' . $filtroTelefone . '%');
            });
        }

        // Aplicar filtro de e-mail
        if (!empty($filtroEmail)) {
            $query->where('email', 'LIKE', '%' . $filtroEmail . '%');
        }

        // Aplicar filtro de período (created_at)
        if (!empty($filtroDataInicio)) {
            $query->whereDate('created_at', '>=', $filtroDataInicio);
        }
        if (!empty($filtroDataFim)) {
            $query->whereDate('created_at', '<=', $filtroDataFim);
        }

        // Ordenar por nome
        $query->orderByRaw("COALESCE(razao_social, nome_fantasia, nome) ASC");

        // Paginar resultados
        $clientes = $query->paginate(20);

        // Preservar filtros na paginação
        $clientes->appends([
            'tipo' => $filtroTipo,
            'situacao' => $filtroSituacao,
            'nome' => $filtroNome,
            'telefone' => $filtroTelefone,
            'email' => $filtroEmail,
            'data_inicio' => $filtroDataInicio,
            'data_fim' => $filtroDataFim,
        ]);

        // Calcular estatísticas
        $queryEstatisticas = \App\Models\Cliente::where('empresa_id', $empresaPrincipal->id);

        // Aplicar mesmos filtros nas estatísticas (exceto situação)
        if (!empty($filtroTipo)) {
            $queryEstatisticas->where('tipo_pessoa', $filtroTipo);
        }
        if (!empty($filtroNome)) {
            $queryEstatisticas->where(function($q) use ($filtroNome) {
                $q->where('nome', 'LIKE', '%' . $filtroNome . '%')
                  ->orWhere('nome_fantasia', 'LIKE', '%' . $filtroNome . '%')
                  ->orWhere('razao_social', 'LIKE', '%' . $filtroNome . '%');
            });
        }
        if (!empty($filtroTelefone)) {
            $queryEstatisticas->where(function($q) use ($filtroTelefone) {
                $q->where('telefone_comercial', 'LIKE', '%' . $filtroTelefone . '%')
                  ->orWhere('celular', 'LIKE', '%' . $filtroTelefone . '%');
            });
        }
        if (!empty($filtroEmail)) {
            $queryEstatisticas->where('email', 'LIKE', '%' . $filtroEmail . '%');
        }
        if (!empty($filtroDataInicio)) {
            $queryEstatisticas->whereDate('created_at', '>=', $filtroDataInicio);
        }
        if (!empty($filtroDataFim)) {
            $queryEstatisticas->whereDate('created_at', '<=', $filtroDataFim);
        }

        $totalRegistros = $queryEstatisticas->count();
        $totalAtivos = (clone $queryEstatisticas)->where('status', 1)->count();
        $totalInativos = (clone $queryEstatisticas)->where('status', 0)->count();

        $tipo = 'clientes';

        return view('relatorios.cadastros.detalhes', compact(
            'tipo',
            'clientes',
            'totalRegistros',
            'totalAtivos',
            'totalInativos',
            'filtroTipo',
            'filtroSituacao',
            'filtroNome',
            'filtroTelefone',
            'filtroEmail',
            'filtroDataInicio',
            'filtroDataFim'
        ));
    }

    /**
     * Relatório de Funcionários
     */
    private function relatorioFuncionarios(Request $request, $empresaPrincipal)
    {
        // Filtros
        $filtroNome = $request->get('nome', '');
        $filtroTelefone = $request->get('telefone', '');
        $filtroEmail = $request->get('email', '');
        $filtroCidade = $request->get('cidade', '');
        $filtroEstado = $request->get('estado', '');
        $filtroDataInicio = $request->get('data_inicio', '');
        $filtroDataFim = $request->get('data_fim', '');

        // Query base
        $query = \App\Models\Funcionario::where('empresa_id', $empresaPrincipal->id)
            ->with('enderecos');

        // Aplicar filtro de nome
        if (!empty($filtroNome)) {
            $query->where(function($q) use ($filtroNome) {
                $q->where('nome', 'LIKE', '%' . $filtroNome . '%')
                  ->orWhere('nome_fantasia', 'LIKE', '%' . $filtroNome . '%')
                  ->orWhere('razao_social', 'LIKE', '%' . $filtroNome . '%');
            });
        }

        // Aplicar filtro de telefone
        if (!empty($filtroTelefone)) {
            $query->where(function($q) use ($filtroTelefone) {
                $q->where('telefone_comercial', 'LIKE', '%' . $filtroTelefone . '%')
                  ->orWhere('celular', 'LIKE', '%' . $filtroTelefone . '%');
            });
        }

        // Aplicar filtro de e-mail
        if (!empty($filtroEmail)) {
            $query->where('email', 'LIKE', '%' . $filtroEmail . '%');
        }

        // Aplicar filtro de cidade (através do relacionamento enderecos)
        if (!empty($filtroCidade)) {
            $query->whereHas('enderecos', function($q) use ($filtroCidade) {
                $q->where('cidade', 'LIKE', '%' . $filtroCidade . '%');
            });
        }

        // Aplicar filtro de estado (através do relacionamento enderecos)
        if (!empty($filtroEstado)) {
            $query->whereHas('enderecos', function($q) use ($filtroEstado) {
                $q->where('estado', 'LIKE', '%' . $filtroEstado . '%');
            });
        }

        // Aplicar filtro de período (created_at)
        if (!empty($filtroDataInicio)) {
            $query->whereDate('created_at', '>=', $filtroDataInicio);
        }
        if (!empty($filtroDataFim)) {
            $query->whereDate('created_at', '<=', $filtroDataFim);
        }

        // Ordenar por nome
        $query->orderByRaw("COALESCE(razao_social, nome_fantasia, nome) ASC");

        // Paginar resultados
        $funcionarios = $query->paginate(20);

        // Preservar filtros na paginação
        $funcionarios->appends([
            'nome' => $filtroNome,
            'telefone' => $filtroTelefone,
            'email' => $filtroEmail,
            'cidade' => $filtroCidade,
            'estado' => $filtroEstado,
            'data_inicio' => $filtroDataInicio,
            'data_fim' => $filtroDataFim,
        ]);

        // Calcular estatísticas
        $queryEstatisticas = \App\Models\Funcionario::where('empresa_id', $empresaPrincipal->id);

        // Aplicar mesmos filtros nas estatísticas
        if (!empty($filtroNome)) {
            $queryEstatisticas->where(function($q) use ($filtroNome) {
                $q->where('nome', 'LIKE', '%' . $filtroNome . '%')
                  ->orWhere('nome_fantasia', 'LIKE', '%' . $filtroNome . '%')
                  ->orWhere('razao_social', 'LIKE', '%' . $filtroNome . '%');
            });
        }
        if (!empty($filtroTelefone)) {
            $queryEstatisticas->where(function($q) use ($filtroTelefone) {
                $q->where('telefone_comercial', 'LIKE', '%' . $filtroTelefone . '%')
                  ->orWhere('celular', 'LIKE', '%' . $filtroTelefone . '%');
            });
        }
        if (!empty($filtroEmail)) {
            $queryEstatisticas->where('email', 'LIKE', '%' . $filtroEmail . '%');
        }
        if (!empty($filtroCidade)) {
            $queryEstatisticas->whereHas('enderecos', function($q) use ($filtroCidade) {
                $q->where('cidade', 'LIKE', '%' . $filtroCidade . '%');
            });
        }
        if (!empty($filtroEstado)) {
            $queryEstatisticas->whereHas('enderecos', function($q) use ($filtroEstado) {
                $q->where('estado', 'LIKE', '%' . $filtroEstado . '%');
            });
        }
        if (!empty($filtroDataInicio)) {
            $queryEstatisticas->whereDate('created_at', '>=', $filtroDataInicio);
        }
        if (!empty($filtroDataFim)) {
            $queryEstatisticas->whereDate('created_at', '<=', $filtroDataFim);
        }

        $totalRegistros = $queryEstatisticas->count();
        $totalAtivos = (clone $queryEstatisticas)->where('status', 1)->count();
        $totalInativos = (clone $queryEstatisticas)->where('status', 0)->count();

        $tipo = 'funcionarios';

        return view('relatorios.cadastros.detalhes', compact(
            'tipo',
            'funcionarios',
            'totalRegistros',
            'totalAtivos',
            'totalInativos',
            'filtroNome',
            'filtroTelefone',
            'filtroEmail',
            'filtroCidade',
            'filtroEstado',
            'filtroDataInicio',
            'filtroDataFim'
        ));
    }

    /**
     * Relatório de Fornecedores
     */
    private function relatorioFornecedores(Request $request, $empresaPrincipal)
    {
        // Filtros
        $filtroTipo = $request->get('tipo', ''); // tipo_pessoa: 1=PF, 2=PJ
        $filtroSituacao = $request->get('situacao', ''); // status: 1=Ativo, 0=Inativo
        $filtroNome = $request->get('nome', '');
        $filtroTelefone = $request->get('telefone', '');
        $filtroEmail = $request->get('email', '');
        $filtroDataInicio = $request->get('data_inicio', '');
        $filtroDataFim = $request->get('data_fim', '');

        // Query base
        $query = \App\Models\Fornecedor::where('empresa_id', $empresaPrincipal->id);

        // Aplicar filtro de tipo (tipo_pessoa)
        if (!empty($filtroTipo)) {
            $query->where('tipo_pessoa', $filtroTipo);
        }

        // Aplicar filtro de situação (status)
        if ($filtroSituacao !== '') {
            $query->where('status', $filtroSituacao);
        }

        // Aplicar filtro de nome
        if (!empty($filtroNome)) {
            $query->where(function($q) use ($filtroNome) {
                $q->where('nome', 'LIKE', '%' . $filtroNome . '%')
                  ->orWhere('nome_fantasia', 'LIKE', '%' . $filtroNome . '%')
                  ->orWhere('razao_social', 'LIKE', '%' . $filtroNome . '%');
            });
        }

        // Aplicar filtro de telefone
        if (!empty($filtroTelefone)) {
            $query->where(function($q) use ($filtroTelefone) {
                $q->where('telefone_comercial', 'LIKE', '%' . $filtroTelefone . '%')
                  ->orWhere('celular', 'LIKE', '%' . $filtroTelefone . '%');
            });
        }

        // Aplicar filtro de e-mail
        if (!empty($filtroEmail)) {
            $query->where('email', 'LIKE', '%' . $filtroEmail . '%');
        }

        // Aplicar filtro de período (created_at)
        if (!empty($filtroDataInicio)) {
            $query->whereDate('created_at', '>=', $filtroDataInicio);
        }
        if (!empty($filtroDataFim)) {
            $query->whereDate('created_at', '<=', $filtroDataFim);
        }

        // Ordenar por nome
        $query->orderByRaw("COALESCE(razao_social, nome_fantasia, nome) ASC");

        // Paginar resultados
        $fornecedores = $query->paginate(20);

        // Preservar filtros na paginação
        $fornecedores->appends([
            'tipo' => $filtroTipo,
            'situacao' => $filtroSituacao,
            'nome' => $filtroNome,
            'telefone' => $filtroTelefone,
            'email' => $filtroEmail,
            'data_inicio' => $filtroDataInicio,
            'data_fim' => $filtroDataFim,
        ]);

        // Calcular estatísticas
        $queryEstatisticas = \App\Models\Fornecedor::where('empresa_id', $empresaPrincipal->id);

        // Aplicar mesmos filtros nas estatísticas (exceto situação)
        if (!empty($filtroTipo)) {
            $queryEstatisticas->where('tipo_pessoa', $filtroTipo);
        }
        if (!empty($filtroNome)) {
            $queryEstatisticas->where(function($q) use ($filtroNome) {
                $q->where('nome', 'LIKE', '%' . $filtroNome . '%')
                  ->orWhere('nome_fantasia', 'LIKE', '%' . $filtroNome . '%')
                  ->orWhere('razao_social', 'LIKE', '%' . $filtroNome . '%');
            });
        }
        if (!empty($filtroTelefone)) {
            $queryEstatisticas->where(function($q) use ($filtroTelefone) {
                $q->where('telefone_comercial', 'LIKE', '%' . $filtroTelefone . '%')
                  ->orWhere('celular', 'LIKE', '%' . $filtroTelefone . '%');
            });
        }
        if (!empty($filtroEmail)) {
            $queryEstatisticas->where('email', 'LIKE', '%' . $filtroEmail . '%');
        }
        if (!empty($filtroDataInicio)) {
            $queryEstatisticas->whereDate('created_at', '>=', $filtroDataInicio);
        }
        if (!empty($filtroDataFim)) {
            $queryEstatisticas->whereDate('created_at', '<=', $filtroDataFim);
        }

        $totalRegistros = $queryEstatisticas->count();
        $totalAtivos = (clone $queryEstatisticas)->where('status', 1)->count();
        $totalInativos = (clone $queryEstatisticas)->where('status', 0)->count();

        $tipo = 'fornecedores';

        return view('relatorios.cadastros.detalhes', compact(
            'tipo',
            'fornecedores',
            'totalRegistros',
            'totalAtivos',
            'totalInativos',
            'filtroTipo',
            'filtroSituacao',
            'filtroNome',
            'filtroTelefone',
            'filtroEmail',
            'filtroDataInicio',
            'filtroDataFim'
        ));
    }

    /**
     * Relatório de Transportadoras
     */
    private function relatorioTransportadoras(Request $request, $empresaPrincipal)
    {
        // Filtros
        $filtroTipo = $request->get('tipo', ''); // tipo_pessoa: 1=PF, 2=PJ
        $filtroSituacao = $request->get('situacao', ''); // status: 1=Ativo, 0=Inativo
        $filtroNome = $request->get('nome', '');
        $filtroTelefone = $request->get('telefone', '');
        $filtroEmail = $request->get('email', '');
        $filtroDataInicio = $request->get('data_inicio', '');
        $filtroDataFim = $request->get('data_fim', '');

        // Query base
        $query = \App\Models\Transportadora::where('empresa_id', $empresaPrincipal->id);

        // Aplicar filtro de tipo (tipo_pessoa)
        if (!empty($filtroTipo)) {
            $query->where('tipo_pessoa', $filtroTipo);
        }

        // Aplicar filtro de situação (status)
        if ($filtroSituacao !== '') {
            $query->where('status', $filtroSituacao);
        }

        // Aplicar filtro de nome
        if (!empty($filtroNome)) {
            $query->where(function($q) use ($filtroNome) {
                $q->where('nome', 'LIKE', '%' . $filtroNome . '%')
                  ->orWhere('nome_fantasia', 'LIKE', '%' . $filtroNome . '%')
                  ->orWhere('razao_social', 'LIKE', '%' . $filtroNome . '%');
            });
        }

        // Aplicar filtro de telefone
        if (!empty($filtroTelefone)) {
            $query->where(function($q) use ($filtroTelefone) {
                $q->where('telefone_comercial', 'LIKE', '%' . $filtroTelefone . '%')
                  ->orWhere('celular', 'LIKE', '%' . $filtroTelefone . '%');
            });
        }

        // Aplicar filtro de e-mail
        if (!empty($filtroEmail)) {
            $query->where('email', 'LIKE', '%' . $filtroEmail . '%');
        }

        // Aplicar filtro de período (created_at)
        if (!empty($filtroDataInicio)) {
            $query->whereDate('created_at', '>=', $filtroDataInicio);
        }
        if (!empty($filtroDataFim)) {
            $query->whereDate('created_at', '<=', $filtroDataFim);
        }

        // Ordenar por nome
        $query->orderByRaw("COALESCE(razao_social, nome_fantasia, nome) ASC");

        // Paginar resultados
        $transportadoras = $query->paginate(20);

        // Preservar filtros na paginação
        $transportadoras->appends([
            'tipo' => $filtroTipo,
            'situacao' => $filtroSituacao,
            'nome' => $filtroNome,
            'telefone' => $filtroTelefone,
            'email' => $filtroEmail,
            'data_inicio' => $filtroDataInicio,
            'data_fim' => $filtroDataFim,
        ]);

        // Calcular estatísticas
        $queryEstatisticas = \App\Models\Transportadora::where('empresa_id', $empresaPrincipal->id);

        // Aplicar mesmos filtros nas estatísticas (exceto situação)
        if (!empty($filtroTipo)) {
            $queryEstatisticas->where('tipo_pessoa', $filtroTipo);
        }
        if (!empty($filtroNome)) {
            $queryEstatisticas->where(function($q) use ($filtroNome) {
                $q->where('nome', 'LIKE', '%' . $filtroNome . '%')
                  ->orWhere('nome_fantasia', 'LIKE', '%' . $filtroNome . '%')
                  ->orWhere('razao_social', 'LIKE', '%' . $filtroNome . '%');
            });
        }
        if (!empty($filtroTelefone)) {
            $queryEstatisticas->where(function($q) use ($filtroTelefone) {
                $q->where('telefone_comercial', 'LIKE', '%' . $filtroTelefone . '%')
                  ->orWhere('celular', 'LIKE', '%' . $filtroTelefone . '%');
            });
        }
        if (!empty($filtroEmail)) {
            $queryEstatisticas->where('email', 'LIKE', '%' . $filtroEmail . '%');
        }
        if (!empty($filtroDataInicio)) {
            $queryEstatisticas->whereDate('created_at', '>=', $filtroDataInicio);
        }
        if (!empty($filtroDataFim)) {
            $queryEstatisticas->whereDate('created_at', '<=', $filtroDataFim);
        }

        $totalRegistros = $queryEstatisticas->count();
        $totalAtivos = (clone $queryEstatisticas)->where('status', 1)->count();
        $totalInativos = (clone $queryEstatisticas)->where('status', 0)->count();

        $tipo = 'transportadoras';

        return view('relatorios.cadastros.detalhes', compact(
            'tipo',
            'transportadoras',
            'totalRegistros',
            'totalAtivos',
            'totalInativos',
            'filtroTipo',
            'filtroSituacao',
            'filtroNome',
            'filtroTelefone',
            'filtroEmail',
            'filtroDataInicio',
            'filtroDataFim'
        ));
    }

    /**
     * Exporta relatório de cadastros para CSV
     */
    public function exportarCadastrosCsv(Request $request, $tipo)
    {
        $tiposPermitidos = ['clientes', 'aniversariantes', 'funcionarios', 'fornecedores', 'transportadoras'];
        
        if (!in_array($tipo, $tiposPermitidos)) {
            abort(404);
        }

        $empresaPrincipal = PermissionHelper::getEmpresaPrincipal();
        if (!$empresaPrincipal) {
            abort(403, 'Empresa principal não encontrada.');
        }

        // Se for clientes, implementar exportação específica
        if ($tipo === 'clientes') {
            return $this->exportarClientesCsv($request, $empresaPrincipal);
        }

        // Se for funcionarios, implementar exportação específica
        if ($tipo === 'funcionarios') {
            return $this->exportarFuncionariosCsv($request, $empresaPrincipal);
        }

        // Se for fornecedores, implementar exportação específica
        if ($tipo === 'fornecedores') {
            return $this->exportarFornecedoresCsv($request, $empresaPrincipal);
        }

        // Se for transportadoras, implementar exportação específica
        if ($tipo === 'transportadoras') {
            return $this->exportarTransportadorasCsv($request, $empresaPrincipal);
        }

        // Para outros tipos, retornar erro por enquanto
        abort(404, 'Exportação ainda não implementada para este tipo de relatório.');
    }

    /**
     * Exporta relatório de clientes para CSV
     */
    private function exportarClientesCsv(Request $request, $empresaPrincipal)
    {
        // Aplicar os mesmos filtros do método relatorioClientes
        $filtroTipo = $request->get('tipo', '');
        $filtroSituacao = $request->get('situacao', '');
        $filtroNome = $request->get('nome', '');
        $filtroTelefone = $request->get('telefone', '');
        $filtroEmail = $request->get('email', '');
        $filtroDataInicio = $request->get('data_inicio', '');
        $filtroDataFim = $request->get('data_fim', '');

        // Query base - buscar todos os clientes (sem paginação)
        $query = \App\Models\Cliente::where('empresa_id', $empresaPrincipal->id);

        // Aplicar filtros (mesma lógica do método relatorioClientes)
        if (!empty($filtroTipo)) {
            $query->where('tipo_pessoa', $filtroTipo);
        }

        if ($filtroSituacao !== '') {
            $query->where('status', $filtroSituacao);
        }

        if (!empty($filtroNome)) {
            $query->where(function($q) use ($filtroNome) {
                $q->where('nome', 'LIKE', '%' . $filtroNome . '%')
                  ->orWhere('nome_fantasia', 'LIKE', '%' . $filtroNome . '%')
                  ->orWhere('razao_social', 'LIKE', '%' . $filtroNome . '%');
            });
        }

        if (!empty($filtroTelefone)) {
            $query->where(function($q) use ($filtroTelefone) {
                $q->where('telefone_comercial', 'LIKE', '%' . $filtroTelefone . '%')
                  ->orWhere('celular', 'LIKE', '%' . $filtroTelefone . '%');
            });
        }

        if (!empty($filtroEmail)) {
            $query->where('email', 'LIKE', '%' . $filtroEmail . '%');
        }

        if (!empty($filtroDataInicio)) {
            $query->whereDate('created_at', '>=', $filtroDataInicio);
        }
        if (!empty($filtroDataFim)) {
            $query->whereDate('created_at', '<=', $filtroDataFim);
        }

        // Ordenar por nome
        $query->orderByRaw("COALESCE(razao_social, nome_fantasia, nome) ASC");

        // Buscar todos os resultados (sem paginação)
        $clientes = $query->get();

        // Preparar nome do arquivo
        $nomeArquivo = 'relatorio_clientes_' . date('Y-m-d_His') . '.csv';

        // Headers para download CSV
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $nomeArquivo . '"',
        ];

        // Criar callback para gerar CSV
        $callback = function() use ($clientes) {
            $file = fopen('php://output', 'w');
            
            // Adicionar BOM para UTF-8 (para Excel abrir corretamente)
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Cabeçalhos
            fputcsv($file, [
                'Nome',
                'Tipo',
                'Documento',
                'E-mail',
                'Telefone Comercial',
                'Celular',
                'Situação',
                'Data de Cadastro',
                'Observação'
            ], ';');

            // Dados
            foreach ($clientes as $cliente) {
                $tipo = $cliente->tipo_pessoa == 1 ? 'Pessoa Física' : 'Pessoa Jurídica';
                $situacao = $cliente->status == 1 ? 'Ativo' : 'Inativo';
                $dataCadastro = $cliente->created_at ? $cliente->created_at->format('d/m/Y') : 'N/A';

                fputcsv($file, [
                    $cliente->nome_completo,
                    $tipo,
                    $cliente->documento_formatado ?? 'N/A',
                    $cliente->email ?? 'N/A',
                    $cliente->telefone_comercial_formatado ?? 'N/A',
                    $cliente->celular_formatado ?? 'N/A',
                    $situacao,
                    $dataCadastro,
                    $cliente->observacao ?? ''
                ], ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Exporta relatório de funcionários para CSV
     */
    private function exportarFuncionariosCsv(Request $request, $empresaPrincipal)
    {
        // Aplicar os mesmos filtros do método relatorioFuncionarios
        $filtroNome = $request->get('nome', '');
        $filtroTelefone = $request->get('telefone', '');
        $filtroEmail = $request->get('email', '');
        $filtroCidade = $request->get('cidade', '');
        $filtroEstado = $request->get('estado', '');
        $filtroDataInicio = $request->get('data_inicio', '');
        $filtroDataFim = $request->get('data_fim', '');

        // Query base - buscar todos os funcionários (sem paginação)
        $query = \App\Models\Funcionario::where('empresa_id', $empresaPrincipal->id)
            ->with('enderecos');

        // Aplicar filtros (mesma lógica do método relatorioFuncionarios)
        if (!empty($filtroNome)) {
            $query->where(function($q) use ($filtroNome) {
                $q->where('nome', 'LIKE', '%' . $filtroNome . '%')
                  ->orWhere('nome_fantasia', 'LIKE', '%' . $filtroNome . '%')
                  ->orWhere('razao_social', 'LIKE', '%' . $filtroNome . '%');
            });
        }

        if (!empty($filtroTelefone)) {
            $query->where(function($q) use ($filtroTelefone) {
                $q->where('telefone_comercial', 'LIKE', '%' . $filtroTelefone . '%')
                  ->orWhere('celular', 'LIKE', '%' . $filtroTelefone . '%');
            });
        }

        if (!empty($filtroEmail)) {
            $query->where('email', 'LIKE', '%' . $filtroEmail . '%');
        }

        if (!empty($filtroCidade)) {
            $query->whereHas('enderecos', function($q) use ($filtroCidade) {
                $q->where('cidade', 'LIKE', '%' . $filtroCidade . '%');
            });
        }

        if (!empty($filtroEstado)) {
            $query->whereHas('enderecos', function($q) use ($filtroEstado) {
                $q->where('estado', 'LIKE', '%' . $filtroEstado . '%');
            });
        }

        if (!empty($filtroDataInicio)) {
            $query->whereDate('created_at', '>=', $filtroDataInicio);
        }
        if (!empty($filtroDataFim)) {
            $query->whereDate('created_at', '<=', $filtroDataFim);
        }

        // Ordenar por nome
        $query->orderByRaw("COALESCE(razao_social, nome_fantasia, nome) ASC");

        // Buscar todos os resultados (sem paginação)
        $funcionarios = $query->get();

        // Preparar nome do arquivo
        $nomeArquivo = 'relatorio_funcionarios_' . date('Y-m-d_His') . '.csv';

        // Headers para download CSV
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $nomeArquivo . '"',
        ];

        // Criar callback para gerar CSV
        $callback = function() use ($funcionarios) {
            $file = fopen('php://output', 'w');
            
            // Adicionar BOM para UTF-8 (para Excel abrir corretamente)
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Cabeçalhos
            fputcsv($file, [
                'Nome',
                'Documento',
                'E-mail',
                'Telefone Comercial',
                'Celular',
                'Cidade',
                'Estado',
                'Situação',
                'Data de Cadastro',
                'Observação'
            ], ';');

            // Dados
            foreach ($funcionarios as $funcionario) {
                $situacao = $funcionario->status == 1 ? 'Ativo' : 'Inativo';
                $dataCadastro = $funcionario->created_at ? $funcionario->created_at->format('d/m/Y') : 'N/A';
                
                // Pegar primeiro endereço (se houver)
                $endereco = $funcionario->enderecos->first();
                $cidade = $endereco ? ($endereco->cidade ?? 'N/A') : 'N/A';
                $estado = $endereco ? ($endereco->estado ?? 'N/A') : 'N/A';

                fputcsv($file, [
                    $funcionario->nome_completo,
                    $funcionario->documento_formatado ?? 'N/A',
                    $funcionario->email ?? 'N/A',
                    $funcionario->telefone_comercial_formatado ?? 'N/A',
                    $funcionario->celular_formatado ?? 'N/A',
                    $cidade,
                    $estado,
                    $situacao,
                    $dataCadastro,
                    $funcionario->observacao ?? ''
                ], ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Exporta relatório de fornecedores para CSV
     */
    private function exportarFornecedoresCsv(Request $request, $empresaPrincipal)
    {
        // Aplicar os mesmos filtros do método relatorioFornecedores
        $filtroTipo = $request->get('tipo', '');
        $filtroSituacao = $request->get('situacao', '');
        $filtroNome = $request->get('nome', '');
        $filtroTelefone = $request->get('telefone', '');
        $filtroEmail = $request->get('email', '');
        $filtroDataInicio = $request->get('data_inicio', '');
        $filtroDataFim = $request->get('data_fim', '');

        // Query base - buscar todos os fornecedores (sem paginação)
        $query = \App\Models\Fornecedor::where('empresa_id', $empresaPrincipal->id);

        // Aplicar filtros (mesma lógica do método relatorioFornecedores)
        if (!empty($filtroTipo)) {
            $query->where('tipo_pessoa', $filtroTipo);
        }

        if ($filtroSituacao !== '') {
            $query->where('status', $filtroSituacao);
        }

        if (!empty($filtroNome)) {
            $query->where(function($q) use ($filtroNome) {
                $q->where('nome', 'LIKE', '%' . $filtroNome . '%')
                  ->orWhere('nome_fantasia', 'LIKE', '%' . $filtroNome . '%')
                  ->orWhere('razao_social', 'LIKE', '%' . $filtroNome . '%');
            });
        }

        if (!empty($filtroTelefone)) {
            $query->where(function($q) use ($filtroTelefone) {
                $q->where('telefone_comercial', 'LIKE', '%' . $filtroTelefone . '%')
                  ->orWhere('celular', 'LIKE', '%' . $filtroTelefone . '%');
            });
        }

        if (!empty($filtroEmail)) {
            $query->where('email', 'LIKE', '%' . $filtroEmail . '%');
        }

        if (!empty($filtroDataInicio)) {
            $query->whereDate('created_at', '>=', $filtroDataInicio);
        }
        if (!empty($filtroDataFim)) {
            $query->whereDate('created_at', '<=', $filtroDataFim);
        }

        // Ordenar por nome
        $query->orderByRaw("COALESCE(razao_social, nome_fantasia, nome) ASC");

        // Buscar todos os resultados (sem paginação)
        $fornecedores = $query->get();

        // Preparar nome do arquivo
        $nomeArquivo = 'relatorio_fornecedores_' . date('Y-m-d_His') . '.csv';

        // Headers para download CSV
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $nomeArquivo . '"',
        ];

        // Criar callback para gerar CSV
        $callback = function() use ($fornecedores) {
            $file = fopen('php://output', 'w');
            
            // Adicionar BOM para UTF-8 (para Excel abrir corretamente)
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Cabeçalhos
            fputcsv($file, [
                'Nome',
                'Tipo',
                'Documento',
                'E-mail',
                'Telefone Comercial',
                'Celular',
                'Situação',
                'Data de Cadastro',
                'Observação'
            ], ';');

            // Dados
            foreach ($fornecedores as $fornecedor) {
                $tipo = $fornecedor->tipo_pessoa == 1 ? 'Pessoa Física' : 'Pessoa Jurídica';
                $situacao = $fornecedor->status == 1 ? 'Ativo' : 'Inativo';
                $dataCadastro = $fornecedor->created_at ? $fornecedor->created_at->format('d/m/Y') : 'N/A';

                fputcsv($file, [
                    $fornecedor->nome_completo,
                    $tipo,
                    $fornecedor->documento_formatado ?? 'N/A',
                    $fornecedor->email ?? 'N/A',
                    $fornecedor->telefone_comercial_formatado ?? 'N/A',
                    $fornecedor->celular_formatado ?? 'N/A',
                    $situacao,
                    $dataCadastro,
                    $fornecedor->observacao ?? ''
                ], ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Exporta relatório de transportadoras para CSV
     */
    private function exportarTransportadorasCsv(Request $request, $empresaPrincipal)
    {
        // Aplicar os mesmos filtros do método relatorioTransportadoras
        $filtroTipo = $request->get('tipo', '');
        $filtroSituacao = $request->get('situacao', '');
        $filtroNome = $request->get('nome', '');
        $filtroTelefone = $request->get('telefone', '');
        $filtroEmail = $request->get('email', '');
        $filtroDataInicio = $request->get('data_inicio', '');
        $filtroDataFim = $request->get('data_fim', '');

        // Query base - buscar todas as transportadoras (sem paginação)
        $query = \App\Models\Transportadora::where('empresa_id', $empresaPrincipal->id);

        // Aplicar filtros (mesma lógica do método relatorioTransportadoras)
        if (!empty($filtroTipo)) {
            $query->where('tipo_pessoa', $filtroTipo);
        }

        if ($filtroSituacao !== '') {
            $query->where('status', $filtroSituacao);
        }

        if (!empty($filtroNome)) {
            $query->where(function($q) use ($filtroNome) {
                $q->where('nome', 'LIKE', '%' . $filtroNome . '%')
                  ->orWhere('nome_fantasia', 'LIKE', '%' . $filtroNome . '%')
                  ->orWhere('razao_social', 'LIKE', '%' . $filtroNome . '%');
            });
        }

        if (!empty($filtroTelefone)) {
            $query->where(function($q) use ($filtroTelefone) {
                $q->where('telefone_comercial', 'LIKE', '%' . $filtroTelefone . '%')
                  ->orWhere('celular', 'LIKE', '%' . $filtroTelefone . '%');
            });
        }

        if (!empty($filtroEmail)) {
            $query->where('email', 'LIKE', '%' . $filtroEmail . '%');
        }

        if (!empty($filtroDataInicio)) {
            $query->whereDate('created_at', '>=', $filtroDataInicio);
        }
        if (!empty($filtroDataFim)) {
            $query->whereDate('created_at', '<=', $filtroDataFim);
        }

        // Ordenar por nome
        $query->orderByRaw("COALESCE(razao_social, nome_fantasia, nome) ASC");

        // Buscar todos os resultados (sem paginação)
        $transportadoras = $query->get();

        // Preparar nome do arquivo
        $nomeArquivo = 'relatorio_transportadoras_' . date('Y-m-d_His') . '.csv';

        // Headers para download CSV
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $nomeArquivo . '"',
        ];

        // Criar callback para gerar CSV
        $callback = function() use ($transportadoras) {
            $file = fopen('php://output', 'w');
            
            // Adicionar BOM para UTF-8 (para Excel abrir corretamente)
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Cabeçalhos
            fputcsv($file, [
                'Nome',
                'Tipo',
                'Documento',
                'E-mail',
                'Telefone Comercial',
                'Celular',
                'Situação',
                'Data de Cadastro',
                'Observação'
            ], ';');

            // Dados
            foreach ($transportadoras as $transportadora) {
                $tipo = $transportadora->tipo_pessoa == 1 ? 'Pessoa Física' : 'Pessoa Jurídica';
                $situacao = $transportadora->status == 1 ? 'Ativo' : 'Inativo';
                $dataCadastro = $transportadora->created_at ? $transportadora->created_at->format('d/m/Y') : 'N/A';

                fputcsv($file, [
                    $transportadora->nome_completo,
                    $tipo,
                    $transportadora->documento_formatado ?? 'N/A',
                    $transportadora->email ?? 'N/A',
                    $transportadora->telefone_comercial_formatado ?? 'N/A',
                    $transportadora->celular_formatado ?? 'N/A',
                    $situacao,
                    $dataCadastro,
                    $transportadora->observacao ?? ''
                ], ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Exibe a página de relatórios financeiros
     */
    public function financeiro(Request $request)
    {
        $empresaPrincipal = PermissionHelper::getEmpresaPrincipal();
        if (!$empresaPrincipal) {
            abort(403, 'Empresa principal não encontrada.');
        }

        // Filtros
        $filtroTipo = $request->get('tipo', '');
        $filtroSituacao = $request->get('situacao', '');
        
        // Definir datas padrão apenas se não há nenhum parâmetro na URL (primeira carga)
        $filtroVencimentoInicio = $request->get('vencimento_inicio', '');
        $filtroVencimentoFim = $request->get('vencimento_fim', '');
        
        // Se não há nenhum parâmetro na requisição, definir datas padrão
        if (!$request->hasAny(['tipo', 'situacao', 'vencimento_inicio', 'vencimento_fim', 'valor_minimo', 'valor_maximo', 'conta_bancaria', 'page'])) {
            $filtroVencimentoInicio = Carbon::now()->startOfYear()->format('Y-m-d'); // 01 de janeiro do ano atual
            $filtroVencimentoFim = Carbon::now()->endOfMonth()->format('Y-m-d'); // Último dia do mês atual
        }
        
        $filtroValorMinimo = $request->get('valor_minimo', '');
        $filtroValorMaximo = $request->get('valor_maximo', '');
        $filtroContaBancaria = $request->get('conta_bancaria', '');

        // Validar período máximo de 2 anos
        if (!empty($filtroVencimentoInicio) && !empty($filtroVencimentoFim)) {
            $dataInicio = Carbon::parse($filtroVencimentoInicio);
            $dataFim = Carbon::parse($filtroVencimentoFim);
            
            if ($dataInicio->gt($dataFim)) {
                return redirect()->route('relatorios.financeiro')
                    ->with('error', 'A data de início não pode ser maior que a data de fim.')
                    ->withInput();
            }
            
            $diferencaAnos = $dataInicio->diffInYears($dataFim);
            if ($diferencaAnos > 2) {
                return redirect()->route('relatorios.financeiro')
                    ->with('error', 'O período selecionado não pode ser maior que 2 anos.')
                    ->withInput();
            }
        }

        // Query base - buscar todas as movimentações da empresa
        $query = Movimentacao::where('empresa_id', $empresaPrincipal->id)
            ->where('situacao', '!=', MovimentacaoSituacaoEnum::CANCELADA->value)
            ->with(['planoConta', 'centroCusto', 'formaPagamento', 'contaEmpresa', 'entidade']);

        // Aplicar filtro de tipo
        if (!empty($filtroTipo)) {
            $tipoEnum = MovimentacaoTipoEnum::tryFrom($filtroTipo);
            if ($tipoEnum) {
                $query->where('tipo', $tipoEnum->value);
            }
        }

        // Aplicar filtro de situação
        if (!empty($filtroSituacao)) {
            $situacaoEnum = MovimentacaoSituacaoEnum::tryFrom($filtroSituacao);
            if ($situacaoEnum) {
                $query->where('situacao', $situacaoEnum->value);
            }
        }

        // Aplicar filtro de vencimento
        if (!empty($filtroVencimentoInicio)) {
            $query->where('vencimento', '>=', $filtroVencimentoInicio);
        }
        if (!empty($filtroVencimentoFim)) {
            $query->where('vencimento', '<=', $filtroVencimentoFim);
        }

        // Aplicar filtro de valor
        if (!empty($filtroValorMinimo)) {
            $query->where('valor_total', '>=', $filtroValorMinimo);
        }
        if (!empty($filtroValorMaximo)) {
            $query->where('valor_total', '<=', $filtroValorMaximo);
        }

        // Aplicar filtro de conta bancária
        if (!empty($filtroContaBancaria)) {
            $query->where('conta_empresa_id', $filtroContaBancaria);
        }

        // Ordenar por vencimento
        $query->orderBy('vencimento', 'desc');

        // Paginar resultados
        $movimentacoes = $query->paginate(20);

        // Preservar filtros na paginação
        $movimentacoes->appends([
            'tipo' => $filtroTipo,
            'situacao' => $filtroSituacao,
            'vencimento_inicio' => $filtroVencimentoInicio,
            'vencimento_fim' => $filtroVencimentoFim,
            'valor_minimo' => $filtroValorMinimo,
            'valor_maximo' => $filtroValorMaximo,
            'conta_bancaria' => $filtroContaBancaria,
        ]);

        // Calcular estatísticas
        $queryEstatisticas = Movimentacao::where('empresa_id', $empresaPrincipal->id)
            ->where('situacao', '!=', MovimentacaoSituacaoEnum::CANCELADA->value);

        // Aplicar mesmos filtros nas estatísticas
        if (!empty($filtroVencimentoInicio)) {
            $queryEstatisticas->where('vencimento', '>=', $filtroVencimentoInicio);
        }
        if (!empty($filtroVencimentoFim)) {
            $queryEstatisticas->where('vencimento', '<=', $filtroVencimentoFim);
        }
        if (!empty($filtroValorMinimo)) {
            $queryEstatisticas->where('valor_total', '>=', $filtroValorMinimo);
        }
        if (!empty($filtroValorMaximo)) {
            $queryEstatisticas->where('valor_total', '<=', $filtroValorMaximo);
        }
        if (!empty($filtroContaBancaria)) {
            $queryEstatisticas->where('conta_empresa_id', $filtroContaBancaria);
        }

        // Total a Pagar (tipo = PAGAR e situação != PAGA)
        $queryPagar = clone $queryEstatisticas;
        $queryPagar->where('tipo', MovimentacaoTipoEnum::PAGAR->value)
            ->where('situacao', '!=', MovimentacaoSituacaoEnum::PAGA->value);
        $totalPagar = $queryPagar->sum('valor_total');

        // Total a Receber (tipo = RECEBER e situação != PAGA)
        $queryReceber = clone $queryEstatisticas;
        $queryReceber->where('tipo', MovimentacaoTipoEnum::RECEBER->value)
            ->where('situacao', '!=', MovimentacaoSituacaoEnum::PAGA->value);
        $totalReceber = $queryReceber->sum('valor_total');

        // Saldo (Receber - Pagar)
        $saldo = $totalReceber - $totalPagar;

        // Total de movimentações
        $totalMovimentacoes = $queryEstatisticas->count();

        // Dados para gráfico "Distribuição por Situação"
        // Usar getQuery()->get() para obter dados sem casts do Eloquent
        $queryDistribuicao = clone $queryEstatisticas;
        $distribuicaoSituacao = $queryDistribuicao->getQuery()
            ->selectRaw('situacao, COUNT(*) as total')
            ->groupBy('situacao')
            ->get()
            ->mapWithKeys(function ($item) {
                // Converter para int (getQuery()->get() retorna stdClass sem casts)
                $situacaoValue = (int) $item->situacao;
                $situacao = MovimentacaoSituacaoEnum::tryFrom($situacaoValue);
                $label = $situacao ? $situacao->getLabel() : 'Desconhecida';
                return [$label => (int) $item->total];
            })
            ->toArray();

        // Dados para gráfico "Movimentações por Mês"
        // Determinar período para gerar todos os meses (usar as datas filtradas, que já incluem padrão se necessário)
        $dataInicioPeriodo = !empty($filtroVencimentoInicio) 
            ? Carbon::parse($filtroVencimentoInicio)->startOfMonth()
            : Carbon::now()->startOfYear()->startOfMonth();
        $dataFimPeriodo = !empty($filtroVencimentoFim) 
            ? Carbon::parse($filtroVencimentoFim)->endOfMonth()
            : Carbon::now()->endOfMonth();

        // Gerar todos os meses do período
        $todosMeses = [];
        $dataAtual = $dataInicioPeriodo->copy()->startOfMonth();
        while ($dataAtual->lte($dataFimPeriodo)) {
            $mesKey = $dataAtual->format('Y-m');
            $todosMeses[$mesKey] = [
                'mes' => $mesKey,
                'total' => 0,
                'valor_total' => 0
            ];
            $dataAtual->addMonth();
        }

        // Buscar dados reais do banco - separar por tipo (entrada/saída)
        // Contas a Receber (entrada) - tipo = 2
        $queryReceber = clone $queryEstatisticas;
        $movimentacoesReceber = $queryReceber->where('tipo', MovimentacaoTipoEnum::RECEBER->value)
            ->selectRaw('DATE_FORMAT(vencimento, "%Y-%m") as mes, SUM(valor_total) as valor_total')
            ->whereNotNull('vencimento')
            ->groupBy('mes')
            ->get()
            ->keyBy('mes');

        // Contas a Pagar (saída) - tipo = 1
        $queryPagar = clone $queryEstatisticas;
        $movimentacoesPagar = $queryPagar->where('tipo', MovimentacaoTipoEnum::PAGAR->value)
            ->selectRaw('DATE_FORMAT(vencimento, "%Y-%m") as mes, SUM(valor_total) as valor_total')
            ->whereNotNull('vencimento')
            ->groupBy('mes')
            ->get()
            ->keyBy('mes');

        // Mesclar dados reais com todos os meses (preenchendo com 0 os meses sem dados)
        foreach ($todosMeses as $mesKey => &$mesData) {
            $valorReceber = isset($movimentacoesReceber[$mesKey]) ? (float) $movimentacoesReceber[$mesKey]->valor_total : 0;
            $valorPagar = isset($movimentacoesPagar[$mesKey]) ? (float) $movimentacoesPagar[$mesKey]->valor_total : 0;
            
            // Saldo do mês (Receber - Pagar)
            $mesData['valor_total'] = $valorReceber - $valorPagar;
            $mesData['valor_receber'] = $valorReceber;
            $mesData['valor_pagar'] = $valorPagar;
        }
        unset($mesData);

        // Ordenar por mês e formatar para o gráfico
        ksort($todosMeses);
        $valoresPagar = [];
        $valoresReceber = [];
        $labelsMensais = [];
        
        // Array de meses em português
        $mesesPortugues = [
            1 => 'Jan', 2 => 'Fev', 3 => 'Mar', 4 => 'Abr',
            5 => 'Mai', 6 => 'Jun', 7 => 'Jul', 8 => 'Ago',
            9 => 'Set', 10 => 'Out', 11 => 'Nov', 12 => 'Dez'
        ];
        
        foreach ($todosMeses as $mesData) {
            $data = Carbon::createFromFormat('Y-m', $mesData['mes']);
            $mesNumero = (int) $data->format('n'); // 1-12
            $ano = $data->format('Y');
            $mesNome = $mesesPortugues[$mesNumero] ?? $data->format('M');
            $labelsMensais[] = $mesNome . '/' . $ano;
            // Separar valores de pagar e receber
            $valoresPagar[] = $mesData['valor_pagar'];
            $valoresReceber[] = $mesData['valor_receber'];
        }

        // Buscar contas bancárias para o filtro
        $contasBancarias = ContaEmpresa::where('empresa_id', $empresaPrincipal->id)
            ->where('status', 1)
            ->with('banco')
            ->orderBy('nome')
            ->get();

        return view('relatorios.financeiro', compact(
            'movimentacoes',
            'totalPagar',
            'totalReceber',
            'saldo',
            'totalMovimentacoes',
            'contasBancarias',
            'filtroTipo',
            'filtroSituacao',
            'filtroVencimentoInicio',
            'filtroVencimentoFim',
            'filtroValorMinimo',
            'filtroValorMaximo',
            'filtroContaBancaria',
            'distribuicaoSituacao',
            'labelsMensais',
            'valoresPagar',
            'valoresReceber'
        ));
    }

    /**
     * Exporta relatório financeiro para CSV
     */
    public function exportarFinanceiroCsv(Request $request)
    {
        $empresaPrincipal = PermissionHelper::getEmpresaPrincipal();
        if (!$empresaPrincipal) {
            abort(403, 'Empresa principal não encontrada.');
        }

        // Aplicar os mesmos filtros do método financeiro()
        $filtroTipo = $request->get('tipo', '');
        $filtroSituacao = $request->get('situacao', '');
        $filtroVencimentoInicio = $request->get('vencimento_inicio', '');
        $filtroVencimentoFim = $request->get('vencimento_fim', '');
        $filtroValorMinimo = $request->get('valor_minimo', '');
        $filtroValorMaximo = $request->get('valor_maximo', '');
        $filtroContaBancaria = $request->get('conta_bancaria', '');

        // Se não há filtros, usar datas padrão
        if (!$request->hasAny(['tipo', 'situacao', 'vencimento_inicio', 'vencimento_fim', 'valor_minimo', 'valor_maximo', 'conta_bancaria'])) {
            $filtroVencimentoInicio = Carbon::now()->startOfYear()->format('Y-m-d');
            $filtroVencimentoFim = Carbon::now()->endOfMonth()->format('Y-m-d');
        }

        // Query base - buscar todas as movimentações da empresa (sem paginação)
        $query = Movimentacao::where('empresa_id', $empresaPrincipal->id)
            ->where('situacao', '!=', MovimentacaoSituacaoEnum::CANCELADA->value)
            ->with(['planoConta', 'centroCusto', 'formaPagamento', 'contaEmpresa', 'entidade']);

        // Aplicar filtros (mesma lógica do método financeiro)
        if (!empty($filtroTipo)) {
            $tipoEnum = MovimentacaoTipoEnum::tryFrom($filtroTipo);
            if ($tipoEnum) {
                $query->where('tipo', $tipoEnum->value);
            }
        }

        if (!empty($filtroSituacao)) {
            $situacaoEnum = MovimentacaoSituacaoEnum::tryFrom($filtroSituacao);
            if ($situacaoEnum) {
                $query->where('situacao', $situacaoEnum->value);
            }
        }

        if (!empty($filtroVencimentoInicio)) {
            $query->where('vencimento', '>=', $filtroVencimentoInicio);
        }
        if (!empty($filtroVencimentoFim)) {
            $query->where('vencimento', '<=', $filtroVencimentoFim);
        }

        if (!empty($filtroValorMinimo)) {
            $query->where('valor_total', '>=', $filtroValorMinimo);
        }
        if (!empty($filtroValorMaximo)) {
            $query->where('valor_total', '<=', $filtroValorMaximo);
        }

        if (!empty($filtroContaBancaria)) {
            $query->where('conta_empresa_id', $filtroContaBancaria);
        }

        // Ordenar por vencimento
        $query->orderBy('vencimento', 'desc');

        // Buscar TODOS os resultados (sem paginação) - exporta todas as páginas
        // Usa get() ao invés de paginate() para exportar todos os registros que atendem aos filtros
        $movimentacoes = $query->get();

        // Preparar nome do arquivo
        $nomeArquivo = 'relatorio_financeiro_' . date('Y-m-d_His') . '.csv';

        // Headers para download CSV
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $nomeArquivo . '"',
        ];

        // Criar callback para gerar CSV
        $callback = function() use ($movimentacoes) {
            $file = fopen('php://output', 'w');
            
            // Adicionar BOM para UTF-8 (para Excel abrir corretamente)
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Cabeçalhos
            fputcsv($file, [
                'Tipo',
                'Descrição',
                'Vencimento',
                'Valor',
                'Situação',
                'Conta Bancária',
                'Plano de Contas',
                'Centro de Custo',
                'Forma de Pagamento',
                'Entidade',
                'Data de Compensação',
                'Observação'
            ], ';');

            // Dados
            foreach ($movimentacoes as $movimentacao) {
                $tipo = $movimentacao->tipo->value == 1 ? 'Contas a Pagar' : 'Contas a Receber';
                $situacao = $movimentacao->situacao->getLabel();
                $vencimento = $movimentacao->vencimento ? Carbon::parse($movimentacao->vencimento)->format('d/m/Y') : 'N/A';
                $valor = number_format($movimentacao->valor_total, 2, ',', '.');
                $contaBancaria = $movimentacao->contaEmpresa ? $movimentacao->contaEmpresa->nome : 'N/A';
                $planoConta = $movimentacao->planoConta ? $movimentacao->planoConta->nome : 'N/A';
                $centroCusto = $movimentacao->centroCusto ? $movimentacao->centroCusto->nome : 'N/A';
                $formaPagamento = $movimentacao->formaPagamento ? $movimentacao->formaPagamento->nome : 'N/A';
                $entidade = $movimentacao->entidade ? $movimentacao->entidade->nome : 'N/A';
                $dataCompensacao = $movimentacao->data_compensacao ? Carbon::parse($movimentacao->data_compensacao)->format('d/m/Y') : 'N/A';
                $observacao = $movimentacao->observacao ?? '';

                fputcsv($file, [
                    $tipo,
                    $movimentacao->descricao,
                    $vencimento,
                    $valor,
                    $situacao,
                    $contaBancaria,
                    $planoConta,
                    $centroCusto,
                    $formaPagamento,
                    $entidade,
                    $dataCompensacao,
                    $observacao
                ], ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}

