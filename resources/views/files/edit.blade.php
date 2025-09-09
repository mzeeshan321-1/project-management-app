@extends('layouts.app')

@section('title')
    <title>Edit File Uploads</title>
@endsection

@section('content')
    <div class="pagetitle">
        <h1>Edit File Uploads</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('files.index') }}">Files</a></li>
                <li class="breadcrumb-item active">Edit File Uploads</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
    <div class="text-end mb-2" title="Back to Files">
        <a href="{{ route('files.index') }}" class="btn btn-primary"><i class="ri-arrow-left-s-line"></i></a>
    </div>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Edit File Upload Details</h5>
            <!-- Floating Labels Form -->
            <form method="post" action="{{ route('files.update', $file->id) }}" class="row g-3"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="col-md-6">
                    <div class="form-floating">
                        <select class="form-select" name="project_id" id="Project" aria-label="Project" required>
                            <option class="text-center" value="" selected disabled>--- Select a Project ---</option>
                            @if ($projects->isNotEmpty())
                                @foreach ($projects as $project)
                                    <option {{ $project->id == $file->project_id ? 'selected' : '' }}
                                        value="{{ $project->id }}">{{ $project->title }}</option>
                                @endforeach
                            @endif
                        </select>
                        <label for="Project">Project</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating">
                        <select class="form-select" name="file_type" id="FileType" aria-label="File Type" required>
                            <option selected value="document" {{ $file->file_type == 'document' ? 'selected' : '' }}>
                                Document
                            </option>
                            <option value="image" {{ $file->file_type == 'image' ? 'selected' : '' }}>Image
                            </option>
                        </select>
                        <label for="FileType">File Type</label>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-floating">
                        <textarea class="form-control" name="description" id="Description" placeholder="Description" style="height: 100px;">{{ $file->description }}</textarea>
                        <label for="Description">Description</label>
                    </div>
                </div>
                <div class="col-md-12 mt-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="input-group mt-3">
                                <div class="form-file">
                                    <input type="file" name="image" id="image" accept="document/*, image/*"
                                        class="form-file-input" style="display: none;">
                                    <label class="form-file-label border rounded" for="image">
                                        <span class="form-file-button btn btn-light">Upload</span>
                                    </label>
                                </div>
                            </div>
                            @if (!empty($file->file_name))
                                <div class="d-flex justify-content-center mt-3">
                                    <img src="{{ asset('images/' . $file->file_name) }}" alt="{{ $file->project->title }}"
                                        class="img-thumbnail" id="preview">
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
