<?php

namespace Modules\Zoom\Repositories\Interfaces;

use Illuminate\Http\Request;

interface VirtualClassRepositoryInterface
{
    public function classStore($request);
    public function classUpdate($request, $id);
    public function classDelete($id);
    public function classShow($id);
}
