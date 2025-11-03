<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quadra;
use App\Models\Empreendimento;
use App\Helpers\PermissionHelper;
use Illuminate\Support\Facades\Auth;

class QuadraController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Empreendimento $empreendimento)
    {
        if (!Auth::user()->temPermissao('quadra', 'listar')) {
            abort(403, 'Você não tem permissão para listar quadras.');
        }

        $empresaPrincipal = PermissionHelper::getEmpresaPrincipal();
        if (!$empresaPrincipal || $empreendimento->empresa_id !== $empresaPrincipal->id) {
            abort(403, 'Empreendimento não encontrado.');
        }

        $query = Quadra::where('empreendimento_id', $empreendimento->id);

        // Filtros
        $filtroNome = $request->get('nome');
        if ($filtroNome) {
            $query->where('nome', 'like', "%{$filtroNome}%");
        }

        // Ordenação
        $sortBy = $request->get('sort_by');
        $sortDirection = $request->get('sort_direction', 'asc');

        if ($sortBy) {
            $query->orderBy($sortBy, $sortDirection);
        } else {
            $query->orderBy('nome', 'asc');
        }

        // Paginação
        $perPage = 20;
        $quadras = $query->paginate($perPage)->appends($request->query());

        return view('quadra.index', compact('empreendimento', 'quadras', 'filtroNome'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Empreendimento $empreendimento)
    {
        if (!Auth::user()->temPermissao('quadra', 'criar')) {
            abort(403, 'Você não tem permissão para criar quadras.');
        }

        $empresaPrincipal = PermissionHelper::getEmpresaPrincipal();
        if (!$empresaPrincipal || $empreendimento->empresa_id !== $empresaPrincipal->id) {
            abort(403, 'Empreendimento não encontrado.');
        }

        $validated = $request->validate([
            'quantidade' => 'required|integer|min:1|max:100',
        ]);

        $quantidade = (int)$validated['quantidade'];
        $quadrasCriadas = [];

        // Buscar quadras existentes uma vez
        $quadrasExistentes = Quadra::where('empreendimento_id', $empreendimento->id)
            ->orderBy('nome')
            ->get();

        $tipoNumeracao = $empreendimento->quadra_numeracao_tipo ?? 1; // 1: Numérica, 2: Alfanumérica

        // Criar múltiplas quadras
        for ($i = 0; $i < $quantidade; $i++) {
            $nome = $this->gerarProximoNomeQuadra($quadrasExistentes, $tipoNumeracao);

            $quadra = Quadra::create([
                'nome' => $nome,
                'empreendimento_id' => $empreendimento->id
            ]);

            // Adicionar à lista de existentes para próxima iteração
            $quadrasExistentes->push($quadra);
            $quadrasCriadas[] = $nome;
        }

        $mensagem = $quantidade == 1
            ? 'Quadra "' . $quadrasCriadas[0] . '" criada com sucesso.'
            : $quantidade . ' quadras criadas com sucesso: ' . implode(', ', $quadrasCriadas) . '.';

        return redirect()->route('quadras.index', $empreendimento->id)->with('success', $mensagem);
    }

    /**
     * Gera o nome da quadra automaticamente baseado no tipo de numeração
     */
    private function gerarNomeQuadra(Empreendimento $empreendimento): string
    {
        // Buscar todas as quadras vinculadas a este empreendimento
        $quadrasExistentes = Quadra::where('empreendimento_id', $empreendimento->id)
            ->orderBy('nome')
            ->get();

        $tipoNumeracao = $empreendimento->quadra_numeracao_tipo ?? 1;

        return $this->gerarProximoNomeQuadra($quadrasExistentes, $tipoNumeracao);
    }

    /**
     * Gera o próximo nome de quadra baseado nas existentes
     */
    private function gerarProximoNomeQuadra($quadrasExistentes, int $tipoNumeracao): string
    {
        if ($tipoNumeracao == 2) {
            // Alfanumérica: A, B, C, D, ...
            $letras = range('A', 'Z');
            $ultimaLetra = null;
            $ultimoIndice = -1;

            foreach ($quadrasExistentes as $quadra) {
                $nome = trim(strtoupper($quadra->nome));
                // Verificar se é uma letra única (A-Z)
                if (strlen($nome) == 1 && preg_match('/^[A-Z]$/', $nome)) {
                    $indice = array_search($nome, $letras);
                    if ($indice !== false && $indice > $ultimoIndice) {
                        $ultimaLetra = $nome;
                        $ultimoIndice = $indice;
                    }
                }
            }

            // Encontrar próxima letra
            if ($ultimoIndice !== false && $ultimoIndice < 25) {
                return $letras[$ultimoIndice + 1];
            } else {
                // Se não há quadras ou chegou no Z, começar com A
                return $ultimaLetra === 'Z' ? 'AA' : 'A';
            }
        } else {
            // Numérica: 1, 2, 3, 4, ...
            $ultimoNumero = 0;

            foreach ($quadrasExistentes as $quadra) {
                $nome = trim($quadra->nome);
                // Verificar se o nome é apenas números
                if (preg_match('/^\d+$/', $nome)) {
                    $numero = (int)$nome;
                    if ($numero > $ultimoNumero) {
                        $ultimoNumero = $numero;
                    }
                }
            }

            return (string)($ultimoNumero + 1);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Quadra $quadra)
    {
        if (!Auth::user()->temPermissao('quadra', 'deletar')) {
            abort(403, 'Você não tem permissão para deletar quadras.');
        }

        $empresaPrincipal = PermissionHelper::getEmpresaPrincipal();
        if (!$empresaPrincipal || !$quadra->empreendimento || $quadra->empreendimento->empresa_id !== $empresaPrincipal->id) {
            abort(403, 'Quadra não encontrada.');
        }

        // Verificar se há lotes usando esta quadra
        if ($quadra->lotes()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Não é possível deletar esta quadra pois existem lotes vinculados a ela.');
        }

        $quadra->delete();

        return redirect()->back()
            ->with('success', 'Quadra deletada com sucesso.');
    }
}
