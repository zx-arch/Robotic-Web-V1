@extends('cms_login.index_admin')
<!-- Memuat jQuery dari CDN -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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
        max-height: 100px;
        margin-top: 10px;
    }

    #filename {
        margin-top: 10px;
    }

    .center-content {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100%;
    }

    #drop-text, #filename {
        text-align: center;
    }
    /* Gaya untuk progress bar */
    .progress {
        position: relative;
        height: 7rem;
        overflow: hidden;
        border-radius: .25rem;
    }

    /* Gaya untuk progress bar yang sedang berjalan */
    .progress-bar {
        background-color: #007bff;
    }

    /* Gaya untuk progress bar yang bergerak */
    .progress-bar-striped {
        background-image: linear-gradient(45deg, rgba(255, 255, 255, .15) 25%, transparent 0, transparent 50%, rgba(255, 255, 255, .15) 0, rgba(255, 255, 255, .15) 75%, transparent 0, transparent);
        background-size: 1rem 1rem;
    }

    /* Animasi progress bar */
    .progress-bar-animated {
        animation: progress-bar-stripes 1s linear infinite;
    }

    /* Kunci animasi */
    @keyframes progress-bar-stripes {
        from {
            background-position: 1rem 0;
        }
        to {
            background-position: 0 0;
        }
    }

</style>

<!-- Memuat jQuery UI dari CDN -->
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

