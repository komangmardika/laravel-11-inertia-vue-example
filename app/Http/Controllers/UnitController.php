<?php

namespace App\Http\Controllers;

use App\Models\Position;
use App\Models\Unit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Yajra\DataTables\DataTables;

class UnitController extends Controller
{
    public function index(): \Illuminate\Http\JsonResponse
    {
        return response()->json(Unit::all());
    }

    public function page(): \Inertia\Response
    {
        return Inertia::render('Unit');
    }

    public function unitPaginated(Request $request) {
        $units = Unit::query();
        // Apply search filter if provided
        if ($request->has('search')) {
            $search = $request->search;
            $units->where(function ($query) use ($search) {
                $query->where('unit_name', 'like', "%{$search}%");
            });
        }

        $dataTable = DataTables::of($units)
            ->addColumn('id', function ($unit) {
                return $unit->id;
            })
            ->addColumn('name', function ($unit) {
                return $unit->unit_name;
            })
            ->addColumn('created_at', function ($unit) {
                return Carbon::parse($unit->created_at)->format('d/m/Y');
            });


        // Apply pagination and ordering
        if ($request->has('start') && $request->has('length')) {
            $start = $request->start;
            $length = $units->count();
            $units->skip($start)->take($length);
        }


        // Return the response in a format compatible with DataTables
        return $dataTable->make(true);
    }
}
