<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Payment;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;

class PaymentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!auth()->user()->hasPermissionTo('view payments')) {
            abort(403, 'You do not have permission to view payments.');
        }

        $sender = auth()->user()->id;

        $payments = Payment::with(['sender', 'receiver', 'project'])
            ->where('sender_id', $sender)
            ->orderBy('id', 'asc')
            ->get();
        return view('payments.index', compact('payments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!auth()->user()->hasPermissionTo('create payments')) {
            abort(403, 'You do not have permission to create payments.');
        }
        $user = auth()->user();
        $tanentId = null;

        if ($user->tanent) {
            $tanentId = $user->tanent->id;
            $users = User::whereHas('expert', function ($query) use ($tanentId) {
                $query->where('tanent_id', $tanentId);
            })
            // ->orWhereHas('client', function ($query) use ($tanentId) {
            //     $query->where('tanent_id', $tanentId);
            // })
            ->orderBy('id', 'asc')->get();
            $projects = Project::where('tanent_id', $tanentId)
                ->where('status', 'completed')
                ->orderBy('id', 'asc')->get();
        } elseif ($user->client) {
            $tanentId = $user->client->tanent_id;
            $users = User::whereHas('tanent', function ($query) use ($tanentId) {
                $query->where('id', $tanentId);
            })->orderBy('id', 'asc')->get();
            $projects = Project::whereHas('client', function ($query) use ($user) {
                $query->where('client_id', $user->client->id);
            })
            ->where('status', 'completed')
            ->orderBy('id', 'asc')->get();
        }

        if (!$tanentId) {
            flash()->options([
                'timeout' => 3000,
                'position' => 'bottom-center',
            ])->error('Tenant not found for the current user.');
            return redirect()->back();
        }
        
        return view('payments.create', compact('projects', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'project_id' => 'required|exists:projects,id',
            'reciever_id' => 'required|exists:users,id',
            'note' => 'nullable|string|max:1000',
            'type' => 'required|in:debit,credit,return',
            'amount' => 'required|numeric',
            'status' => 'nullable|in:pending,received,returned',
            'upload_invoice' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            flash()->options([
                'timeout' => 3000,
                'position' => 'bottom-center',
            ])->error('Validation failed: ' . implode(', ', $errors));
            return redirect()->back();
        }

        try {
            $project = Project::find($request->project_id);
            $projectBudget = $project->budget;
            if ($request->amount != $projectBudget) {
                flash()->options([
                    'timeout' => 3000,
                    'position' => 'bottom-center',
                ])->error("Payment Amount Must Be Equal to the project's budget.");
                return redirect()->back()->withInput();
            }

            if (!file_exists(public_path('images'))) {
                mkdir(public_path('images'), 0755, true);
            }

            if ($request->hasFile('upload_invoice')) {
                $image = $request->file('upload_invoice');
                $originalName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $sanitizedOriginalName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $originalName);
                $imageName = $sanitizedOriginalName . '_' . time() . '.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('images');
                $image->move($destinationPath, $imageName);
            } else {
                $imageName = null;
            }

            $user = auth()->user()->id;

            Payment::create([
                'sender_id' => $user,
                'project_id' => $request->project_id,
                'reciever_id' => $request->reciever_id,
                'note' => $request->note,
                'type' => $request->type,
                'amount' => $request->amount,
                'status' => $request->status,
                'upload_invoice' => $imageName
            ]);

            if (auth()->user()->hasRole('client')) {
                if ($project && $project->status === 'completed') {
                    $project->update([
                        'approval_status' => true
                    ]);
                }
            }

            flash()->options([
                'timeout' => 3000,
                'position' => 'bottom-center',
            ])->success('Payment Created Successfully!');

            return redirect()->route('payments.index');
        } catch (\Exception $e) {
            flash()->options([
                'timeout' => 3000,
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
        $payment = Payment::with('receiver')->find($id);
        if (empty($payment)) {
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
            $users = User::whereHas('expert', function ($query) use ($tanentId) {
                $query->where('tanent_id', $tanentId);
            })
            // ->orWhereHas('expert', function ($query) use ($tanentId) {
            //     $query->where('tanent_id', $tanentId);
            // })
            ->orderBy('id', 'asc')->get();
            $projects = Project::where('tanent_id', $tanentId)
                ->where('status', 'completed')
                ->orderBy('id', 'asc')->get();
        } elseif ($user->client) {
            $tanentId = $user->client->tanent_id;
            $users = User::whereHas('tanent', function ($query) use ($tanentId) {
                $query->where('id', $tanentId);
            })->orderBy('id', 'asc')->get();
            $projects = Project::whereHas('client', function ($query) use ($user) {
                $query->where('client_id', $user->client->id);
            })
            ->where('status', 'completed')
            ->orderBy('id', 'asc')->get();
        }

        if (!$tanentId) {
            flash()->options([
                'timeout' => 3000,
                'position' => 'bottom-center',
            ])->error('Tenant not found for the current user.');
            return redirect()->back();
        }

        return view('payments.edit', compact('projects', 'users', 'payment'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $payment = Payment::with('receiver')->find($id);
        if (empty($payment)) {
            flash()->options([
                'timeout' => 3000,
                'position' => 'bottom-center',
            ])->error('Payment ID no: ' . $id . ' Not found!');
            return redirect()->back();
        }

        $validator = Validator::make($request->all(), [
            'project_id' => 'required|exists:projects,id',
            'reciever_id' => 'required|exists:users,id',
            'note' => 'nullable|string|max:1000',
            'type' => 'required|in:debit,credit,return',
            'amount' => 'required|numeric',
            'status' => 'nullable|in:pending,received,returned',
            'upload_invoice' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
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
            $project = Project::find($payment->project_id);
            $projectBudget = $project->budget;

            if ($request->amount != $projectBudget) {
                flash()->options([
                    'timeout' => 3000,
                    'position' => 'bottom-center',
                ])->error("Payment Amount Must Be Equal to the project's budget.");
                return redirect()->back()->withInput();
            }

            if ($request->hasFile('upload_invoice')) {
                $userImage = $payment->upload_invoice;
                $imageName = null;
                if (!empty($userImage)) {
                    $existingImage = public_path('images/' . $userImage);
                    if (file_exists($existingImage)) {
                        unlink($existingImage);
                    }
                }
                $image = $request->file('upload_invoice');
                $originalName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $sanitizedOriginalName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $originalName);
                $imageName = $sanitizedOriginalName . '_' . time() . '.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('images');
                $image->move($destinationPath, $imageName);
            } else {
                $imageName = $payment->upload_invoice;
            }

            $payment->update([
                'project_id' => $request->project_id,
                'reciever_id' => $request->reciever_id,
                'note' => $request->note,
                'type' => $request->type,
                'amount' => $request->amount,
                'status' => $request->status,
                'upload_invoice' => $imageName,
            ]);

            flash()->options([
                'timeout' => 3000,
                'position' => 'bottom-center',
            ])->success('Payment ID no: ' . $id . ' Updated Successfully!');

            return redirect()->route('payments.index');
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
        $payment = Payment::with('receiver')->find($id);
        if (empty($payment)) {
            flash()->options([
                'timeout' => 3000,
                'position' => 'bottom-center',
            ])->error('Payment ID no: ' . $id . ' Not found!');
            return redirect()->back();
        }

        try {
            if (!empty($payment->upload_invoice)) {
                $imagePath = public_path('images/' . $payment->upload_invoice);

                if (file_exists($imagePath)) {
                    unlink($imagePath); // Delete the image
                }
            }
            $payment->delete();
            if (auth()->user()->hasRole('client')) {
                // Update project approval status to false when a payment is deleted
                $project = Project::find($payment->project_id);
                if ($project && $project->status === 'completed') {
                    $project->update(['approval_status' => false]);
                }
            }
            flash()->options([
                'timeout' => 3000, // 3 seconds
                'position' => 'bottom-center',
            ])->success('Payment ID no: ' . $id . ' Deleted Successfully!');
            return redirect()->route('payments.index');
        } catch (\Exception $e) {
            flash()->options([
                'timeout' => 3000, // 3 seconds
                'position' => 'bottom-center',
            ])->error($e->getMessage());
            return redirect()->back()->withInput();
        }
    }
}

