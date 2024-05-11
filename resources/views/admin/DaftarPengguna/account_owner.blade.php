@extends('cms_login.index_admin')

<style>

    {{route('daftar_pengguna.saveAccountOwner')}}preview {
        max-width: 100%;
        max-height: 200px;
        margin-top: 10px;
    }

    {{route('daftar_pengguna.saveAccountOwner')}}filename {
        margin-top: 10px;
    }

    {{route('daftar_pengguna.saveAccountOwner')}}drop-area {
        border: 2px dashed {{route('daftar_pengguna.saveAccountOwner')}}ccc;
        border-radius: 10px;
        padding: 20px;
        margin-top: 20px;
        cursor: pointer;
    }

    {{route('daftar_pengguna.saveAccountOwner')}}drop-area p {
        margin: 0;
        font-size: 16px;
        line-height: 20px;
    }

    .center-content {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100%;
    }

    {{route('daftar_pengguna.saveAccountOwner')}}drop-text, {{route('daftar_pengguna.saveAccountOwner')}}filename {
        text-align: center;
    }
</style>

@section('content')

<div class="container-fluid">
    <div class="content">
        <div class="row">
            <div class="col-lg-2 mb-3">
                <h5 class="p-2">Settings</h5>
                    
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card">

                    <div class="card-header">
                        <div class="image">
                            <img src="{{ ((isset($settings->foto_profil) ? asset('assets/foto_profil/' . $settings->foto_profil) : asset('assets/img/logo-user-image.png'))) }}" width="170" height="160" class="img-circle" alt="User Image" onclick="window.location.href=`/admin`">
                        </div>

                        @if (session()->has('success_saved'))
                            <div id="w6" class="alert-warning alert alert-dismissible mt-3" role="alert">
                                {{session('success_saved')}}
                                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span></button>
                            </div>
                        @endif

                        @if (session()->has('error_saved'))
                            <div id="w6" class="alert-danger alert alert-dismissible mt-3" role="alert">
                                {{session('error_saved')}}
                                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span></button>
                            </div>
                        @endif
                    </div>
                    
                    <div class="row">
                        <div class="card-body d-flex">

                            <form action="{{route('daftar_pengguna.saveAccountOwner')}}" method="post" class="d-flex">
                                @csrf
                                <input type="hidden" name="user_id" value="{{$user_id}}">
                                <div class="form-group highlight-addon mr-2" style="width: 100%;">
                                    <label for="rename-field">Nama Pengelola</label>
                                    <input type="text" name="nama_pengelola" value="{{isset($settings->nama_pengelola) ? $settings->nama_pengelola : ''}}" class="form-control" id="rename-field" maxlength="100" autocomplete="off" spellcheck="false">
                                </div>
                                <button type="submit" class="btn btn-info align-self-center mt-3">Save</button>
                            </form>

                            <form action="{{route('daftar_pengguna.saveAccountOwner')}}" method="post" class="d-flex ml-4">
                                @csrf
                                <input type="hidden" name="user_id" value="{{$user_id}}">
                                <div class="form-group highlight-addon mr-2" style="width: 100%;">
                                    <label for="rename-field">Email Pengelola</label>
                                    <input type="email" name="email_pengelola" value="{{isset($settings->email_pengelola) ? $settings->email_pengelola : ''}}" class="form-control" id="rename-field" maxlength="100" autocomplete="off" spellcheck="false">
                                </div>
                                <button type="submit" class="btn btn-info align-self-center mt-3">Save</button>
                            </form>

                        </div>
                    </div>

                    <div class="row">
                        <div class="card-body d-flex" style="margin-top:-35px;">
                            
                            <form action="{{route('daftar_pengguna.saveAccountOwner')}}" method="post" class="d-flex">
                                @csrf
                                <input type="hidden" name="user_id" value="{{$user_id}}">
                                <div class="form-group highlight-addon mr-2" style="width: 100%;">
                                    <label for="rename-field">Instansi Pekerjaan</label>
                                    <input type="text" name="instansi" class="form-control" value="{{isset($settings->instansi) ? $settings->instansi : ''}}" id="rename-field" autocomplete="off" spellcheck="false">
                                </div>
                                <button type="submit" class="btn btn-info align-self-center mt-3">Save</button>
                            </form>

                            <form action="{{route('daftar_pengguna.saveAccountOwner')}}" method="post" class="d-flex ml-4">
                                @csrf
                                <input type="hidden" name="user_id" value="{{$user_id}}">
                                <div class="form-group highlight-addon mr-2" style="width: 100%;">
                                    <label for="rename-field">Jabatan</label>
                                    <input type="text" name="jabatan" class="form-control" {{isset($settings->jabatan) ? $settings->jabatan : ''}} id="rename-field" autocomplete="off" spellcheck="false">
                                </div>
                                <button type="submit" class="btn btn-info align-self-center mt-3">Save</button>
                            </form>
                        </div>
                    </div>

                    <div class="row">
                        <div class="card-body d-flex" style="margin-top:-35px;">
                            <form action="{{route('daftar_pengguna.saveAccountOwner')}}" method="post" class="d-flex">
                                @csrf
                                <input type="hidden" name="user_id" value="{{$user_id}}">
                                <div class="form-group highlight-addon mr-2" style="width: 100%;">
                                    <label for="rename-field">Change Password</label>
                                    <input type="password" name="change_password" class="form-control" id="rename-field" maxlength="8" autocomplete="off" spellcheck="false">
                                </div>
                                <button type="submit" class="btn btn-info align-self-center mt-3">Save</button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>

            <div class="col-lg-6">
                <form action="{{route('daftar_pengguna.saveAccountOwner')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="user_id" value="{{$user_id}}">
                    <div class="form-group highlight-addon has-success">
                        <label for="youtube">Foto Profil <span class="text-danger"></span></label>
                        <div class="custom-file">
                            <input type="file" name="image" required id="url_link" class="custom-file-input" accept="image/*">
                            <label class="custom-file-label" for="url_link">Pilih Gambar</label>
                        </div>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="card" id="drop-area" style="height: 250px;">
                        <div class="card-body">
                            <div class="center-content">
                                <p id="drop-text">Drag & drop gambar di sini <br> <br> max upload 500 KB</p>
                                <img src="{{route('daftar_pengguna.saveAccountOwner')}}" alt="Preview" id="preview" class="img-fluid d-none">
                                <p id="filename" class="d-none"></p>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success">Save</button>
                </form>
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
                        preview.src = '{{route('daftar_pengguna.saveAccountOwner')}}';
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
                    preview.src = '{{route('daftar_pengguna.saveAccountOwner')}}';
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
                        preview.src = '{{route('daftar_pengguna.saveAccountOwner')}}';
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
                    preview.src = '{{route('daftar_pengguna.saveAccountOwner')}}';
                    preview.classList.add('d-none');
                    filename.innerText = '';
                    filename.classList.add('d-none');
                }
            }
        }

            
    });
</script>