<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\ProjectDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class Lien
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check() && Auth::user()->checkLienProvider()) {
            if (Request::get('project_id')) {

                $project_id = Request::get('project_id');
                $project = ProjectDetail::find($project_id);
                $lienProviders = !is_null($project->user) && !is_null($project->user->lienProvider) ? $project->user->lienProvider()->pluck('lien_id')->toArray() : [];
                if (!in_array(!is_null(Auth::user()->lienUser) ? Auth::user()->lienUser->id : '', $lienProviders)) {
                    return response()->view('errors.403', [], 403);
                }
            }
            return $next($request);
        }
        return redirect()->route('member.login');
    }
}
