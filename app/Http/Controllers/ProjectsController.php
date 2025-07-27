<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;

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
        $projects = Project::with('client')->orderBy('id', 'asc')->get();
        return view('projects.index', compact('projects'));
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
            'start_date' => 'nullable|date',
            'deadline' => 'nullable|date',
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
            Project::create([
                'tanent_id' => auth()->user()->tanent->id,
                'client_id' => $request->client_id,
                'title' => $request->title,
                'description' => $request->description,
                'start_date' => $request->start_date,
                'deadline' => $request->deadline,
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
        //
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
            'start_date' => 'nullable|date',
            'deadline' => 'nullable|date',
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
             $project->update([
                'client_id' => $request->client_id,
                'title' => $request->title,
                'description' => $request->description,
                'start_date' => $request->start_date,
                'deadline' => $request->deadline,
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
}
