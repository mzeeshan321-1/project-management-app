<?php

namespace App\Http\Controllers;

use App\Models\Expert;
use App\Models\Project;
use App\Models\ProjectAssign;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!auth()->user()->hasPermissionTo('view tasks')) {
            abort(403, 'You do not have permission to view this page.');
        }
        $tasks = Task::with('project')->where('tanent_id', auth()->user()->tanent->id)->orderBy('id', 'asc')->get();
        return view('tasks.index', compact('tasks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $projects = Project::where('tanent_id', auth()->user()->tanent->id)->orderBy('id', 'asc')->get();
        return view('tasks.create', compact('projects'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'project_id' => 'required|exists:projects,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'due_date' => 'nullable|date',
            'completed_at' => 'nullable|date',
            'priority' => 'required|in:low,medium,high',
            'status' => 'required|in:pending,in_progress,completed,cancelled,on_hold',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            flash()->options([
                'timeout' => 3000, // 3 seconds
                'position' => 'bottom-center',
            ])->error('Validation failed: ' . implode(', ', $errors));
            return redirect()->back();
        }

        try {
            Task::create([
                'tanent_id' => auth()->user()->tanent->id,
                'project_id' => $request->project_id,
                'title' => $request->title,
                'description' => $request->description,
                'due_date' => $request->due_date,
                'completed_at' => $request->completed_at,
                'priority' => $request->priority,
                'status' => $request->status,
            ]);

            flash()->options([
                'timeout' => 3000,
                'position' => 'bottom-center',
            ])->success('Task Created Successfully!');

            return redirect()->route('tasks.index');
        } catch (\Exception $e) {
            flash()->options([
                'timeout' => 3000, // 3 seconds
                'position' => 'bottom-center',
            ])->error($e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $projects = Project::where('tanent_id', auth()->user()->tanent->id)->orderBy('id', 'asc')->get();
        $task = Task::find($id);
        if (empty($task)) {
            flash()->options([
                'timeout' => 3000,
                'position' => 'bottom-center',
            ])->error('Task ID no: ' . $id . ' Not found!');
            return redirect()->back();
        }
        return view('tasks.edit', compact('projects', 'task'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $task = Task::find($id);
        if (empty($task)) {
            flash()->options([
                'timeout' => 3000,
                'position' => 'bottom-center',
            ])->error('Task ID no: ' . $id . ' Not found!');
            return redirect()->back();
        }

        $validator = Validator::make($request->all(), [
            'project_id' => 'required|exists:projects,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'due_date' => 'nullable|date',
            'completed_at' => 'nullable|date',
            'priority' => 'required|in:low,medium,high',
            'status' => 'required|in:pending,in_progress,completed,cancelled,on_hold',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            flash()->options([
                'timeout' => 3000, // 3 seconds
                'position' => 'bottom-center',
            ])->error('Validation failed: ' . implode(', ', $errors));
            return redirect()->back();
        }

        try {
            $task->update([
                'project_id' => $request->project_id,
                'title' => $request->title,
                'description' => $request->description,
                'due_date' => $request->due_date,
                'completed_at' => $request->completed_at,
                'priority' => $request->priority,
                'status' => $request->status,
            ]);

            flash()->options([
                'timeout' => 3000,
                'position' => 'bottom-center',
            ])->success('Task ID no: ' . $id . ' Updated Successfully!');

            return redirect()->route('tasks.index');
        } catch (\Exception $e) {
            flash()->options([
                'timeout' => 3000, // 3 seconds
                'position' => 'bottom-center',
            ])->error($e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $task = Task::find($id);
        if (empty($task)) {
            flash()->options([
                'timeout' => 3000,
                'position' => 'bottom-center',
            ])->error('Task ID no: ' . $id . ' Not found!');
            return redirect()->back();
        }
        try {
            $task->delete();
            flash()->options([
                'timeout' => 3000, // 3 seconds
                'position' => 'bottom-center',
            ])->success('Task ID no: ' . $id . ' Deleted Successfully!');
            return redirect()->route('tasks.index');
        } catch (\Exception $e) {
            flash()->options([
                'timeout' => 3000, // 3 seconds
                'position' => 'bottom-center',
            ])->error($e->getMessage());
            return redirect()->back()->withInput();
        }
    }
}
