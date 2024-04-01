@extends('cms_login.index_admin')
<!-- Memuat jQuery dari CDN -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Memuat jQuery UI dari CDN -->
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

@section('content')
<div class="container-fluid">
    <style>
        
        .dropzone {
            border: 2px dashed #ccc;
            padding: 20px;
            text-align: center;
            margin-top: auto;
            width: 450px;
            height: 300px;
        }

        .dropzone img {
            max-width: 70%;
            max-height: 70%;
        }

        .file-info {
            margin-top: 10px;
        }

    </style>
    <div class="content">
        <div class="row">
            <div class="col-lg-12">
                <h5 class="p-2">Edit Tutorials</h5>

                <div class="card card-default">
                    <div class="card-body p-0">
                        <div class="container mb-3 mt-3">
                            <form action="{{route('tutorials.save_update', ['video_id' => $tutorial->id])}}" method="post" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="row">

                                    <div class="col-lg-6">
                                        <div class="row">

                                            <div class="col-md-12">
                                                <img src="{{$tutorial->thumbnail}}" id="thumbnail" width="110" height="100" alt="">

                                                <div class="form-group highlight-addon has-success mt-2">
                                                    <label for="video_name">Video Name <span class="text-danger">*</span></label>
                                                    <input type="text" name="video_name" id="video_name" required class="form-control" value="{{(isset($tutorial) && $tutorial->video_name) ? $tutorial->video_name : ''}}">
                                                    <div class="invalid-feedback"></div>
                                                </div>

                                                <div class="form-group highlight-addon has-success">
                                                    <label for="category">Category <span class="text-danger">*</span></label>
                                                    <select name="category" id="category" class="form-control w-50" required>
                                                        <option value="" disabled {{(isset($tutorial) && $tutorial->category) ? '' : 'selected'}}>Choose Category ..</option>
                                                        @foreach ($getCategory as $tutorial_cat)
                                                            <option value="{{$tutorial_cat->id}}" {{(isset($tutorial) && $tutorial->tutorial_category_id == $tutorial_cat->id) ? 'selected' : ''}}>{{$tutorial_cat->category}}</option>
                                                        @endforeach
                                                    </select>
                                                    <div class="invalid-feedback"></div>
                                                </div>

                                                <div class="form-group highlight-addon has-success">
                                                    <label for="status">Status <span class="text-danger">*</span></label>
                                                    <select name="status" id="status" class="form-control w-25" required>
                                                        <option value="" disabled {{(isset($tutorial) && $tutorial->status_id) ? '' : 'selected'}}>Status ..</option>
                                                        <option value="enable" {{(isset($tutorial) && \App\Models\MasterStatus::where('id', $tutorial->status_id)->first()->id == 4) ? 'selected' : ''}}>Enable</option>
                                                        <option value="disable" {{(isset($tutorial) && \App\Models\MasterStatus::where('id', $tutorial->status_id)->first()->id == 5) ? 'selected' : ''}}>Disable</option>
                                                        <option value="draft" {{(isset($tutorial) && \App\Models\MasterStatus::where('id', $tutorial->status_id)->first()->id == 6) ? 'selected' : ''}}>Draft</option>
                                                    </select>
                                                    <div class="invalid-feedback"></div>
                                                </div>

                                                <div class="form-group highlight-addon has-success">
                                                    <label for="youtube">Link URL <span class="text-danger">*</span></label>
                                                    <input type="text" name="url_link" required id="url_link" required class="form-control w-75" value="{{(isset($tutorial) && $tutorial->url) ? $tutorial->url : ''}}">
                                                    <div class="invalid-feedback"></div>
                                                </div>
                                                
                                            </div>

                                        </div>
                                    </div>
                                    
                                    <div class="col-lg-4 ml-3">
                                        <div class="dropzone mb-3" id="dropzone">
                                            <p>Drag an image here <span class="text-danger">*</span></p>
                                        </div>
                                    </div>
                                    
                                    <input type="hidden" name="image" id="imageInput">

                                </div>

                                <button type="submit" class="btn btn-success">Update</button>

                            </form>
                            
                            @if (session()->has('error_submit_save'))
                                <div id="w6" class="alert-danger alert alert-dismissible mt-3 w-75" role="alert">
                                    {{session('error_submit_save')}}
                                    <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span></button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const dropzone = document.getElementById('dropzone');

        dropzone.addEventListener('dragover', function (event) {
            event.preventDefault();
            this.classList.add('dragover');
        });

        dropzone.addEventListener('dragleave', function () {
            this.classList.remove('dragover');
        });

        dropzone.addEventListener('drop', function (event) {
            event.preventDefault();
            this.classList.remove('dragover');
            const file = event.dataTransfer.files[0];
                
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                    
                reader.onload = function () {
                    const img = new Image();
                    img.src = reader.result;
                    dropzone.innerHTML = '';
                    dropzone.appendChild(img);

                    // Menampilkan nama dan ukuran file
                    const fileInfo = document.createElement('p');
                    fileInfo.textContent = `Name: ${file.name}, Size: ${formatBytes(file.size)}`;
                    fileInfo.classList.add('file-info'); // Tambahkan kelas untuk styling
                    dropzone.appendChild(fileInfo);
                    document.getElementById('thumbnail').src = reader.result;
                    document.getElementById('imageInput').value = reader.result;

                };

                reader.readAsDataURL(file);
            } else {
                alert('Please drop an image file.');
            }
        });

        // Fungsi untuk mengubah ukuran file menjadi format yang lebih mudah dibaca
        function formatBytes(bytes, decimals = 2) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const dm = decimals < 0 ? 0 : decimals;
            const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
        }
            
    });
</script>