<?php

use App\Models\Locale;
use App\Models\Translation;
use App\Services\TranslationService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(Tests\TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    $this->service = app(TranslationService::class);
});

test('can create a translation', function () {
    $locale = Locale::factory()->create(['code' => 'en']); // ✅ create locale

    $data = [
        'key' => 'welcome.message',
        'locale_id' => $locale->id, // ✅ use locale_id instead of locale
        'value' => 'Welcome!',
        'tags' => 'web,homepage',
    ];

    $translation = $this->service->create($data);

    expect($translation)
        ->toBeInstanceOf(Translation::class)
        ->and($translation->key)->toBe('welcome.message')
        ->and($translation->locale_id)->toBe($locale->id);
});

test('can search translations by key', function () {
    $locale = Locale::factory()->create(['code' => 'en']);

    Translation::factory()->create(['key' => 'home.title', 'locale_id' => $locale->id]);
    Translation::factory()->create(['key' => 'dashboard.title', 'locale_id' => $locale->id]);

    $results = $this->service->search(['key' => 'home']);

    expect($results->count())->toBe(1)
        ->and($results->first()->key)->toBe('home.title');
});

test('can update a translation', function () {
    $locale = Locale::factory()->create(['code' => 'en']);

    $translation = Translation::factory()->create([
        'key' => 'greeting.message',
        'locale_id' => $locale->id,
        'value' => 'Hello!',
    ]);

    $updatedData = [
        'value' => 'Hello, World!',
    ];

    $updatedTranslation = $this->service->update($translation->id, $updatedData);

    expect($updatedTranslation->value)->toBe('Hello, World!');
});

test('can delete a translation', function () {
    $locale = Locale::factory()->create(['code' => 'en']);

    $translation = Translation::factory()->create([
        'key' => 'farewell.message',
        'locale_id' => $locale->id,
        'value' => 'Goodbye!',
    ]);
    $this->service->delete($translation->id);
    $found = $this->service->find($translation->id);
    expect($found)->toBeNull();
});

