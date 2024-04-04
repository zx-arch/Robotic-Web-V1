@extends('cms_login.index_admin')
<!-- Memuat jQuery dari CDN -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Memuat jQuery UI dari CDN -->
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

@section('content')

<div class="container-fluid">
    <style>
        
        #drop-area {
    border: 2px dashed #ccc;
    border-radius: 10px;
    padding: 20px;
    margin-top: 20px;
    cursor: pointer;
}

#drop-area p {
    margin: 0;
    font-size: 16px;
    line-height: 20px;
}

#preview {
    max-width: 100%;
    max-height: 200px;
    margin-top: 10px;
}

#filename {
    margin-top: 10px;
}
#drop-area {
    border: 2px dashed #ccc;
    border-radius: 10px;
    padding: 20px;
    margin-top: 20px;
    cursor: pointer;
}

#drop-area p {
    margin: 0;
    font-size: 16px;
    line-height: 20px;
}


    </style>
    <div class="content">
        <div class="row">
            <div class="col-lg-12">
                <h5 class="p-2">Add Tutorials</h5>

                <div class="card card-default">
                    <div class="card-body p-0">
                        <div class="container mb-3 mt-3">
                            <form action="{{route('tutorials.save_tutorial')}}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="row">

                                    <div class="col-lg-6">
                                        <div class="row">

                                            <div class="col-md-12">

                                                <div class="form-group highlight-addon has-success">
                                                    <label for="video_name">Video Name <span class="text-danger">*</span></label>
                                                    <input type="text" name="video_name" id="video_name" required class="form-control">
                                                    <div class="invalid-feedback"></div>
                                                </div>
                                                @php
                                                    $getCategory = \App\Models\CategoryTutorial::all();
                                                @endphp
                                                <div class="form-group highlight-addon has-success">
                                                    <label for="category">Category <span class="text-danger">*</span></label>
                                                    <select name="category" id="category" class="form-control w-50" required>
                                                        <option value="" disabled selected>Choose Category ..</option>
                                                        @foreach ($getCategory as $tutorial_cat)
                                                            <option value="{{$tutorial_cat->id}}">{{$tutorial_cat->category}}</option>
                                                        @endforeach
                                                    </select>
                                                    <div class="invalid-feedback"></div>
                                                </div>

                                                <div class="form-group highlight-addon has-success">
                                                    <label for="status">Status <span class="text-danger">*</span></label>
                                                    <select name="status" id="status" class="form-control w-25" required>
                                                        <option value="" disabled selected>Status ..</option>
                                                        <option value="enable">Enable</option>
                                                        <option value="disable">Disable</option>
                                                        <option value="draft">Draft</option>
                                                    </select>
                                                    <div class="invalid-feedback"></div>
                                                </div>

                                                <div class="form-group highlight-addon has-success">
                                                    <label for="youtube">Link URL <span class="text-danger">*</span></label>
                                                    <input type="text" name="url_link" required id="url_link" required class="form-control w-75">
                                                    <div class="invalid-feedback"></div>
                                                </div>
                                                {{-- <div class="form-group highlight-addon has-success">
                                                    <label for="youtube">Thumbnail <span class="text-danger">*</span></label>
                                                    <input type="file" name="image" required id="url_link" required class="form-control w-75">
                                                    <div class="invalid-feedback"></div>
                                                </div> --}}
                                            </div>

                                        </div>
                                    </div>
                                    
                                    <div class="col-lg-4 ml-3">
                                        <div class="form-group highlight-addon has-success">
    <label for="youtube">Thumbnail <span class="text-danger">*</span></label>
    <div class="custom-file">
        <input type="file" name="image" required id="url_link" class="custom-file-input" accept="image/*">
        <label class="custom-file-label" for="url_link">Pilih Gambar</label>
    </div>
    <div class="invalid-feedback"></div>
</div>

<div class="card" id="drop-area">
    <div class="card-body">
        <p id="drop-text">Drag & drop gambar di sini</p>
        <img src="#" alt="Preview" id="preview" class="img-fluid d-none">
        <p id="filename" class="d-none"></p>
    </div>
