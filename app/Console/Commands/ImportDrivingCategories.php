<?php

namespace App\Console\Commands;

use App\DrivingCategory;
use DB;
use Illuminate\Console\Command;

class ImportDrivingCategories extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:driving-categories {path}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Импорт водительских категорий';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $path = $this->argument('path');

        if (! file_exists($path)) {
            $this->error('Файл не найден');
            return;
        }

        $handle = fopen($path, 'r');

        DB::transaction(function () use ($handle) {
            while ($line = fgetcsv($handle, 0, '|')) {
                list($name, $shortDescription, $description) = $line;

                DrivingCategory::create([
                    'name' => $name,
                    'short_description' => $shortDescription,
                    'description' => $description,
                ]);
            }
        });

        fclose($handle);

        $this->output->success('Импорт завершен!');
    }
}
