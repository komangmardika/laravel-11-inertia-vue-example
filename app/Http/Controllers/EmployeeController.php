<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Yajra\DataTables\DataTables;

class EmployeeController extends Controller
{
    public function page(): \Inertia\Response
    {
        return Inertia::render('User');
    }

    public function employeePaginated(Request $request)
    {
        $employees = User::with('unit', 'positions')->orderBy('id', 'desc');

        // Apply search filter if provided
        if ($request->has('search')) {
            $search = $request->search;
            $employees->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%");
            });
        }

        $dataTable = DataTables::of($employees)
            ->addColumn('id', function ($employee) {
                return $employee->id;
            })
            ->addColumn('name', function ($employee) {
                return $employee->name;
            })
            ->addColumn('username', function ($employee) {
                return $employee->username;
            })
            ->addColumn('unit', function ($employee) {
                return $employee->unit->unit_name;
            })
            ->addColumn('positions', function ($employee) {
                return $employee->positions->pluck('position_name')->implode(', ');
            })
            ->addColumn('join_date', function ($employee) {
                return Carbon::parse($employee->join_date)->format('d/m/Y');
            });


        // Apply pagination and ordering
        if ($request->has('start') && $request->has('length')) {
            $start = $request->start;
            $length = $employees->count();
            $employees->skip($start)->take($length);
        }


        // Return the response in a format compatible with DataTables
        return $dataTable->make(true);
    }
}
