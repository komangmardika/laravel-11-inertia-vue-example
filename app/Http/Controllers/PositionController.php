<?php

namespace App\Http\Controllers;

use App\Models\LoginHistory;
use App\Models\Position;
use App\Models\Unit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Yajra\DataTables\DataTables;

class PositionController extends Controller
{
    public function index(): \Illuminate\Http\JsonResponse
    {
        return response()->json(Position::all());
    }

    public function page(): \Inertia\Response
    {
        return Inertia::render('Position');
    }

    public function positionPaginated(Request $request) {
        $positions = Position::query();
        // Apply search filter if provided
        if ($request->has('search')) {
            $search = $request->search;
            $positions->where(function ($query) use ($search) {
                $query->where('position_name', 'like', "%{$search}%");
            });
        }

        $dataTable = DataTables::of($positions)
            ->addColumn('id', function ($positions) {
                return $positions->id;
            })
            ->addColumn('name', function ($positions) {
                return $positions->position_name;
            })
            ->addColumn('created_at', function ($positions) {
                return Carbon::parse($positions->created_at)->format('d/m/Y');
            });


        // Apply pagination and ordering
        if ($request->has('start') && $request->has('length')) {
            $start = $request->start;
            $length = $positions->count();
            $positions->skip($start)->take($length);
        }


        // Return the response in a format compatible with DataTables
        return $dataTable->make(true);
    }
}
