<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Locale;
use Illuminate\Http\Request;

class LocaleController extends Controller
{
    public function index()
    {
        return response()->json(Locale::select('id', 'code', 'name')->orderBy('code')->get());
    }
}
