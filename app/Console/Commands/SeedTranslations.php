<?php

namespace App\Console\Commands;

use App\Models\Locale;
use App\Models\Tag;
use App\Models\Translation;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class SeedTranslations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:seed-translations {count=100000}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed translations with associated locales and tags';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $count = (int) $this->argument('count');

        $locales = ['en', 'fr', 'es', 'de', 'zh'];
        foreach ($locales as $code) {
            Locale::firstOrCreate(['code' => $code], ['name' => strtoupper($code)]);
        }

        $tagNames = ['mobile', 'desktop', 'web'];
        foreach ($tagNames as $name) {
            Tag::firstOrCreate(['name' => $name]);
        }

        $tags = Tag::all();
        $localesByCode = Locale::pluck('id', 'code')->toArray();

        $bar = $this->output->createProgressBar($count);
        $batchSize = 1000;
        $created = 0;

        while ($created < $count) {
            $toCreate = min($batchSize, $count - $created);

            foreach (range(1, $toCreate) as $i) {
                $localeCode = $locales[array_rand($locales)];
                $localeId = $localesByCode[$localeCode];

                $translation = Translation::create([
                    'key' => 'key.' . Str::random(8) . '.' . ($created + $i),
                    'locale_id' => $localeId,
                    'value' => 'Sample translation ' . Str::random(30),
                    'meta' => json_encode(['source' => 'seed']),
                ]);

                $translation->tags()->attach(
                    $tags->random(rand(1, 3))->pluck('id')->toArray()
                );

                $bar->advance();
            }

            $created += $toCreate;
        }

        $bar->finish();
        $this->newLine();
        $this->info("Seeded {$created} translations.");
    }
}
