<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LeaderboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $leaders = User::where('role', 'student')->orderByDesc('xp')->orderBy('name')->take(50)->get();
        $me = $request->user();
        $myRank = User::where('role', 'student')->where('xp', '>', $me->xp)->count() + 1;

        return view('leaderboard', compact('leaders', 'me', 'myRank'));
    }
}
