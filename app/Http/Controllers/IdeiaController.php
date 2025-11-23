<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Ideia;
use App\Models\IdeiaComentario;
use App\Enums\IdeiaStatusEnum;

class IdeiaController extends Controller
{
    /**
     * Lista todas as ideias com filtros
     */
    public function index(Request $request)
    {
        $filtro = $request->get('filtro', 'todas');
        $busca = $request->get('busca');

        $query = Ideia::with(['usuario', 'votosUsuarios']);

        // Aplicar filtros
        switch ($filtro) {
            case 'mais-votadas':
                $query->orderBy('votos', 'desc');
                break;
            case 'novas':
                $query->orderBy('created_at', 'desc');
                break;
            case 'em-aberto':
                $query->where('status', IdeiaStatusEnum::EM_ABERTO->value);
                break;
            case 'em-analise':
                $query->where('status', IdeiaStatusEnum::EM_ANALISE->value);
                break;
            case 'em-desenvolvimento':
                $query->where('status', IdeiaStatusEnum::EM_DESENVOLVIMENTO->value);
                break;
            case 'concluido':
                $query->where('status', IdeiaStatusEnum::CONCLUIDO->value);
                break;
            case 'sem-previsao':
                $query->where('status', IdeiaStatusEnum::SEM_PREVISAO->value);
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        // Busca por título
        if ($busca) {
            $query->where('titulo', 'like', '%' . $busca . '%');
        }

        $ideias = $query->paginate(20)->appends(request()->query());

        // Verificar quais ideias o usuário já votou
        $usuarioId = Auth::id();
        $ideiasVotadas = [];
        if ($usuarioId) {
            $ideiasVotadas = Ideia::whereHas('votosUsuarios', function($q) use ($usuarioId) {
                $q->where('usuario_id', $usuarioId);
            })->pluck('id')->toArray();
        }

        return view('ideias.index', compact('ideias', 'filtro', 'busca', 'ideiasVotadas'));
    }

    /**
     * Exibe os detalhes de uma ideia
     */
    public function show(Ideia $ideia)
    {
        $ideia->load(['usuario', 'comentarios.usuario', 'votosUsuarios']);
        
        $usuarioId = Auth::id();
        $usuarioVotou = $usuarioId ? $ideia->usuarioVotou($usuarioId) : false;

        return view('ideias.show', compact('ideia', 'usuarioVotou'));
    }

    /**
     * Exibe o formulário para criar nova ideia
     */
    public function create()
    {
        $categorias = $this->getCategorias();
        return view('ideias.create', compact('categorias'));
    }

    /**
     * Salva uma nova ideia
     */
    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'required|string',
            'categoria' => 'required|string|max:255',
        ]);

        $ideia = Ideia::create([
            'usuario_id' => Auth::id(),
            'titulo' => $request->titulo,
            'descricao' => $request->descricao,
            'categoria' => $request->categoria,
            'status' => IdeiaStatusEnum::EM_ABERTO->value, // Sempre inicia como "Em Aberto"
            'votos' => 0,
        ]);

        return redirect()->route('ideias.show', $ideia)
            ->with('success', 'Ideia criada com sucesso!');
    }

    /**
     * Adiciona um comentário à ideia
     */
    public function comentar(Request $request, Ideia $ideia)
    {
        $request->validate([
            'comentario' => 'required|string|max:1000',
        ]);

        IdeiaComentario::create([
            'ideia_id' => $ideia->id,
            'usuario_id' => Auth::id(),
            'comentario' => $request->comentario,
        ]);

        return redirect()->route('ideias.show', $ideia)
            ->with('success', 'Comentário adicionado com sucesso!');
    }

    /**
     * Vota em uma ideia
     */
    public function votar(Ideia $ideia)
    {
        $usuarioId = Auth::id();

        if (!$usuarioId) {
            return response()->json([
                'success' => false,
                'message' => 'Você precisa estar logado para votar.',
            ], 401);
        }

        // Verificar se já votou antes de fazer qualquer operação
        $jaVotou = $ideia->votosUsuarios()->where('usuario_id', $usuarioId)->exists();

        if ($jaVotou) {
            // Remove o voto
            $ideia->votosUsuarios()->detach($usuarioId);
        } else {
            // Adiciona o voto
            $ideia->votosUsuarios()->attach($usuarioId);
        }

        // Recarregar a relação e atualizar votos
        $ideia->unsetRelation('votosUsuarios');
        $ideia->atualizarVotos();
        $ideia->refresh();

        // Verificar novamente se votou após a operação
        $votouAgora = $ideia->votosUsuarios()->where('usuario_id', $usuarioId)->exists();

        return response()->json([
            'success' => true,
            'votos' => $ideia->votos,
            'votou' => $votouAgora,
        ]);
    }

    /**
     * Retorna lista de categorias disponíveis
     */
    private function getCategorias(): array
    {
        return [
            'Financeiro',
            'Gestão',
            'Vendas',
            'Atendimentos',
            'Marketing',
            'E-commerce',
            'Relatórios',
            'Dashboard',
            'Usuários',
            'Configurações',
            'Outros',
        ];
    }
}
