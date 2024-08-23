<?php

namespace App\Http\Controllers;

use App\Http\Resources\Task\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        // нетерпеливая загрузка/жадная загрузка - повторить!!!!!!
        $tasks =  TaskResource::collection(Task::with(['user', 'file'])->get());
        return inertia('Task/Index', compact('tasks'));
    }
}
