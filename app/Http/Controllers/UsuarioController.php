<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;
use App\Models\Empresa;
use App\Models\Whitelabel;
use App\Models\UsuarioTelefone;
use App\Models\UsuarioGeral;
use App\Models\UsuarioEndereco;
use Illuminate\Support\Str;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Usuario::with('empresas', 'geral', 'enderecos', 'telefones');

        // Filtros
        if ($request->filled('nome')) {
            $query->where('nome', 'like', '%' . $request->nome . '%');
        }

        if ($request->filled('email')) {
            $query->where('email', 'like', '%' . $request->email . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('empresa_id')) {
            $query->whereHas('empresas', function($q) use ($request) {
                $q->where('empresa_id', $request->empresa_id);
            });
        }

        if ($request->filled('cpf')) {
            $query->whereHas('geral', function($q) use ($request) {
                $q->where('cpf', 'like', '%' . $request->cpf . '%');
            });
        }

        if ($request->filled('uf')) {
            $query->whereHas('enderecos', function($q) use ($request) {
                $q->where('uf', $request->uf);
            });
        }

        // Ordenação
        $sortBy = $request->get('sort_by', 'nome');
        $sortDirection = $request->get('sort_direction', 'asc');

        if (in_array($sortBy, ['nome', 'email', 'status', 'criado_em'])) {
            $query->orderBy($sortBy, $sortDirection);
        }

        $usuarios = $query->paginate(15)->withQueryString();
        $empresas = Empresa::all();

        return view('usuarios.index', compact('usuarios', 'empresas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $empresas = Empresa::all();
        return view('usuarios.create', compact('empresas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:usuario,email',
            'senha' => 'required|string|min:6|confirmed',
            'telefones' => 'required|array|min:1',
            'telefones.*.numero' => 'required|string|max:20',
            'telefones.*.tipo' => 'required|string|in:celular,residencial,comercial,whatsapp',
            'empresa_id' => 'required|exists:empresa,id',
            'status' => 'nullable|integer',
            // Campos pessoais
            'cpf' => 'nullable|string|max:255',
            'rg' => 'nullable|string|max:255',
            'data_nascimento' => 'nullable|date',
            'sexo' => 'nullable|string|in:M,F,O',
            'comissao' => 'nullable|numeric|min:0|max:100',
            'desconto_maximo' => 'nullable|numeric|min:0|max:100',
            'obs' => 'nullable|string|max:1000',
            // Campos de endereço
            'cep' => 'nullable|string|max:255',
            'logradouro' => 'nullable|string|max:255',
            'numero' => 'nullable|string|max:255',
            'complemento' => 'nullable|string|max:255',
            'bairro' => 'nullable|string|max:255',
            'uf' => 'nullable|string|max:2',
        ]);

        try {
            // Criar usuário
            $usuario = Usuario::create([
                'id' => Str::uuid()->toString(),
                'nome' => $request->nome,
                'email' => $request->email,
                'senha' => Hash::make($request->senha),
                'status' => $request->status ?? 1,
                'criado_em' => now(),
                'atualizado_em' => now(),
            ]);

            // Vincular usuário à empresa
            $usuario->empresas()->attach($request->empresa_id, [
                'principal' => 1,
                'status' => 1,
                'criado_em' => now(),
                'atualizado_em' => now(),
            ]);

            // Adicionar telefones do usuário
            foreach ($request->telefones as $telefone) {
                // Remover máscara do telefone
                $numeroLimpo = preg_replace('/\D/', '', $telefone['numero']);

                UsuarioTelefone::create([
                    'id' => Str::uuid()->toString(),
                    'usuario_id' => $usuario->id,
                    'tipo' => $telefone['tipo'],
                    'ddd' => substr($numeroLimpo, 0, 2),
                    'numero' => substr($numeroLimpo, 2),
                    'criado_em' => now(),
                    'atualizado_em' => now(),
                ]);
            }

            // Adicionar informações pessoais
            if ($request->cpf || $request->rg || $request->data_nascimento || $request->sexo || $request->comissao || $request->desconto_maximo || $request->obs) {
                UsuarioGeral::create([
                    'id' => Str::uuid()->toString(),
                    'usuario_id' => $usuario->id,
                    'cpf' => $request->cpf,
                    'rg' => $request->rg,
                    'data_nascimento' => $request->data_nascimento,
                    'sexo' => $request->sexo,
                    'comissao' => $request->comissao,
                    'desconto_maximo' => $request->desconto_maximo,
                    'obs' => $request->obs,
                    'criado_em' => now(),
                    'atualizado_em' => now(),
                ]);
            }

            // Adicionar endereço
            if ($request->cep || $request->logradouro || $request->numero || $request->complemento || $request->bairro || $request->uf) {
                UsuarioEndereco::create([
                    'id' => Str::uuid()->toString(),
                    'usuario_id' => $usuario->id,
                    'cep' => $request->cep,
                    'logradouro' => $request->logradouro,
                    'numero' => $request->numero,
                    'complemento' => $request->complemento,
                    'bairro' => $request->bairro,
                    'uf' => $request->uf,
                    'criado_em' => now(),
                    'atualizado_em' => now(),
                ]);
            }

            return redirect()->route('usuarios.index')
                ->with('success', 'Usuário criado com sucesso!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erro ao criar usuário: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Usuario $usuario)
    {
        $usuario->load('empresas', 'telefones', 'geral', 'enderecos');
        return view('usuarios.show', compact('usuario'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Usuario $usuario)
    {
        $empresas = Empresa::all();
        $usuario->load('empresas', 'telefones', 'geral', 'enderecos');
        return view('usuarios.edit', compact('usuario', 'empresas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Usuario $usuario)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:usuario,email,' . $usuario->id,
            'senha' => 'nullable|string|min:6|confirmed',
            'telefones' => 'required|array|min:1',
            'telefones.*.numero' => 'required|string|max:20',
            'telefones.*.tipo' => 'required|string|in:celular,residencial,comercial,whatsapp',
            'empresa_id' => 'required|exists:empresa,id',
            'status' => 'nullable|integer',
            // Campos pessoais
            'cpf' => 'nullable|string|max:255',
            'rg' => 'nullable|string|max:255',
            'data_nascimento' => 'nullable|date',
            'sexo' => 'nullable|string|in:M,F,O',
            'comissao' => 'nullable|numeric|min:0|max:100',
            'desconto_maximo' => 'nullable|numeric|min:0|max:100',
            'obs' => 'nullable|string|max:1000',
            // Campos de endereço
            'cep' => 'nullable|string|max:255',
            'logradouro' => 'nullable|string|max:255',
            'numero' => 'nullable|string|max:255',
            'complemento' => 'nullable|string|max:255',
            'bairro' => 'nullable|string|max:255',
            'uf' => 'nullable|string|max:2',
        ]);

        try {
            $usuario->update([
                'nome' => $request->nome,
                'email' => $request->email,
                'senha' => $request->senha ? Hash::make($request->senha) : $usuario->senha,
                'status' => $request->status ?? $usuario->status,
                'atualizado_em' => now(),
            ]);

            // Atualizar vínculo com empresa
            $usuario->empresas()->sync([$request->empresa_id => [
                'principal' => 1,
                'status' => 1,
                'criado_em' => now(),
                'atualizado_em' => now(),
            ]]);

            // Atualizar telefones
            $usuario->telefones()->delete(); // Remove todos os telefones existentes

            foreach ($request->telefones as $telefone) {
                // Remover máscara do telefone
                $numeroLimpo = preg_replace('/\D/', '', $telefone['numero']);

                UsuarioTelefone::create([
                    'id' => Str::uuid()->toString(),
                    'usuario_id' => $usuario->id,
                    'tipo' => $telefone['tipo'],
                    'ddd' => substr($numeroLimpo, 0, 2),
                    'numero' => substr($numeroLimpo, 2),
                    'criado_em' => now(),
                    'atualizado_em' => now(),
                ]);
            }

            // Atualizar informações pessoais
            $geral = $usuario->geral()->first();
            if ($geral) {
                $geral->update([
                    'cpf' => $request->cpf,
                    'rg' => $request->rg,
                    'data_nascimento' => $request->data_nascimento,
                    'sexo' => $request->sexo,
                    'comissao' => $request->comissao,
                    'desconto_maximo' => $request->desconto_maximo,
                    'obs' => $request->obs,
                    'atualizado_em' => now(),
                ]);
            } else if ($request->cpf || $request->rg || $request->data_nascimento || $request->sexo || $request->comissao || $request->desconto_maximo || $request->obs) {
                UsuarioGeral::create([
                    'id' => Str::uuid()->toString(),
                    'usuario_id' => $usuario->id,
                    'cpf' => $request->cpf,
                    'rg' => $request->rg,
                    'data_nascimento' => $request->data_nascimento,
                    'sexo' => $request->sexo,
                    'comissao' => $request->comissao,
                    'desconto_maximo' => $request->desconto_maximo,
                    'obs' => $request->obs,
                    'criado_em' => now(),
                    'atualizado_em' => now(),
                ]);
            }

            // Atualizar endereço
            $endereco = $usuario->enderecos()->first();
            if ($endereco) {
                $endereco->update([
                    'cep' => $request->cep,
                    'logradouro' => $request->logradouro,
                    'numero' => $request->numero,
                    'complemento' => $request->complemento,
                    'bairro' => $request->bairro,
                    'uf' => $request->uf,
                    'atualizado_em' => now(),
                ]);
            } else if ($request->cep || $request->logradouro || $request->numero || $request->complemento || $request->bairro || $request->uf) {
                UsuarioEndereco::create([
                    'id' => Str::uuid()->toString(),
                    'usuario_id' => $usuario->id,
                    'cep' => $request->cep,
                    'logradouro' => $request->logradouro,
                    'numero' => $request->numero,
                    'complemento' => $request->complemento,
                    'bairro' => $request->bairro,
                    'uf' => $request->uf,
                    'criado_em' => now(),
                    'atualizado_em' => now(),
                ]);
            }

            return redirect()->route('usuarios.index')
                ->with('success', 'Usuário atualizado com sucesso!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erro ao atualizar usuário: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Usuario $usuario)
    {
        try {
            $usuario->delete();
            return redirect()->route('usuarios.index')
                ->with('success', 'Usuário excluído com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erro ao excluir usuário: ' . $e->getMessage());
        }
    }
}
