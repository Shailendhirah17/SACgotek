<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Modules\RolePermission\Entities\Permission;

class UserRolePermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function handle($request, Closure $next, $route = null)
    {
        if (! Auth::guard('web')->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // 1. Whitelist View Profile for all roles accessing their own profile
        $currentRoute = Route::current();
        if ($currentRoute) {
            $routeName = $currentRoute->getName();
            
            // Staff viewing their own profile
            if ($routeName == 'viewStaff' || $route == 'view-staff' || $route == 'viewStaff') {
                $routeId = $currentRoute->parameter('id');
                if ($user->staff && $user->staff->id == $routeId) {
                    return $next($request);
                }
            }
            
            // Student viewing their own profile
            if ($user->role_id == 2 && ($routeName == 'student-profile' || $route == 'student-profile')) {
                return $next($request);
            }
            
            // Parent viewing their own dashboard/profile
            if ($user->role_id == 3 && ($routeName == 'parent-profile' || $route == 'parent-profile')) {
                return $next($request);
            }
        }

        $permissions = app('permission');
        if ($user->role_id == 3 && Cache::get('have_due_fees_'.$user->id)) {
            $url = explode('/', $request->getRequestUri());
            $param = end($url);
            $students = Cache::get('have_due_fees_'.$user->id);

            if (in_array($param, $students) && ! in_array(Route::currentRouteName(), ['parent_fees', 'fees.student-fees-list-parent'])) {

                abort(403);
            } else {
                return $next($request);
            }
        }

        if (! $this->hasPermission($route)) {
            abort(403);
        }

        if ((! is_null($permissions)) && (!in_array(Auth::user()->role_id, [1, 10]))) {

            if (in_array($route, $permissions)) {
                return $next($request);
            }

            abort('403');

        } else {
            return $next($request);
        }

        return null;
    }

    public function hasPermission($route)
    {
        // $builder = Permission::with(['subModule']); // getPermissions();
        // $parent_module = $builder->where('route', $route)->first();
        // if (! $parent_module) {
        //     foreach ($builder as $permission) {
        //     if(!$permission){
        //         dd($route);
        //     }
        //         $children_module = $permission->subModule->where('route', $route)->first();
        //         if ($children_module) {
        //             $parent_module = $permission;
        //             break;
        //         }
        //     }
        // }
        $builder = Permission::with(['subModule']); // getPermissions();
        $parent_module = $builder->where('route', $route)->first();

        if (! $parent_module) {
            $permissions = $builder->get();
            foreach ($permissions as $permission) {
                if ($permission->subModule) {
                    $children_module = $permission->subModule->where('route', $route)->first();
                    if ($children_module) {
                        $parent_module = $permission;
                        break;
                    }
                }
            }
        }
        if ($parent_module) {
            $parent_module_id = $parent_module->id;
            // get permission name
            $school_permissions = planPermissions('menus', true);
            $key = false;
            foreach ($school_permissions as $permission => $id) {
                if ($id == $parent_module_id) {
                    $key = $permission;
                    break;
                }
            }

            if ($key) {
                return isMenuAllowToShow($key);
            }
        }

        return true;
    }
}
