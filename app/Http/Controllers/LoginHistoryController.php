<?php

namespace App\Http\Controllers;

use App\Models\LoginHistory;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Yajra\DataTables\DataTables;

class LoginHistoryController extends Controller
{
    public function page(): \Inertia\Response
    {
        return Inertia::render('LoginHistory');
    }

    public function loginHistoryPaginated(Request $request): \Illuminate\Http\JsonResponse
    {
        $logs = LoginHistory::query()
            ->join('users', 'login_histories.user_id', '=', 'users.id')
            ->select('login_histories.id', 'login_histories.created_at', 'users.name', 'users.username')
            ->orderBy('login_histories.id', 'desc');

        // Apply search filter if provided
        if ($request->has('search')) {
            $search = $request->search;
            $logs->where(function ($query) use ($search) {
                $query->where('users.name', 'like', "%{$search}%")
                    ->orWhere('users.username', 'like', "%{$search}%");
            });
        }

        $dataTable = DataTables::of($logs)
            ->addColumn('id', function ($logs) {
                return $logs->id;
            })
            ->addColumn('name', function ($logs) {
                return $logs->name;
            })
            ->addColumn('username', function ($logs) {
                return $logs->username;
            })
            ->addColumn('created_at', function ($logs) {
                return Carbon::parse($logs->created_at)->format('d/m/Y');
            });


        // Apply pagination and ordering
        if ($request->has('start') && $request->has('length')) {
            $start = $request->start;
            $length = $logs->count();
            $logs->skip($start)->take($length);
        }


        // Return the response in a format compatible with DataTables
        return $dataTable->make(true);
    }


}
