<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\ProjectDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use App\User;

/**
 * Class Member for Member Middleware
 * @package App\Http\Middleware
 */
class Member
{
    /**
     * Handle an incoming request.
     *  Check a Authenticate user is member or not
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check() && Auth::user()->isMember()) {
            if (Request::route()->getName() != "member.create.project") {
                Session::forget('projectName');
                Session::forget('role');
                Session::forget('projectType');
                Session::forget('customer');
                Session::forget('state');
                Session::forget('customer_name');
            }

            if ($request->route('project_id')) {
                $project_id = $request->route('project_id');
                $project = ProjectDetail::find($project_id);
                if ($project->user_id != Auth::id()) {
//                    return response()->view('errors.403', [], 403);

                    $parentUser = User::find(Auth::id());
                    $arrayAllUsers = User::getAllChild($parentUser->id);
                    $isChild = false;
                    foreach ($arrayAllUsers as $key => $value) {
                        if($value->id == $project->user_id) {
                            $isChild = true;
                            break;
                        }
                    }
                    if($isChild) {
                        $parents = Session::get('parents');
                        $parents[Auth::id()] = $parentUser->name;
                        Session::put('parents', $parents);
                        $prUser = User::find($project->user_id);
                        Auth::login($prUser);
                        return redirect($_SERVER['REQUEST_URI']);
                    } else {
                        return response()->view('errors.403', [], 403);
                    }
                }
            } else if($request->query('parent')) {
                $parentUser = User::find($request->query('parent'));
                $loggedUser = User::find(Auth::id());
                $arrayAllUsers = User::getAllParents($loggedUser->id);
                $isParent = false;
                $parents = [];
                foreach ($arrayAllUsers as $key => $value) {
                    if($value->id == $parentUser->id) {
                        $isParent = true;
                        break;
                    } else {
                        $parents[$value->id] = $value->name;
                    }
                }
                if($isParent) {
                    Session::put('parents', $parents);
                    Auth::login($parentUser);
                    return redirect('/member');
                } else {
                    return response()->view('errors.403', [], 403);
                }
            }
            return $next($request);
        }
        return redirect()->route('member.login');
    }
}
