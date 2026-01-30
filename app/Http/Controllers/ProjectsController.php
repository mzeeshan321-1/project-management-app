<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class ProjectsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!auth()->user()->hasPermissionTo('view projects')) {
            abort(403, 'You do not have permission to view this page.');
        }
        $user = auth()->user();
        $tanentId = null;
        $projects = collect();

        if ($user->tanent) {
            $tanentId = $user->tanent->id;
            $projects = Project::with('client', 'tanent')
                ->where('tanent_id', $tanentId)
                ->orderBy('id', 'asc')
                ->get();
        } elseif ($user->expert) {
            $tanentId = $user->expert->tanent_id;
            $expertId = $user->expert->id;
            // Only show projects assigned to this expert
            $projects = Project::with('client', 'projectAssigns')
                ->where('tanent_id', $tanentId)
                ->whereHas('projectAssigns', function ($query) use ($expertId) {
                    $query->where('expert_id', $expertId);
                })
                ->orderBy('id', 'asc')
                ->get();
        } elseif ($user->client) {
            $tanentId = $user->client->tanent_id;
            // Only show projects for this client
            $projects = Project::with('client', 'tanent')
                ->where('tanent_id', $tanentId)
                ->where('client_id', $user->client->id)
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

        // Calculate statistics for cards
        $statistics = [
            'total_projects' => $projects->count(),
            'completed_projects' => $projects->where('status', 'completed')->count(),
            'in_progress_projects' => $projects->where('status', 'in_progress')->count(),
            'cancelled_projects' => $projects->where('status', 'cancelled')->count(),
            'inactive_projects' => $projects->where('status', 'inactive')->count(),
            'total_budget' => $projects->sum('budget'),
            'completed_budget' => $projects->where('status', 'completed')->sum('budget'),
            'overdue_projects' => $projects->filter(function ($project) {
                return $project->deadline && Carbon::parse($project->deadline)->isPast() && $project->status !== 'completed';
            })->count(),
        ];

        return view('projects.index', compact('projects', 'statistics'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clients = Client::with('user')->where('tanent_id', auth()->user()->tanent->id)->orderBy('id', 'asc')->get();
        return view('projects.create', compact('clients'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'client_id' => 'required|exists:clients,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'start_date' => 'nullable|date_format:d-M-Y|before_or_equal:deadline',
            'deadline' => 'nullable|date_format:d-M-Y|after_or_equal:start_date',
            'budget' => 'required|integer|min:0',
            'status' => 'nullable|in:in_progress,completed,inactive,cancelled',
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
            // $startDate = Carbon::createFromFormat('d-M-Y', $request->start_date)->format('Y-m-d');
            // $deadline = Carbon::createFromFormat('d-M-Y', $request->deadline)->format('Y-m-d');

            $startDate = $request->start_date
                ? Carbon::createFromFormat('d-M-Y', $request->start_date)->format('Y-m-d')
                : null;

            $deadline = $request->deadline
                ? Carbon::createFromFormat('d-M-Y', $request->deadline)->format('Y-m-d')
                : null;

            if ($request->budget <= 0) {
                flash()->options([
                    'timeout' => 3000,
                    'position' => 'bottom-center',
                ])->error("Project Budget Must Not be '0'!");
                return redirect()->back()->withInput();
            }

            Project::create([
                'tanent_id' => auth()->user()->tanent->id,
                'client_id' => $request->client_id,
                'title' => $request->title,
                'description' => $request->description,
                'start_date' => $startDate,
                'deadline' => $deadline,
                'budget' => $request->budget,
                'status' => $request->status,
            ]);

            flash()->options([
                'timeout' => 3000,
                'position' => 'bottom-center',
            ])->success('Project Created Successfully!');

            return redirect()->route('projects.index');
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
        $user = auth()->user();
        $tanentId = null;
        $expertId = null;

        if ($user->tanent) {
            $tanentId = $user->tanent->id;
        } elseif ($user->expert) {
            $tanentId = $user->expert->tanent_id;
            $expertId = $user->expert->id;
        } elseif ($user->client) {
            $tanentId = $user->client->tanent_id;
        }

        $project = Project::with(['client.user', 'tasks', 'projectAssigns.expert.user', 'payments', 'files.user.roles'])
            ->where('tanent_id', $tanentId)
            ->find($id);

        if (empty($project)) {
            flash()->options([
                'timeout' => 3000,
                'position' => 'bottom-center',
            ])->error('Project ID no: ' . $id . ' Not found!');
            return redirect()->back();
        }

        if ($project) {
            // Filter files based on user role
            if ($user->hasRole('expert')) {
                // Experts can only see files uploaded by middleman and their own files
                $project->files = $project->files->filter(function ($file) use ($user) {
                    return $file->user->hasRole('middleman') || $file->user->id === $user->id;
                });
            } elseif ($user->hasRole('client') || $user->hasRole('middleman')) {
                // Clients and middleman can see files uploaded by experts
                $project->files = $project->files->filter(function ($file) {
                    return $file->user->hasRole('expert');
                });
            }
        }

        return view('projects.show', compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $clients = Client::with('user')->where('tanent_id', auth()->user()->tanent->id)->orderBy('id', 'asc')->get();
        $project = Project::with('client')->find($id);
        if (empty($project)) {
            flash()->options([
                'timeout' => 3000,
                'position' => 'bottom-center',
            ])->error('Project ID no: ' . $id . ' Not found!');
            return redirect()->back();
        }

        $project->start_date = Carbon::createFromFormat('Y-m-d', $project->start_date)->format('d-M-Y');
        $project->deadline = Carbon::createFromFormat('Y-m-d', $project->deadline)->format('d-M-Y');

        return view('projects.edit', compact('clients', 'project'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $project = Project::with('client')->find($id);
        if (empty($project)) {
            flash()->options([
                'timeout' => 3000,
                'position' => 'bottom-center',
            ])->error('Project ID no: ' . $id . ' Not found!');
            return redirect()->back();
        }

        $validator = Validator::make($request->all(), [
            'client_id' => 'nullable|exists:clients,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'start_date' => 'nullable|date_format:d-M-Y|before_or_equal:deadline',
            'deadline' => 'nullable|date_format:d-M-Y|after_or_equal:start_date',
            'budget' => 'required|integer|min:0',
            'status' => 'nullable|in:in_progress,completed,inactive,cancelled',
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
            // $startDate = Carbon::createFromFormat('d-M-Y', $request->start_date)->format('Y-m-d');
            // $deadline = Carbon::createFromFormat('d-M-Y', $request->deadline)->format('Y-m-d');

            $startDate = $request->start_date
                ? Carbon::createFromFormat('d-M-Y', $request->start_date)->format('Y-m-d')
                : null;

            $deadline = $request->deadline
                ? Carbon::createFromFormat('d-M-Y', $request->deadline)->format('Y-m-d')
                : null;

            if ($request->budget <= 0) {
                flash()->options([
                    'timeout' => 3000,
                    'position' => 'bottom-center',
                ])->error("Project Budget Must Not be '0'!");
                return redirect()->back()->withInput();
            }

            $project->update([
                'client_id' => $request->client_id,
                'title' => $request->title,
                'description' => $request->description,
                'start_date' => $startDate,
                'deadline' => $deadline,
                'budget' => $request->budget,
                'status' => $request->status,
            ]);

            flash()->options([
                'timeout' => 3000,
                'position' => 'bottom-center',
            ])->success('Project ID no: ' . $id . ' Updated Successfully!');

            return redirect()->route('projects.index');
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
        $project = Project::with('client')->find($id);
        if (empty($project)) {
            flash()->options([
                'timeout' => 3000,
                'position' => 'bottom-center',
            ])->error('Project ID no: ' . $id . ' Not found!');
            return redirect()->back();
        }
        try {
            $project->delete();
            flash()->options([
                'timeout' => 3000, // 3 seconds
                'position' => 'bottom-center',
            ])->success('Project ID no: ' . $id . ' Deleted Successfully!');
            return redirect()->route('projects.index');
        } catch (\Exception $e) {
            flash()->options([
                'timeout' => 3000, // 3 seconds
                'position' => 'bottom-center',
            ])->error($e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Update the status of the specified resource.
     */
    public function updateStatus(Request $request, string $id)
    {
        $project = Project::find($id);
        if (empty($project)) {
            flash()->options([
                'timeout' => 3000,
                'position' => 'bottom-center',
            ])->error('Project ID no: ' . $id . ' Not found!');
            return redirect()->back();
        }

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:in_progress,completed,inactive,cancelled',
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
            $project->update([
                'status' => $request->status,
            ]);

            flash()->options([
                'timeout' => 3000,
                'position' => 'bottom-center',
            ])->success('Project ID no: ' . $id . ' Status Updated Successfully!');

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