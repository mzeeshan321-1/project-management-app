@extends('layouts.app')

@section('title')
    <title>Create Payments</title>
@endsection

@section('content')
    <div class="pagetitle">
        <h1>Create Payments</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('payments.index') }}">Payments</a></li>
                <li class="breadcrumb-item active">Create Payment</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
    <div class="d-flex justify-content-between align-items-end mb-2">
        <div>
            <small class="text-danger fw-bold">NOTE:</small>
            <small class="text-muted fw-italic">Payments can only be made for <b>Completed Projects!</b></small>
        </div>
        <div title="Back to Payments">
            <a href="{{ route('payments.index') }}" class="btn btn-primary"><i class="ri-arrow-left-s-line"></i></a>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Payment Details</h5>
            <!-- Floating Labels Form -->
            <form method="post" action="{{ route('payments.store') }}" class="row g-3" enctype="multipart/form-data">
                @csrf
                @role('client')
                <input type="hidden" class="form-control" value="debit" name="type">
                <input type="hidden" class="form-control" value="pending" name="status">
                @endrole
                <div class="col-md-4">
                    <div class="form-floating">
                        <select class="form-select" name="project_id" id="Project" aria-label="Project" required {{ isset($selectedProject) ? 'readonly' : '' }}>
                            <option class="text-center" value="" selected disabled>--- Select a Project ---</option>
                            @if ($projects->isNotEmpty())
                                @foreach ($projects as $project)
                                    <option value="{{ $project->id }}" 
                                        {{ (isset($selectedProject) && $selectedProject->id == $project->id) || request()->query('project_id') == $project->id ? 'selected' : '' }}>
                                        {{ $project->title }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        @if(isset($selectedProject))
                            <input type="hidden" name="project_id" value="{{ $selectedProject->id }}">
                        @endif
                        <label for="Project">Project</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-floating">
                        <select class="form-select" name="reciever_id" id="Reciever" aria-label="Send To" required {{ isset($selectedReceiver) ? 'readonly' : '' }}>
                            <option class="text-center" value="" selected disabled>--- Select a Receiver ---</option>
                            @if(isset($selectedReceiver))
                                <option value="{{ $selectedReceiver->id }}" selected>
                                    {{ $selectedReceiver->first_name }} {{ $selectedReceiver->last_name }}
                                </option>
                            @elseif($users->isNotEmpty())
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" {{ request()->query('project_id') && $projects->firstWhere('id', request()->query('project_id'))->client->id == $user->client->id ? 'selected' : '' }}>
                                        {{ $user->first_name }} {{ $user->last_name }}</option>
                                @endforeach
                            @endif
                        </select>
                        @if(isset($selectedReceiver))
                            <input type="hidden" name="reciever_id" value="{{ $selectedReceiver->id }}">
                        @endif
                        <label for="Reciever">Send To</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-floating">
                        <input type="text" class="form-control" 
                            value="{{ isset($selectedProject) ? $selectedProject->budget : old('amount') }}" 
                            name="amount" id="Amount"
                            placeholder="Amount" required 
                            {{ isset($selectedProject) ? 'readonly' : '' }}>
                        <label for="Amount">Amount</label>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-floating">
                        <textarea class="form-control" name="note" id="Note" placeholder="Note" style="height: 100px;">{{ old('Note') }}</textarea>
                        <label for="Note">Note</label>
                    </div>
                </div>
                @role('middleman')
                    <div class="col-md-6">
                        <div class="form-floating">
                            <select class="form-select" name="type" id="Type" aria-label="Type" required>
                                <option value="" selected disabled class="text-center">-- Select a Type --</option>
                                <option value="debit" {{ old('type') == 'debit' ? 'selected' : '' }}>Debit
                                </option>
                                <option value="credit" {{ old('type') == 'credit' ? 'selected' : '' }}>Credit
                                </option>
                                <option value="return" {{ old('type') == 'return' ? 'selected' : '' }}>Return
                                </option>
                            </select>
                            <label for="Type">Type</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <select class="form-select" name="status" id="Status" aria-label="Status" required>
                                <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending
                                </option>
                                <option value="received" {{ old('status') == 'received' ? 'selected' : '' }}>Recieved
                                </option>
                                <option value="returned" {{ old('status') == 'returned' ? 'selected' : '' }}>Returned
                                </option>
                            </select>
                            <label for="Status">Status</label>
                        </div>
                    </div>
                @endrole
                <div class="col-md-12">
                    <input type="file" name="upload_invoice" class="form-control" id="image" title="Upload Invoice"
                        accept="image/*" area-label="Upload Invoice">
                </div>
                <div class="offset-md-4 col-md-4 mt-3">
                    <img src="" alt="Select Image" id="preview" class="img-thumbnail" style="display: none;">
                </div>
                <div class="text-center mt-5">
                    <input type="Reset" value="Reset" class="btn btn-light">
                    <input type="submit" value="Submit" class="btn btn-primary">
                </div>
            </form><!-- End floating Labels Form -->
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('#image').on('change', function(event) {
                const imageInput = event.target;
                const preview = $('#preview');

                if (imageInput.files && imageInput.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.attr('src', e.target.result);
                        preview.show();
                    };
                    reader.readAsDataURL(imageInput.files[0]);
                } else {
                    preview.attr('src', '');
                    preview.hide();
                }
            });
        });
    </script>
@endsection

