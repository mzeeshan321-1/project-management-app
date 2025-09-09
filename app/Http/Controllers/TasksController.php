<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

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
        $user = auth()->user();
        $tanentId = null;
        $tasks = collect();

        if ($user->tanent) {
            $tanentId = $user->tanent->id;
            $tasks = Task::with('expert', 'project')
                ->where('tanent_id', $tanentId)
                ->orderBy('id', 'asc')
                ->get();
        } elseif ($user->expert) {
            $tanentId = $user->expert->tanent_id;
            $expertId = $user->expert->id;
            // Only show projects assigned to this expert
            $tasks = Task::with('expert', 'project')
                ->where('tanent_id', $tanentId)
                ->whereHas('project', function ($query) use ($expertId) {
                    $query->whereHas('projectAssigns', function ($query) use ($expertId) {
                        $query->where('expert_id', $expertId);
                    });
                })
                ->orderBy('id', 'asc')
                ->get();
        }
        if (!$tanentId) {
            flash()->options([
                'timeout' => 3000,
                'position' => 'bottom-center',
            ])->error('Tenant not found for the current user.');
            return redirect()->back();
        }

        $projects = Project::where('tanent_id', $tanentId)->get();
        // Calculate statistics for cards
        $statistics = [
            'total_projects' => $projects->count(),
            'total_tasks' => $tasks->count(),
            'pending_tasks' => $tasks->where('status', 'pending')->count(),
            'on_hold_tasks' => $tasks->where('status', 'on_hold')->count(),
            'completed_tasks' => $tasks->where('status', 'completed')->count(),
            'in_progress_tasks' => $tasks->where('status', 'in_progress')->count(),
            'cancelled_tasks' => $tasks->where('status', 'cancelled')->count(),
            'overdue_tasks' => $tasks->filter(function ($task) {
                return $task->due_date && Carbon::parse($task->due_date)->gt(Carbon::today()) && $task->status !== 'completed';
            })->count(),
        ];

        return view('tasks.index', compact('tasks', 'statistics'));
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
            'due_date' => 'nullable|date_format:d-M-Y|after_or_equal:today',
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
            $dueDate = Carbon::createFromFormat('d-M-Y', $request->due_date)->format('Y-m-d');

            Task::create([
                'tanent_id' => auth()->user()->tanent->id,
                'project_id' => $request->project_id,
                'title' => $request->title,
                'description' => $request->description,
                'due_date' => $dueDate,
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
        $task = Task::find($id);
        if (empty($task)) {
            flash()->options([
                'timeout' => 3000,
                'position' => 'bottom-center',
            ])->error('Task ID no: ' . $id . ' Not found!');
            return redirect()->back();
        }
        return view('tasks.show', compact('task'));
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
        $task->due_date = Carbon::createFromFormat('Y-m-d', $task->due_date)->format('d-M-Y');

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
            'due_date' => 'nullable|date_format:d-M-Y|after_or_equal:today',
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
            $dueDate = Carbon::createFromFormat('d-M-Y', $request->due_date)->format('Y-m-d');

            $task->update([
                'project_id' => $request->project_id,
                'title' => $request->title,
                'description' => $request->description,
                'due_date' => $dueDate,
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

    public function updateStatus(Request $request, string $id)
    {
        $task = Task::find($id);
        if (empty($task)) {
            flash()->options([
                'timeout' => 3000,
                'position' => 'bottom-center',
            ])->error('Task ID no: ' . $id . ' Not found!');
            return redirect()->back();
        }

        $user = auth()->user();

        // Determine validation rules based on user role
        $validationRules = [
            'status' => 'required|in:in_progress,completed,pending,cancelled,on_hold',
        ];

        // Add priority validation only for middleman role
        if ($user->hasRole('middleman')) {
            $validationRules['priority'] = 'required|in:low,medium,high';
        }

        $validator = Validator::make($request->all(), $validationRules);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            flash()->options([
                'timeout' => 3000, // 3 seconds
                'position' => 'bottom-center',
            ])->error('Validation failed: ' . implode(', ', $errors));
            return redirect()->back();
        }

        try {
            // Prepare update data based on user role
            $updateData = ['status' => $request->status];

            // Only update priority if user has middleman role
            if ($user->hasRole('middleman') && $request->has('priority')) {
                $updateData['priority'] = $request->priority;
            }

            $task->update($updateData);

            flash()->options([
                'timeout' => 3000,
                'position' => 'bottom-center',
            ])->success('Task ID no: ' . $id . ' Status Updated Successfully!');

            return redirect()->back();
        } catch (\Exception $e) {
            flash()->options([
                'timeout' => 3000, // 3 seconds
                'position' => 'bottom-center',
            ])->error($e->getMessage());
            return redirect()->back()->withInput();
        }
    }

}
