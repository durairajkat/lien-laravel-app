<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlanController extends Controller
{
    public function index()
    {
        $plans = Plan::all();
        return view('plans.index', compact('plans'));
    }

    public function show(Plan $plan, Request $request)
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->subscribed('basic') || $user->subscribed('pro')) {
                return redirect()->route('member.dashboard')->with('success', 'You are already subscribed');
            } else {
                return view('plans.show', compact('plan'));
            }
        } else {
            return redirect()->route('member.login')->with('error', 'You are not logged in Please Log in before processng the payment');;
        }
    }
}
