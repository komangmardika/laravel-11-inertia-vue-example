<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function index(): \Illuminate\Http\JsonResponse
    {
        return response()->json(Unit::all());
    }
}
