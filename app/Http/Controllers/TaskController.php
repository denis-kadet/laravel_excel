<?php

namespace App\Http\Controllers;

use App\Http\Resources\FailedRow\FailedRowResource;
use App\Http\Resources\Task\TaskResource;
use App\Models\FailedRow;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        // нетерпеливая загрузка/жадная загрузка - повторить!!!!!!
        $tasks =  TaskResource::collection(Task::with(['user', 'file'])->withCount('failedRows')->get());
        return inertia('Task/Index', compact('tasks'));
    }

    public function failedList(Task $task)
    {
        $failedRows = FailedRow::where('task_id', $task->id)->get();
        $failedList = FailedRowResource::collection($failedRows);
        return inertia('Task/FailedList', compact('failedList'));
    }
}
