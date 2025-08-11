<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\file;
use App\Models\Payment;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;

class FilesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        $uploadedBy = $user->id;
        $tanentId = null;

        if ($user->tanent) {
            $tanentId = $user->tanent->id;
            $files = file::with(['user', 'project'])
                ->where('tanent_id', $tanentId)
                ->orderBy('id', 'asc')
                ->get();
        } elseif ($user->expert) {
            $tanentId = $user->expert->tanent_id;
            $files = file::with(['user', 'project'])
                ->where('tanent_id', $tanentId)
                ->where('uploaded_by', $user->id)
                ->orderBy('id', 'asc')
                ->get();
        }
        
        return view('files.index', compact('files'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!auth()->user()->hasPermissionTo('manage project deliverables')) {
            abort(403, 'You do not have permission to view this page.');
        }
        $user = auth()->user();
        $tanentId = null;

        if ($user->tanent) {
            $tanentId = $user->tanent->id;
        } elseif ($user->expert) {
            $tanentId = $user->expert->tanent_id;
        }

        $projects = Project::where('tanent_id', $tanentId)->orderBy('id', 'asc')->get();
        return view('files.create', compact('projects'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'project_id' => 'required|exists:projects,id',
            'file_type' => 'required|in:document,image',
            'description' => 'nullable|string|max:1000',
            'image' => 'nullable|mimes:pdf,jpeg,png,jpg,gif|max:2048',
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
            $destinationPath = public_path('images');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true); // Create the directory if it doesn't exist
            }

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $originalName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $sanitizedOriginalName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $originalName);
                $imageName = $sanitizedOriginalName . '_' . time() . '.' . $image->getClientOriginalExtension();
                $image->move($destinationPath, $imageName);
            } else {
                flash()->options([
                    'timeout' => 3000,
                    'position' => 'bottom-center',  
                ])->error('Sorry: File is required to Upload!');
                return redirect()->back();
            }

            $user = auth()->user();
            $uploadedBy = $user->id;
            $tanentId = null;

            if ($user->tanent) {
                $tanentId = $user->tanent->id;
            } elseif ($user->expert) {
                $tanentId = $user->expert->tanent_id;
            }
            if (!$tanentId) {
                flash()->options([
                    'timeout' => 3000,
                    'position' => 'bottom-center',
                ])->error('Tenant not found for the current user.');
                return redirect()->back();
            }

            file::create([
                'project_id' => $request->project_id,
                'tanent_id' => $tanentId,
                'uploaded_by' => $uploadedBy,
                'file_name' => $imageName,
                'file_type' => $request->file_type,
                'file_url' => 'images/' . $imageName,
                'description' => $request->description,
            ]);

            flash()->options([
                'timeout' => 3000,
                'position' => 'bottom-center',
            ])->success('Document Created Successfully!');

            return redirect()->route('files.index');
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
        if (!auth()->user()->hasPermissionTo('manage project deliverables')) {
             abort(403, 'You do not have permission to view this page.');
         }

        $file = file::with('project')->find($id);
        if (empty($file)) {
            flash()->options([
                'timeout' => 3000,
                'position' => 'bottom-center',
            ])->error('Payment ID no: ' . $id . ' Not found!');
            return redirect()->back();
        }
        $user = auth()->user();
        $tanentId = null;

        if ($user->tanent) {
            $tanentId = $user->tanent->id;
        } elseif ($user->expert) {
            $tanentId = $user->expert->tanent_id;
        }

        $projects = Project::where('tanent_id', $tanentId)->orderBy('id', 'asc')->get();
        return view('files.edit', compact('projects', 'file'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $file = file::with('project')->find($id);
        if (empty($file)) {
            flash()->options([
                'timeout' => 3000,
                'position' => 'bottom-center',
            ])->error('Payment ID no: ' . $id . ' Not found!');
            return redirect()->back();
        }

        $validator = Validator::make($request->all(), [
            'project_id' => 'required|exists:projects,id',
            'file_type' => 'required|in:document,image',
            'description' => 'nullable|string|max:1000',
            'image' => 'nullable|mimes:pdf,jpeg,png,jpg,gif|max:2048',
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
            if ($request->hasFile('image')) {
                $userImage = $file->file_name;
                $imageName = null;
                if (!empty($userImage)) {
                    $existingImage = public_path('images/' . $userImage);
                    if (file_exists($existingImage)) {
                        unlink($existingImage);
                    }
                }
                $image = $request->file('image');
                $originalName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $sanitizedOriginalName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $originalName);
                $imageName = $sanitizedOriginalName . '_' . time() . '.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('images');
                $image->move($destinationPath, $imageName);
            } else {
                $imageName = $file->file_name;
            }

            $file->update([
                'project_id' => $request->project_id,
                'file_name' => $imageName,
                'file_type' => $request->file_type,
                'description' => $request->description,
            ]);

            flash()->options([
                'timeout' => 3000,
                'position' => 'bottom-center',
            ])->success('File ID No: ' . $id . ' Updated Successfully!');

            return redirect()->route('files.index');
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
         $file = file::with('project')->find($id);
        if (empty($file)) {
            flash()->options([
                'timeout' => 3000,
                'position' => 'bottom-center',
            ])->error('Payment ID no: ' . $id . ' Not found!');
            return redirect()->back();
        }

        try {
            if (!empty($file->file_name)) {
                $imagePath = public_path('images/' . $file->file_name);

                if (file_exists($imagePath)) {
                    unlink($imagePath); // Delete the image
                }
            }
            $file->delete();
            flash()->options([
                'timeout' => 3000, // 3 seconds
                'position' => 'bottom-center',
            ])->success('File ID no: ' . $id . ' Deleted Successfully!');
            return redirect()->route('files.index');
        } catch (\Exception $e) {
            flash()->options([
                'timeout' => 3000, // 3 seconds
                'position' => 'bottom-center',
            ])->error($e->getMessage());
            return redirect()->back()->withInput();
        }
    }
}
