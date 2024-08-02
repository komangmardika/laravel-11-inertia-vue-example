<?php

namespace App\Http\Controllers;

use App\Models\LoginHistory;
use App\Models\Position;
use App\Models\Unit;
use App\Models\User;
use App\Models\UserPosition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(): \Inertia\Response
    {
        return Inertia::render('Dashboard');
    }

    public function addNewUser(Request $request): \Illuminate\Http\JsonResponse|\Inertia\Response
    {

        $validated = $request->validate([
            'name' => 'required',
            'username' => 'required',
            'password' => 'required',
            'unit_id' => 'required',
            'join_date' => 'required',
            'positions' => 'required'
        ]);

        $unit = Unit::where('unit_name', $request->unit_id)->first();

        if(empty($unit)) {
            $unit = Unit::create(['unit_name'=> $request->unit_id]);
        }

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'unit_id' => $unit->id,
            'join_date' => $request->join_date
        ]);

        foreach ($request->positions as $p) {
            $position = Position::where('position_name', $p)->first();
            if(empty($position)) {
                $position = Position::create(['position_name' => $p]);
            }

            UserPosition::create([
                'user_id' => $user->id,
                'position_id' => $position->id,
            ]);
        }

        return response()->json($user);


    }

    public function dashboardData(Request $request): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'employee' => User::whereBetween('created_at', [$request->start, $request->end])->count(),
            'unit' => Unit::all()->count(),
            'position' => Position::all()->count(),
            'loggedIn' => LoginHistory::whereBetween('created_at', [$request->start, $request->end])->count(),
            'top10LoggedIn' => LoginHistory::query()
                                ->join('users', 'login_histories.user_id', '=', 'users.id')
                                ->select('login_histories.id', 'login_histories.created_at', 'users.name', 'users.username')
                                ->whereBetween('login_histories.created_at', [$request->start, $request->end])
                                ->orderBy('login_histories.id', 'desc')
                                ->limit(10)
                                ->get()
        ]);
    }
}

