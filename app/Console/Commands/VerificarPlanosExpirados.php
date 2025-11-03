<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\PlanoService;

class VerificarPlanosExpirados extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'planos:verificar-expirados';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verifica e atualiza o status de planos expirados';

    protected $planoService;

    public function __construct(PlanoService $planoService)
    {
        parent::__construct();
        $this->planoService = $planoService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Verificando planos expirados...');

        $planosExpirados = $this->planoService->atualizarPlanosExpirados();

        if ($planosExpirados > 0) {
            $this->info("Atualizados {$planosExpirados} planos expirados.");
        } else {
            $this->info('Nenhum plano expirado encontrado.');
        }

        // Verificar planos próximos do vencimento
        $planosProximosVencimento = $this->planoService->verificarPlanosProximosVencimento();

        if (count($planosProximosVencimento) > 0) {
            $this->warn('Planos próximos do vencimento:');
            foreach ($planosProximosVencimento as $plano) {
                $this->line("- Empresa: {$plano['empresa']['nome_fantasia']} | Plano: {$plano['plano']['nome']} | Vence em: {$plano['data_fim']}");
            }
        }

        $this->info('Verificação concluída!');
    }
}
