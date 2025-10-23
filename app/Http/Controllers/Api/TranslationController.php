<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Translation;
use App\Services\TranslationService;
use Illuminate\Http\Request;

class TranslationController extends Controller
{
    protected TranslationService $translationService;

    public function __construct(TranslationService $translationService)
    {
        $this->translationService = $translationService;
    }

    public function index(Request $request)
    {
        $translations = $this->translationService->search($request->query());
        return response()->json($translations, 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'key' => 'required|string|unique:translations',
            'locale' => 'required|string',
            'value' => 'required|string',
            'tags' => 'nullable|string'
        ]);

        $translation = $this->translationService->create($validated);

        return response()->json($translation, 201);
    }

    public function show($id)
    {
        $translation = $this->translationService->find($id);

        if (!$translation) {
            return response()->json(['message' => 'Not found'], 404);
        }

        return response()->json($translation);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'key' => 'sometimes|string',
            'locale' => 'sometimes|string',
            'value' => 'sometimes|string',
            'tags' => 'nullable|string'
        ]);

        $translation = $this->translationService->update($id, $validated);

        return response()->json($translation, 200);
    }

    public function destroy($id)
    {
        $this->translationService->delete($id);
        return response()->json(['message' => 'Deleted'], 204);
    }

    public function exportJson(Request $request)
    {
        $perPage = $request->query('per_page', 1000);

        $data = $this->translationService->export($perPage);

        return response()->json($data, 200);
    }
}