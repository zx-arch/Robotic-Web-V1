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
        max-height: 65px;
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
        max-height: 65px;
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

    .form-group {
        position: relative;
        max-width: 90%;
    }

    #online_app {
        width: 100%;
    }

    #other_app {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border: none;
        padding: 8px 12px;
        font-size: 1rem;
        box-sizing: border-box;
        display: none;
    }
</style>

<!-- Memuat jQuery UI dari CDN -->
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

@section('content')

    <div class="container-fluid">

        <div class="box">
            <div class="box-body">

                <div class="px-15px px-lg-25px">
                    <div class="col-md-12 mx-auto">
                    <h5 class="p-2">Update Online Events</h5>

                        <div class="card card-default">
                            <div class="card-body p-3">
                                <div class="container">
                                    <form action="{{route('admin.onlineEvents.saveUpdate', ['code' => $onlineEvents->code])}}" id="form" method="post" enctype="multipart/form-data">
                                        @csrf

                                        <div class="row">

                                            <div class="col-lg-6">
                                                <div class="row">

                                                    <div class="col-md-12">

                                                        <div class="form-group highlight-addon has-success">
                                                            <label for="name">Nama Event <span class="text-danger">*</span></label>
                                                            <input type="text" name="name" id="event_name" required class="form-control" style="max-width: 90%;" value="{{$onlineEvents->name}}">
                                                            <div class="invalid-feedback"></div>
                                                        </div>

                                                        <div class="form-group highlight-addon has-success mb-2">
                                                            <label for="event_date">Tanggal / Jam <span class="text-danger">*</span></label>
                                                            <input type="datetime-local" id="event_date" name="event_date" id="event_date" required class="form-control" value="{{$onlineEvents->event_date}}" style="max-width: 90%;"><br>
                                                            <div class="invalid-feedback"></div>
                                                        </div>

                                                        <div class="form-group highlight-addon has-success">
                                                            <label for="host">Nama Host Acara <span class="text-danger">*</span></label>
                                                            <input type="text" name="host" id="host" required class="form-control" style="max-width: 90%;" value="{{$onlineEvents->host}}">
                                                            <div class="invalid-feedback"></div>
                                                        </div>

                                                        <div class="form-group highlight-addon has-success">
                                                            <label for="speakers">Pembicara <span class="text-danger">*</span></label>
                                                            <input type="text" name="speakers" id="speakers" required class="form-control" style="max-width: 90%;" value="{{$onlineEvents->speakers}}">
                                                            <div class="invalid-feedback"></div>
                                                        </div>

                                                        <div class="form-group highlight-addon has-success">
                                                            <label for="link_pendaftaran">Link Pendaftaran</label>
                                                            <input type="url" name="link_pendaftaran" id="link_pendaftaran" class="form-control" style="max-width: 90%;" value="{{$onlineEvents->link_pendaftaran}}">
                                                            <div class="invalid-feedback"></div>
                                                        </div>

                                                        <div class="form-group highlight-addon has-success">
                                                            <label for="online_apps">Online App <span class="text-danger">*</span></label>
                                                            <select name="online_app" id="online_app" required class="form-control" style="max-width: 90%;">
                                                                <option value="{{!in_array($onlineEvents->online_app, $dataOnlineApp) ? $onlineEvents->online_app : ''}}" {{!in_array($onlineEvents->online_app, $dataOnlineApp) ? 'selected' : ''}}>{{!in_array($onlineEvents->online_app, $dataOnlineApp) ? $onlineEvents->online_app : ''}}</option>
                                                                <option value="Google Meet" {{$onlineEvents->online_app == 'Google Meet' ? 'selected' : ''}}>Google Meet</option>
                                                                <option value="Zoom" {{$onlineEvents->online_app == 'Zoom' ? 'selected' : ''}}>Zoom</option>
                                                                <option value="Microsoft Teams" {{$onlineEvents->online_app == 'Microsoft Teams' ? 'selected' : ''}}>Microsoft Teams</option>
                                                                <option value="Youtube" {{$onlineEvents->online_app == 'Youtube' ? 'selected' : ''}}>Youtube</option>
                                                                <option value="other">Other</option>
                                                            </select>
                                                            <input type="text" name="other_app" id="other_app" style="max-width: 90%" value="{{!in_array($onlineEvents->online_app, $dataOnlineApp) ?? $onlineEvents->online_app}}" class="form-control" placeholder="Please specify other app">
                                                            <div class="invalid-feedback"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-4">
                                                <div class="form-group highlight-addon has-success">
                                                    <label for="link_online">Link Online</label>
                                                    <input type="url" name="link_online" id="link_online" class="form-control" style="max-width: 90%;" value="{{$onlineEvents->link_online}}">
                                                    <div class="invalid-feedback"></div>
                                                </div>

                                                <div class="form-group highlight-addon has-success">
                                                    <label for="user_access">User Akses</label>
                                                    <input type="text" name="user_access" id="user_access" class="form-control" style="max-width: 90%;" value="{{$onlineEvents->user_access}}">
                                                    <div class="invalid-feedback"></div>
                                                </div>

                                                <div class="form-group highlight-addon has-success">
                                                    <label for="passcode">Passcode Akses</label>
                                                    <input type="text" name="passcode" id="passcode" class="form-control" style="max-width: 90%;" value="{{$onlineEvents->passcode}}">
                                                    <div class="invalid-feedback"></div>
                                                </div>
                                                <div class="form-group highlight-addon has-success">
                                                    <label for="poster">Upload Poster Event</label>
                                                    <div class="custom-file">
                                                        <input type="file" name="poster_event" id="url_link" class="custom-file-input" accept=".png, .jpg, .jpeg" maxlength="52428800">
                                                        <label class="custom-file-label" for="url_link">Pilih Poster</label>
                                                    </div>
                                                    <div class="invalid-feedback"></div>
                                                </div>

                                                <div class="card" id="drop-area">
                                                    <div class="card-body">
                                                        <div class="center-content">
                                                            <p id="drop-text">Drag & drop poster di sini <br> <br> max upload 500 KB</p>
                                                            <img src="#" alt="Preview" id="preview" class="img-fluid d-none">
                                                            <p id="filename" class="d-none"></p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div id="image-container">
                                                    @if ($onlineEvents->poster)
                                                        <img src="{{$onlineEvents->poster}}" alt="" width="70" height="70" class="img-fluid">
                                                    @endif
                                                </div>

                                            </div>

                                        </div>

                                        <div id="progress" class="progress mt-3 mb-2" style="height: 30px;display:none;">
                                            <div id="progress-bar" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width: 0%; height: 100%; line-height: 30px;">
                                                <span id="status">0% uploaded... please wait</span>
                                            </div>
                                        </div>

                                        <button type="submit" class="btn btn-warning mt-2 mb-2">Update</button>

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
    </div>