@section('content')

    <div class="container-fluid">
        <div class="content">
            <div class="row">
                <div class="col-lg-12">
                    <h5 class="p-2">Add Courses</h5>

                    <div class="card card-default">
                        <div class="card-body p-0">
                            <div class="container mb-3 mt-3">
                                <form action="{{route('admin.courses.save_courses')}}" id="form" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">

                                        <div class="col-lg-6">
                                            <div class="row">

                                                <div class="col-md-12">

                                                    <div class="form-group highlight-addon has-success">
                                                        <label for="book_title">Book Title <span class="text-danger">*</span></label>
                                                        <input type="text" name="book_title" id="book_title" required class="form-control" style="max-width: 90%;">
                                                        <div class="invalid-feedback"></div>
                                                    </div>

                                                    <div class="form-group highlight-addon has-success">
                                                        <label for="terjemahan">Terjemahan <span class="text-danger">*</span></label>
                                                        <select name="terjemahan" id="terjemahan" class="form-control w-50" required onclick="showSearch()">
                                                            <option value="" disabled selected>Terjemahan ..</option>
                                                            @foreach ($getTerjemahan as $terjemahan)
                                                                <option value="{{$terjemahan->id}}">{{$terjemahan->language_name}}</option>
                                                            @endforeach
                                                        </select>
                                                        <input type="text" name="search[terjemahan]" class="form-control" id="terjemahan_search" style="display: none;" placeholder="Search ..">
                                                        <div class="invalid-feedback"></div>
                                                    </div>

                                                    <div class="form-group highlight-addon has-success">
                                                        <label for="pages">Total Pages <span class="text-danger">*</span></label>
                                                        <input type="text" name="pages" id="pages" required class="form-control w-25">
                                                        <div class="invalid-feedback"></div>
                                                    </div>

                                                    <div class="form-group highlight-addon has-success">
                                                        <label for="status_id">Status <span class="text-danger">*</span></label>
                                                        <select name="status_id" id="status_id" class="form-control w-50" required onclick="showSearch()">
                                                            <option value="" disabled selected>Status ..</option>
                                                            <option value="1">Enable</option>
                                                            <option value="2">Disable</option>
                                                            <option value="3">Draft</option>
                                                        </select>
                                                        <input type="text" name="search[terjemahan]" class="form-control" id="terjemahan_search" style="display: none;" placeholder="Search ..">
                                                        <div class="invalid-feedback"></div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="form-group highlight-addon has-success">
                                                <label for="youtube">Upload File <span class="text-danger">*</span></label>
                                                <div class="custom-file">
                                                    <input type="file" name="ebook_file" required id="url_link" class="custom-file-input" accept=".pdf" maxlength="52428800">
                                                    <label class="custom-file-label" for="url_link">Pilih PDF</label>
                                                </div>
                                                <div class="invalid-feedback"></div>
                                            </div>

                                            <div class="card" id="drop-area">
                                                <div class="card-body">
                                                    <div class="center-content">
                                                        <p id="drop-text">Drag & drop Ebook PDF di sini <br> <br> max upload 50 MB</p>
                                                        <img src="{{ asset('assets/img/logo/pdf-icon.jpg')}}" alt="Preview" id="preview" class="img-fluid d-none">
                                                        <p id="filename" class="d-none"></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div id="progress" class="progress mt-3 mb-2" style="height: 30px;display:none;">
                                        <div id="progress-bar" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width: 0%; height: 100%; line-height: 30px;">
                                            <span id="status">0% uploaded... please wait</span>
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-primary mt-2 mb-2">Submit</button>

                                </form>

                                <p id="success_upload" class="text-success" style="font-weight: bold;"></p>
                                
                                <p id="error_upload" class="text-danger" style="font-weight: bold;"></p>
                                
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
                if (fileType === 'application/pdf') { // Periksa tipe file
                    if (fileSize < 50 * 1024 * 1024) { // Periksa ukuran file jika tipe file valid (maksimal 5 MB)
                        inputElement.files = files;
                        labelElement.innerText = file.name;
                        preview.classList.remove('d-none');
                        filename.innerText = file.name;
                        filename.classList.remove('d-none');
                        dropText.classList.add('d-none'); // Menambahkan class d-none untuk menyembunyikan teks "Drag & drop gambar di sini"
                    } else {
                        // Tampilkan pesan kesalahan jika ukuran file melebihi batas
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Ukuran file melebihi batas (50 MB)',
                        });
                        // Hapus file yang di-drop
                        inputElement.value = '';
                        // Reset label
                        labelElement.innerText = 'Pilih PDF';
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
                        text: 'Tipe file harus PDF',
                    });
                    // Hapus file yang di-drop
                    inputElement.value = '';
                    // Reset label
                    labelElement.innerText = 'Pilih PDF';
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
                if (fileType === 'application/pdf') { // Periksa tipe file
                    if (fileSize < 50 * 1024 * 1024) { // Periksa ukuran file jika tipe file valid
                        inputElement.files = files;
                        labelElement.innerText = file.name;
                        preview.classList.remove('d-none');
                        filename.innerText = file.name;
                        filename.classList.remove('d-none');
                        dropText.classList.add('d-none'); // Menambahkan class d-none untuk menyembunyikan teks "Drag & drop gambar di sini"
                    } else {
                        // Tampilkan pesan kesalahan jika ukuran file melebihi batas
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Ukuran file melebihi batas (50 MB)',
                        });
                        // Hapus file yang di-drop
                        inputElement.value = '';
                        // Reset label
                        labelElement.innerText = 'Pilih PDF';
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
                        text: 'Tipe file harus PDF',
                    });
                    // Hapus file yang di-drop
                    inputElement.value = '';
                    // Reset label
                    labelElement.innerText = 'Pilih PDF';
                    // Sembunyikan preview dan nama file
                    preview.src = '#';
                    preview.classList.add('d-none');
                    filename.innerText = '';
                    filename.classList.add('d-none');
                }
            }
        }

        function showSearch() {
            var select = document.getElementById("terjemahan");
            var searchInput = document.getElementById("terjemahan_search");
            
            if (select.value === "__search__") {
                searchInput.style.display = "block";
            } else {
                searchInput.style.display = "none";
            }
        }

        const form = document.getElementById('form');
        const progressBar = document.getElementById('progress');
        const progressBarInner = document.getElementById('progress-bar');

        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);

            // Show progress bar
            progressBar.style.display = 'block';

            // Create XMLHttpRequest object
            const xhr = new XMLHttpRequest();

            // Handle progress event
            xhr.upload.addEventListener('progress', function(event) {
                if (event.lengthComputable) {
                    document.getElementById('error_upload').innerHTML = '';
                    const percentCompleted = Math.round((event.loaded / event.total) * 100);
                    progressBarInner.style.width = percentCompleted + '%';
                    document.getElementById('status').innerHTML = percentCompleted + '% uploaded... please wait';
                }
            });

            // Handle request completion
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    // Hide progress bar
                    progressBar.style.display = 'none';
                    // Reset progress bar width
                    progressBarInner.style.width = '0%';

                    if (xhr.status === 200) {
                        // Berhasil
                        const response = JSON.parse(xhr.responseText);

                        if (response.message == 'success' || response.message == 'failed') {
                            document.getElementById('success_upload').innerHTML = 'Upload success... please wait';
                            window.location.href = '/admin/courses';

                        } else {
                            document.getElementById('error_upload').innerHTML = response.message;
                        }

                        //window.location.href = '/admin/courses';
                    } else {
                        // Terjadi kesalahan
                        console.error('Terjadi kesalahan saat pengungahan:', xhr.statusText);
                        document.getElementById('status').innerHTML = 'Upload Failed';
                    }
                }
            };

            // Handle request error
            xhr.onerror = function() {
                console.error('Terjadi kesalahan saat pengungahan');
                // Hide progress bar
                progressBar.style.display = 'none';
                // Reset progress bar width
                progressBarInner.style.width = '0%';
                document.getElementById('status').innerHTML = 'Upload Failed';
            };

            // Open connection and send request
            xhr.open('POST', form.action);
            xhr.send(formData);
        });

    });
</script>

@php
    session()->forget('success_submit_save');
    session()->forget('error_submit_save');
@endphp