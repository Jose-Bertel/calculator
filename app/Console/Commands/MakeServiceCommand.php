<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeServiceCommand extends Command
{
    protected $signature = 'make:service {name}';
    protected $description = 'Crear una clase de servicio';

    public function handle()
    {
        $name = $this->argument('name');
        $path = app_path("Services/{$name}Service.php");

        if (File::exists($path)) {
            $this->error("El servicio {$name} ya existe.");
            return;
        }

        $stub = <<<EOT
<?php

namespace App\Services;

class {$name}Service
{
    //
}
EOT;

        File::ensureDirectoryExists(app_path('Services'));
        File::put($path, $stub);

        $this->info("Servicio {$name} creado correctamente.");
    }
}
