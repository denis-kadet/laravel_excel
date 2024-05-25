<?php

namespace App\Http\Controllers;

use App\Http\Requests\Project\ImportStoreRequest;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        return inertia('Project/Index');
    }

    public function import()
    {
        return inertia('Project/Import');
    }

    public function importStore(ImportStoreRequest $request)
    {
        $data = $request->validated();
        dd($data);
    }
}
