<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\CardTemplate;
use Illuminate\Http\Request;

class CardTemplateController extends Controller
{
    public function index()
    {
        return response()->json(CardTemplate::where('is_active', true)->orderBy('sort_order')->get());
    }

    public function show(CardTemplate $template)
    {
        return response()->json($template);
    }
}