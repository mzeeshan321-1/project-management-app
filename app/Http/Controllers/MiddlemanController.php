<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tanent;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MiddlemanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!auth()->user()->hasPermissionTo('view middleman')) {
            abort(403, 'You do not have permission to view this page.');
        }

        $tanents = Tanent::with('user')->orderBy('id', 'asc')->get();
        return view('middleman.index', compact('tanents'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('middleman.create');

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'contact' => 'nullable|string|max:15',
            'address' => 'nullable|string|max:255',
            'status' => 'nullable|in:inactive,active,suspended',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
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
            DB::transaction(function () use ($request) {

                if (!file_exists(public_path('images'))) {
                    mkdir(public_path('images'), 0755, true); // Create the directory if it doesn't exist
                }
                if ($request->hasFile('image')) {
                    $image = $request->file('image');
                    $originalName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                    $sanitizedOriginalName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $originalName);
                    $imageName = $sanitizedOriginalName . '_' . time() . '.' . $image->getClientOriginalExtension();
                    $destinationPath = public_path('images');
                    $image->move($destinationPath, $imageName);
                } else {
                    $imageName = null;
                }
                $user = User::create([
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'contact' => $request->contact,
                    'address' => $request->address,
                    'status' => $request->status,
                    'image' => $imageName,
                ]);

                $user->tanent()->create([
                    'user_id' => $user->id,
                ]);
            });
            flash()->options([
                'timeout' => 3000,
                'position' => 'bottom-center',
            ])->success('Tanent created successfully!');

            return redirect()->route('middleman.index');
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
        $user = User::with('tanent')->find($id);
        if (empty($user)) {
            flash()->options([
                'timeout' => 3000,
                'position' => 'bottom-center',
            ])->error('Tanent ID no: ' . $id . ' Not found!');
            return redirect()->back();
        }
        return view('middleman.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::with('tanent')->find($id);
        if (empty($user)) {
            flash()->options([
                'timeout' => 3000,
                'position' => 'bottom-center',
            ])->error('Tanent ID no: ' . $id . ' Not found!');
            return redirect()->back();
        }

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
            'contact' => 'nullable|string|max:15',
            'address' => 'nullable|string|max:255',
            'status' => 'nullable|in:inactive,active,suspended',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
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
            DB::transaction(function () use ($request, $user) {

                if ($request->hasFile('image')) {
                    $userImage = $user->image;
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
                    $imageName = $user->image;
                }

                $user->update([
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'contact' => $request->contact,
                    'address' => $request->address,
                    'status' => $request->status,
                    'image' => $imageName,
                ]);

                $user->tanent()->update([
                    'user_id' => $user->id,
                ]);
            });
            flash()->options([
                'timeout' => 3000,
                'position' => 'bottom-center',
            ])->success('Tanent ID no: ' . $id . ' Updated Successfully!');

            return redirect()->route('middleman.index');
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
    public function destroy (string $id)
    {
        $user = User::with('tanent')->find($id);
        if (empty($user)) {
            flash()->options([
                'timeout' => 3000, // 3 seconds
                'position' => 'bottom-center',
            ])->error('Tanent ID no: ' . $id . ' Not found!');
            return redirect()->back();
        }
        try {
            DB::transaction(function () use ($user) {
                if (!empty($user->image)) {
                    $imagePath = public_path('images/' . $user->image);

                    if (file_exists($imagePath)) {
                        unlink($imagePath); // Delete the image
                    }
                }
                $user->delete();
                $user->tanent()->delete();
            });
            flash()->options([
                'timeout' => 3000, // 3 seconds
                'position' => 'bottom-center',
            ])->success('Tanent ID no: ' . $id . ' Deleted Successfully!');
            return redirect()->route('middleman.index');
        } catch (\Exception $e) {
            flash()->options([
                'timeout' => 3000, // 3 seconds
                'position' => 'bottom-right',
            ])->error($e->getMessage());
            return redirect()->back()->withInput();
        }
    }
}