@endsection

<script>
    document.addEventListener('DOMContentLoaded', function () {

        document.getElementById('online_app').addEventListener('change', function() {
            var otherAppInput = document.getElementById('other_app');
            if (this.value === 'other') {
                otherAppInput.style.display = 'block';
                this.style.display = 'none';
                var label = document.createElement('label');
                label.setAttribute('for', 'other_app');
                label.setAttribute('id', 'other_app_label');
                label.textContent = 'Other App';
                document.querySelector('.select-wrapper').insertBefore(label, document.getElementById('other_app'));
                otherAppInput.focus();
            }
        });

        document.getElementById('other_app').addEventListener('blur', function() {
            if (this.value === '') {
                this.style.display = 'none';
                document.getElementById('online_app').style.display = 'block';
                document.getElementById('online_app').value = '';
                var label = document.getElementById('other_app_label');
                if (label) {
                    label.remove();
                }
            }
        });
        
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
                        dropText.classList.remove('d-none'); // Menambahkan class d-none untuk menyembunyikan teks "Drag & drop gambar di sini"

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
                    dropText.classList.remove('d-none'); // Menambahkan class d-none untuk menyembunyikan teks "Drag & drop gambar di sini"

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

        // const form = document.getElementById('form');
        // const progressBar = document.getElementById('progress');
        // const progressBarInner = document.getElementById('progress-bar');

        // form.addEventListener('submit', function(e) {
        //     e.preventDefault();
            
        //     const formData = new FormData(this);

        //     // Show progress bar
        //     progressBar.style.display = 'block';

        //     // Create XMLHttpRequest object
        //     const xhr = new XMLHttpRequest();

        //     // Handle progress event
        //     xhr.upload.addEventListener('progress', function(event) {
        //         if (event.lengthComputable) {
        //             document.getElementById('error_upload').innerHTML = '';
        //             const percentCompleted = Math.round((event.loaded / event.total) * 100);
        //             progressBarInner.style.width = percentCompleted + '%';
        //             document.getElementById('status').innerHTML = percentCompleted + '% uploaded... please wait';
        //         }
        //     });

        //     // Handle request completion
        //     xhr.onreadystatechange = function() {
        //         if (xhr.readyState === XMLHttpRequest.DONE) {
        //             // Hide progress bar
        //             progressBar.style.display = 'none';
        //             // Reset progress bar width
        //             progressBarInner.style.width = '0%';

        //             if (xhr.status === 200) {
        //                 // Berhasil
        //                 const response = JSON.parse(xhr.responseText);

        //                 if (response.message == 'success' || response.message == 'failed') {
        //                     document.getElementById('success_upload').innerHTML = 'Upload success... please wait';
        //                     window.location.href = '/admin/events';

        //                 } else {
        //                     document.getElementById('error_upload').innerHTML = response.message;
        //                 }

        //                 //window.location.href = '/admin/courses';
        //             } else {
        //                 // Terjadi kesalahan
        //                 console.error('Terjadi kesalahan saat pengungahan:', xhr.statusText);
        //                 document.getElementById('status').innerHTML = 'Upload Failed';
        //             }
        //         }
        //     };

        //     // Handle request error
        //     xhr.onerror = function() {
        //         console.error('Terjadi kesalahan saat pengungahan');
        //         // Hide progress bar
        //         progressBar.style.display = 'none';
        //         // Reset progress bar width
        //         progressBarInner.style.width = '0%';
        //         document.getElementById('status').innerHTML = 'Upload Failed';
        //     };

        //     // Open connection and send request
        //     xhr.open('POST', form.action);
        //     xhr.send(formData);
        // });
    });
</script>

@php
    session()->forget('success_submit_save');
    session()->forget('error_submit_save');
@endphp