</div>

                                    </div>
                                    

                                </div>

                                <button type="submit" class="btn btn-primary">Submit</button>

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
        // Get the input element
        const inputElement = document.querySelector('.custom-file-input');
        const labelElement = document.querySelector('.custom-file-label');
        const dropArea = document.getElementById('drop-area');
        const dropText = document.getElementById('drop-text');
        const preview = document.getElementById('preview');
        const filename = document.getElementById('filename');

        // Add event listeners for input element
        inputElement.addEventListener('change', handleFileSelect);

        // Add event listeners for drop area
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, preventDefaults, false);
        });
        ['dragenter', 'dragover'].forEach(eventName => {
            dropArea.addEventListener(eventName, highlight, false);
        });
        ['dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, unhighlight, false);
        });
        dropArea.addEventListener('drop', handleDrop, false);

        // Prevent default behavior
        function preventDefaults(event) {
            event.preventDefault();
            event.stopPropagation();
        }

        // Highlight drop area
        function highlight() {
            dropArea.classList.add('highlight');
        }

        // Unhighlight drop area
        function unhighlight() {
            dropArea.classList.remove('highlight');
        }

// Handle file select
function handleFileSelect(event) {
    const files = event.target.files;
    if (files.length > 0) {
        const file = files[0];
        const fileSize = file.size; // Dapatkan ukuran file dalam byte
        const fileType = file.type; // Dapatkan tipe file
        if (fileType === 'image/png' || fileType === 'image/jpeg') { // Periksa tipe file
            if (fileSize < 500 * 1024) { // Periksa ukuran file jika tipe file valid
                inputElement.files = files;
                labelElement.innerText = file.name;
                preview.src = URL.createObjectURL(file);
                preview.classList.remove('d-none');
                filename.innerText = file.name;
                filename.classList.remove('d-none');
                dropText.classList.add('d-none'); // Menambahkan class d-none untuk menyembunyikan teks "Drag & drop gambar di sini"
            } else {
                // Tampilkan pesan kesalahan jika ukuran file melebihi batas
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Ukuran file melebihi batas (500 KB)',
                });
                // Hapus file yang di-drop
                inputElement.value = '';
                // Reset label
                labelElement.innerText = 'Pilih Gambar';
                // Sembunyikan preview dan nama file
                preview.src = '#';
                preview.classList.add('d-none');
                filename.innerText = '';
                filename.classList.add('d-none');
            }
        } else {
            // Tampilkan pesan kesalahan jika tipe file tidak valid
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Tipe file harus PNG atau JPEG',
            });
            // Hapus file yang di-drop
            inputElement.value = '';
            // Reset label
            labelElement.innerText = 'Pilih Gambar';
            // Sembunyikan preview dan nama file
            preview.src = '#';
            preview.classList.add('d-none');
            filename.innerText = '';
            filename.classList.add('d-none');
        }
    }
}


        // Handle drop event
        function handleDrop(event) {
            const dt = event.dataTransfer;
            const files = dt.files;
            if (files.length > 0) {
                const file = files[0];
                const fileSize = file.size; // Dapatkan ukuran file dalam byte
                const fileType = file.type; // Dapatkan tipe file
                if (fileType === 'image/png' || fileType === 'image/jpeg') { // Periksa tipe file
                    if (fileSize < 500 * 1024) { // Periksa ukuran file jika tipe file valid
                        inputElement.files = files;
                        labelElement.innerText = file.name;
                        preview.src = URL.createObjectURL(file);
                        preview.classList.remove('d-none');
                        filename.innerText = file.name;
                        filename.classList.remove('d-none');
                        dropText.classList.add('d-none'); // Menambahkan class d-none untuk menyembunyikan teks "Drag & drop gambar di sini"
                    } else {
                        // Tampilkan pesan kesalahan jika ukuran file melebihi batas
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Ukuran file melebihi batas (500 KB)',
                        });
                        // Hapus file yang di-drop
                        inputElement.value = '';
                        // Reset label
                        labelElement.innerText = 'Pilih Gambar';
                        // Sembunyikan preview dan nama file
                        preview.src = '#';
                        preview.classList.add('d-none');
                        filename.innerText = '';
                        filename.classList.add('d-none');
                    }
                } else {
                    // Tampilkan pesan kesalahan jika tipe file tidak valid
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Tipe file harus PNG atau JPEG',
                    });
                    // Hapus file yang di-drop
                    inputElement.value = '';
                    // Reset label
                    labelElement.innerText = 'Pilih Gambar';
                    // Sembunyikan preview dan nama file
                    preview.src = '#';
                    preview.classList.add('d-none');
                    filename.innerText = '';
                    filename.classList.add('d-none');
                }
            }
        }

            
    });
</script>