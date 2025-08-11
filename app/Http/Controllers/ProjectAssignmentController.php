<?php

namespace App\Http\Controllers;

use App\Models\Expert;
use App\Models\Project;
use App\Models\ProjectAssign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ProjectsController;

class ProjectAssignmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!auth()->user()->hasPermissionTo('assign projects')) {
            abort(403, 'You do not have permission to view this page.');
        }
        $project_assignments = ProjectAssign::with('expert', 'project')->where('tanent_id', auth()->user()->tanent->id)->orderBy('id', 'asc')->get();
        return view('project_assignments.index', compact('project_assignments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $projects = Project::where('tanent_id', auth()->user()->tanent->id)->orderBy('id', 'asc')->get();
        $experts = Expert::where('tanent_id', auth()->user()->tanent->id)->orderBy('id', 'asc')->get();
        return view('project_assignments.create', compact('experts', 'projects'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'project_id' => 'required|exists:projects,id',
            'expert_id' => 'required|exists:experts,id',
            'note' => 'nullable|string|max:1000',
            'budget' => 'required|integer|min:0',
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
            ProjectAssign::create([
                'tanent_id' => auth()->user()->tanent->id,
                'project_id' => $request->project_id,
                'expert_id' => $request->expert_id,
                'note' => $request->note,
                'budget' => $request->budget,
            ]);

            $projectsController = new ProjectsController();
            $projectsController->calculateProfit('');

            flash()->options([
                'timeout' => 3000,
                'position' => 'bottom-center',
            ])->success('Project Assignment Created Successfully!');

            return redirect()->route('project_assignments.index');
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
        $experts = Expert::where('tanent_id', auth()->user()->tanent->id)->orderBy('id', 'asc')->get();
        $project_assignment = ProjectAssign::find($id);
        if (empty($project_assignment)) {
            flash()->options([
                'timeout' => 3000,
                'position' => 'bottom-center',
            ])->error('Project Assignment ID no: ' . $id . ' Not found!');
            return redirect()->back();
        }
        return view('project_assignments.edit', compact('experts', 'projects', 'project_assignment'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $project_assignment = ProjectAssign::find($id);
        if (empty($project_assignment)) {
            flash()->options([
                'timeout' => 3000,
                'position' => 'bottom-center',
            ])->error('Project Assignment ID no: ' . $id . ' Not found!');
            return redirect()->back();
        }

        $validator = Validator::make($request->all(), [
            'project_id' => 'required|exists:projects,id',
            'expert_id' => 'required|exists:experts,id',
            'note' => 'nullable|string|max:1000',
            'budget' => 'required|integer|min:0',
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
            $project_assignment->update([
                'project_id' => $request->project_id,
                'expert_id' => $request->expert_id,
                'note' => $request->note,
                'budget' => $request->budget,
            ]);

            flash()->options([
                'timeout' => 3000,
                'position' => 'bottom-center',
            ])->success('Project Assignment ID no: ' . $id . ' Updated Successfully!');

            return redirect()->route('project_assignments.index');
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
        $project_assignment = ProjectAssign::find($id);
        if (empty($project_assignment)) {
            flash()->options([
                'timeout' => 3000,
                'position' => 'bottom-center',
            ])->error('Project Assignment ID no: ' . $id . ' Not found!');
            return redirect()->back();
        }
        try {
            $project_assignment->delete();
            flash()->options([
                'timeout' => 3000, // 3 seconds
                'position' => 'bottom-center',
            ])->success('Project Assignment ID no: ' . $id . ' Deleted Successfully!');
            return redirect()->route('project_assignments.index');
        } catch (\Exception $e) {
            flash()->options([
                'timeout' => 3000, // 3 seconds
                'position' => 'bottom-center',
            ])->error($e->getMessage());
            return redirect()->back()->withInput();
        }
    }
}
