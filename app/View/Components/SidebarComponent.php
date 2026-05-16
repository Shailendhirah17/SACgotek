<?php

namespace App\View\Components;

use App\SmParent;
use Illuminate\View\Component;
use App\Traits\SidebarDataStore;
use Illuminate\Support\Facades\Auth;

class SidebarComponent extends Component
{
    use SidebarDataStore;

    public function render()
    {
        $data = [];
        $data['paid_modules'] = $this->allActivePaidModules();

        if (Auth::check()) {
            $user = Auth::user();
            if ($user->role_id == 3) {
                $data['children'] = SmParent::myChildrens();
            }
        }

        return view('components.sidebar-component', $data);
    }
}
