<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Usuario;
use App\Models\UsuarioTelefone;
use App\Services\AuditService;

class MeusDadosController extends Controller
{
    /**
     * Exibe a tela de meus dados
     */
    public function index()
    {
        $usuario = Auth::user();

        // Buscar telefone do usuário
        $telefone = $usuario->telefones()->first();
        $telefoneCompleto = $telefone ? $telefone->numero : '';

        return view('meus-dados.index', compact('usuario', 'telefoneCompleto'));
    }

    /**
     * Atualiza os dados do usuário
     */
    public function update(Request $request)
    {
        $usuario = Auth::user();

        $validator = Validator::make($request->all(), [
            'nome' => 'required|string|max:255',
            'telefone' => 'nullable|string|max:20',
            'senha_atual' => 'nullable|string',
            'nova_senha' => 'nullable|string|min:6|confirmed',
        ], [
            'nome.required' => 'O nome é obrigatório.',
            'nome.max' => 'O nome deve ter no máximo 255 caracteres.',
            'telefone.max' => 'O telefone deve ter no máximo 20 caracteres.',
            'senha_atual.required' => 'A senha atual é obrigatória para alterar a senha.',
            'nova_senha.min' => 'A nova senha deve ter pelo menos 6 caracteres.',
            'nova_senha.confirmed' => 'A confirmação da nova senha não confere.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Verificar senha atual se fornecida
        if ($request->filled('nova_senha')) {
            if (!$request->filled('senha_atual')) {
                return redirect()->back()
                    ->withErrors(['senha_atual' => 'A senha atual é obrigatória para alterar a senha.'])
                    ->withInput();
            }

            if (!Hash::check($request->senha_atual, $usuario->senha)) {
                return redirect()->back()
                    ->withErrors(['senha_atual' => 'A senha atual está incorreta.'])
                    ->withInput();
            }
        }

        // Capturar dados antigos para auditoria
        $telefoneAntigo = $usuario->telefones()->first();
        $telefoneAntigoCompleto = $telefoneAntigo ? $telefoneAntigo->numero : '';

        $dadosAntigos = [
            'nome' => $usuario->nome,
            'telefone' => $telefoneAntigoCompleto
        ];

        // Atualizar nome
        $usuario->nome = $request->nome;

        // Atualizar senha se fornecida
        if ($request->filled('nova_senha')) {
            $usuario->senha = Hash::make($request->nova_senha);
        }

        $usuario->save();

        // Atualizar telefone
        if ($request->filled('telefone')) {
            $telefone = $usuario->telefones()->first();

            if ($telefone) {
                // Atualizar telefone existente - salvar como está
                $telefone->ddi = '';
                $telefone->ddd = '';
                $telefone->numero = $request->telefone;
                $telefone->save();
            } else {
                // Criar novo telefone - salvar como está
                UsuarioTelefone::create([
                    'id' => \Illuminate\Support\Str::uuid(),
                    'usuario_id' => $usuario->id,
                    'tipo' => 'celular',
                    'ddi' => '',
                    'ddd' => '',
                    'numero' => $request->telefone,
                ]);
            }
        }

        // Capturar dados novos para auditoria
        $telefoneNovo = $usuario->telefones()->first();
        $telefoneNovoCompleto = $telefoneNovo ? $telefoneNovo->numero : '';

        $dadosNovos = [
            'nome' => $usuario->nome,
            'telefone' => $telefoneNovoCompleto
        ];

        // Log da auditoria personalizado
        self::logMeusDadosUpdate($usuario, $dadosAntigos, $dadosNovos);

        return redirect()->route('meus-dados.index')
            ->with('success', 'Seus dados foram atualizados com sucesso!');
    }

    /**
     * Log personalizado para alterações em "Meus Dados"
     */
    private static function logMeusDadosUpdate($usuario, $dadosAntigos, $dadosNovos)
    {
        // Verificar se houve mudanças reais
        $hasRealChanges = false;
        foreach ($dadosNovos as $key => $newValue) {
            $oldValue = $dadosAntigos[$key] ?? null;

            // Normalizar valores vazios para comparação
            $oldValueNormalized = self::normalizeValue($oldValue);
            $newValueNormalized = self::normalizeValue($newValue);

            if ($oldValueNormalized !== $newValueNormalized) {
                $hasRealChanges = true;
                break;
            }
        }

        // Só registrar se houve mudanças reais
        if ($hasRealChanges) {
            AuditService::logCustom(
                'UPDATE',
                'Alteração de dados pessoais',
                'Meus Dados',
                $usuario->id,
                $usuario->nome,
                [
                    'old_values' => $dadosAntigos,
                    'new_values' => $dadosNovos
                ]
            );
        }
    }

    /**
     * Normalizar valores para comparação
     */
    private static function normalizeValue($value)
    {
        if ($value === null || $value === '') {
            return '';
        }

        // Converter string numérica para inteiro se aplicável
        if (is_string($value) && is_numeric($value)) {
            return (string) intval($value);
        }

        return (string) $value;
    }
}
