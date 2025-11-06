<?php

namespace App\Console\Commands;

use App\Jobs\CheckDriverDocumentsExpiration;
use Illuminate\Console\Command;

class CheckExpiredDriverDocuments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'drivers:check-expired-documents';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verifica documentos vencidos de conductores y actualiza sus estados';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Verificando documentos vencidos de conductores...');

        CheckDriverDocumentsExpiration::dispatch();

        $this->info('Verificación completada exitosamente.');

        return Command::SUCCESS;
    }
}
