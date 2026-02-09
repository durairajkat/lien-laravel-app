<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    public function create(Request $request, Plan $plan)
    {
        $plan = Plan::findOrFail($request->get('plan'));
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->subscribed($plan->slug)) {
                return redirect()->route('member.dashboard')->with('success', 'Your plan subscribed successfully');
            } else {
                $request->user()->actual_plan = $plan->slug;
                $request->user()->save();

                $request->user()
                    ->newSubscription('trial', 'trialPlan')
                    ->create($request->stripeToken);
                return redirect()->route('member.dashboard')->with('success', 'Your plan subscribed successfully');
            }
        } else {
            return redirect()->route('member.login');
        }
    }
}
