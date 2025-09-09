@extends('layouts.app')

@section('title')
    <title>Edit Payments Details</title>
@endsection

@section('content')
    <div class="pagetitle">
        <h1>Edit Payments Details</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('payments.index') }}">Payments</a></li>
                <li class="breadcrumb-item active">Edit Payment Details</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
    <div class="text-end mb-2" title="Back to Payments">
        <a href="{{ route('payments.index') }}" class="btn btn-primary"><i class="ri-arrow-left-s-line"></i></a>
    </div>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Payment Details</h5>
            <!-- Floating Labels Form -->
            <form method="post" action="{{ route('payments.update', $payment->id) }}" class="row g-3"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                @role('client')
                    <input type="hidden" class="form-control" value="debit" name="type">
                    <input type="hidden" class="form-control" value="pending" name="status">
                @endrole
                <div class="col-md-4">
                    <div class="form-floating">
                        <select class="form-select" name="project_id" id="Project" aria-label="Project" required>
                            <option class="text-center" value="" selected disabled>--- Select a Project ---</option>
                            @if ($projects->isNotEmpty())
                                @foreach ($projects as $project)
                                    <option {{ $payment->project_id == $project->id ? 'selected' : '' }}
                                        value="{{ $project->id }}">{{ $project->title }}</option>
                                @endforeach
                            @endif
                        </select>
                        <label for="Project">Project</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-floating">
                        <select class="form-select" name="reciever_id" id="Reciever" aria-label="Send To" required>
                            <option class="text-center" value="" selected disabled>--- Select a Reciever ---</option>
                            @if ($users->isNotEmpty())
                                @foreach ($users as $user)
                                    <option {{ $payment->reciever_id == $user->id ? 'selected' : '' }}
                                        value="{{ $user->id }}">
                                        {{ $user->first_name }} {{ $user->last_name }}</option>
                                @endforeach
                            @endif
                        </select>
                        <label for="Reciever">Send To</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-floating">
                        <input type="text" class="form-control" value="{{ $payment->amount }}" name="amount"
                            id="Amount" placeholder="Amount" required>
                        <label for="Amount">Amount</label>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-floating">
                        <textarea class="form-control" name="note" id="Note" placeholder="Note" style="height: 100px;">{{ $payment->note }}</textarea>
                        <label for="Note">Note</label>
                    </div>
                </div>
                @role('middleman')
                    <div class="col-md-6">
                        <div class="form-floating">
                            <select class="form-select" name="type" id="Type" aria-label="Type" required>
                                <option value="" selected disabled class="text-center">-- Select a Type --</option>
                                <option value="debit" {{ $payment->type == 'debit' ? 'selected' : '' }}>Debit
                                </option>
                                <option value="credit" {{ $payment->type == 'credit' ? 'selected' : '' }}>Credit
                                </option>
                                <option value="return" {{ $payment->type == 'return' ? 'selected' : '' }}>Return
                                </option>
                            </select>
                            <label for="Type">Type</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <select class="form-select" name="status" id="Status" aria-label="Status" required>
                                <option value="pending" {{ $payment->status == 'pending' ? 'selected' : '' }}>Pending
                                </option>
                                <option value="received" {{ $payment->status == 'received' ? 'selected' : '' }}>Recieved
                                </option>
                                <option value="returned" {{ $payment->status == 'returned' ? 'selected' : '' }}>Returned
                                </option>
                            </select>
                            <label for="Status">Status</label>
                        </div>
                    </div>
                @endrole
                <div class="col-md-12 mt-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="input-group mt-3">
                                <div class="form-file">
                                    <input type="file" name="upload_invoice" id="image" accept="image/*"
                                        class="form-file-input" style="display: none;">
                                    <label class="form-file-label border rounded" for="image">
                                        <span class="form-file-button btn btn-light">Upload Invoice</span>
                                    </label>
                                </div>
                            </div>
                            @if (!empty($payment->upload_invoice))
                                <div class="d-flex justify-content-center mt-3">
                                    <img src="{{ asset('images/' . $payment->upload_invoice) }}"
                                        alt="{{ $payment->project->title }}" class="img-thumbnail" id="preview">
                                </div>
                                <div id="image-name" class="text-center mt-2"></div>
                            @else
                                <div class="d-flex justify-content-center mt-3">
                                    <img src="" alt="Select Image" id="preview" class="img-thumbnail"
                                        style="display: none;">
                                </div>
                                <div id="image-name" class="text-center mt-2" style="display: none;"></div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="text-center mt-3">
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
                const imageName = $('#image-name');

                if (imageInput.files && imageInput.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.attr('src', e.target.result);
                        preview.show();
                        imageName.text(imageInput.files[0].name).show();
                    };
                    reader.readAsDataURL(imageInput.files[0]);
                } else {
                    preview.attr('src', '');
                    preview.hide();
                    imageName.text('').hide();
                }
            });
        });
    </script>
@endsection
