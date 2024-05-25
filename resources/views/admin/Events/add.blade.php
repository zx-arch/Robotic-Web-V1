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

</style>

<!-- Memuat jQuery UI dari CDN -->
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

@section('content')

    <div class="container-fluid">

        <div class="box">
            <div class="box-body">

                <div class="px-15px px-lg-25px">
                    <div class="col-md-12 mx-auto">

                        <div class="card card-default">
                            <div class="card-body p-3">
                                <div class="container mb-3 mt-3">
                                    <form action="{{route('admin.events.saveAdd')}}" id="form" method="post" enctype="multipart/form-data">
                                        @csrf
                                        <div class="row">

                                            <div class="col-lg-6">
                                                <div class="row">

                                                    <div class="col-md-12">

                                                        <div class="form-group highlight-addon has-success">
                                                            <label for="event_name">Nama Event <span class="text-danger">*</span></label>
                                                            <input type="text" name="event_name" id="event_name" required class="form-control" style="max-width: 90%;">
                                                            <div class="invalid-feedback"></div>
                                                        </div>

                                                        <div class="form-group highlight-addon has-success">
                                                            <label for="location">Location Event <span class="text-danger">*</span></label>
                                                            <input type="text" name="location" id="location" required class="form-control" style="max-width: 90%;">
                                                            <div class="invalid-feedback"></div>
                                                        </div>

                                                        <div class="form-group highlight-addon has-success">
                                                            <label for="organizer_name">Organizer Name <span class="text-danger">*</span></label>
                                                            <input type="text" name="organizer_name" id="organizer_name" required class="form-control" style="max-width: 90%;">
                                                            <div class="invalid-feedback"></div>
                                                        </div>

                                                        <div class="form-group highlight-addon has-success">
                                                            <label for="event_section">Bagian Acara <span class="text-danger">*</span> (dari nama pengurus)</label>
                                                            <input type="text" name="event_section" id="event_section" required class="form-control" style="max-width: 90%;">
                                                            <div class="invalid-feedback"></div>
                                                        </div>

                                                        <div class="form-group highlight-addon has-success mb-2">
                                                            <label for="event_date">Tanggal / Jam <span class="text-danger">*</span></label>
                                                            <input type="datetime-local" id="datetime" name="datetime" id="event_date" required class="form-control" min="{{date('Y-m-d\TH:i')}}" style="max-width: 90%;"><br>
                                                            <div class="invalid-feedback"></div>
                                                        </div>

                                                        <div class="form-group highlight-addon has-success">
                                                            <label>Add Organizer</label>
                                                            <div id="organizer-container">
                                                                <div class="form-group highlight-addon has-success organizer" id="organizer-1">
                                                                    <div class="button mt-2">
                                                                        <button type="button" class="btn btn-primary" onclick="addOrganizer()">+</button>
                                                                    </div>
                                                                    <div class="invalid-feedback"></div>

                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="form-group highlight-addon has-success mt-4">
                                                            <label>Add Participant</label>
                                                            <div id="participant-container">
                                                                <div class="form-group highlight-addon has-success participant" id="participant-1">
                                                                    <div class="button mt-2 mb-4">
                                                                        <button type="button" class="btn btn-primary" onclick="addParticipant()">+</button>
                                                                    </div>
                                                                    <div class="invalid-feedback"></div>

                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-4">
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
                                            </div>

                                        </div>

                                        <div id="progress" class="progress mt-3 mb-2" style="height: 30px;display:none;">
                                            <div id="progress-bar" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width: 0%; height: 100%; line-height: 30px;">
                                                <span id="status">0% uploaded... please wait</span>
                                            </div>
                                        </div>

                                        <button type="submit" class="btn btn-success mt-2 mb-2">Create</button>

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
    let organizerCount = 0;
    let participantCount = 0;

    document.addEventListener("DOMContentLoaded", function() {
        updateRemoveButtons('organizer');
        updateRemoveButtons('participant');
        const eventInputs = ['event_name', 'location', 'organizer_name', 'event_section'];

        eventInputs.forEach(id => {
            const inputElement = document.getElementById(id);
            const feedbackElement = inputElement.nextElementSibling;
            inputElement.addEventListener('input', function () {
                validateInput(inputElement, feedbackElement);
            });
        });

        document.getElementById('event_date').addEventListener('change', function () {
            document.getElementById('event_date').classList.remove('is-invalid');
            document.getElementById('event_date').classList.add('is-valid');
            feedbackElement.textContent = '';
        });
    });

    function addOrganizer() {
        addInput('organizer-container', 'organizer', ++organizerCount);
    }

    function addParticipant() {
        addInput('participant-container', 'participant', ++participantCount);
    }

    function removeSpecificElement(id, type) {
        const container = document.getElementById(`${type}-container`);
        const element = document.getElementById(id);
        if (element) {
            element.remove();
            // Update indexes and IDs after removal
            updateIndexesAndIds(container, type);
            // Re-validate form after removal
            checkFormValidity();
        }
    }

    function updateIndexesAndIds(container, type) {
        const items = container.getElementsByClassName('card-body');
        for (let i = 0; i < items.length; i++) {
            const itemId = `${type}-${i + 1}`;
            items[i].id = itemId;
            const inputs = items[i].querySelectorAll('.form-control');
            inputs.forEach(input => {
                const name = input.name.replace(/\[\d+\]/, `[${i}]`);
                input.name = name;
            });
            const buttons = items[i].querySelectorAll('.btn-danger');
            buttons.forEach(button => {
                button.setAttribute('onclick', `removeSpecificElement('${itemId}', '${type}')`);
            });
        }
    }


    function createColumn(type, labelName, addFunction, count) {
        const div = document.createElement('div');
        div.className = 'card-body d-flex';
        div.id = `${type}-${count}`;

        const formGroupDiv = document.createElement('div');
        formGroupDiv.className = 'form-group highlight-addon mr-2';
        formGroupDiv.style.flex = '20';

        const label = document.createElement('label');
        label.textContent = labelName;

        const inputNameElement = createValidatedInput('text', `${type}[${count}][nama]`, 'Nama', '^[a-zA-Z\\s]+$', 'Nama hanya boleh berisi huruf dan spasi.');
        const inputEmailElement = createValidatedInput('email', `${type}[${count}][email]`, 'Email');
        const inputPhoneElement = createValidatedInput('tel', `${type}[${count}][phone_number]`, 'Nomor Telepon', '^\\+?\\d{10,15}$', 'Nomor telepon harus berupa angka dan boleh diawali dengan +, dengan panjang 10-15 digit.');

        formGroupDiv.appendChild(label);
        formGroupDiv.appendChild(inputNameElement);
        formGroupDiv.appendChild(inputEmailElement);
        formGroupDiv.appendChild(inputPhoneElement);

        if (type == 'organizer') {
            const inputSectionElement = createValidatedInput('text', `${type}[${count}][section]`, 'Bagian panitia acara');
            formGroupDiv.appendChild(inputSectionElement);
        }

        const buttonDiv = document.createElement('div');
        buttonDiv.className = 'd-flex align-items-center';
        buttonDiv.style.marginTop = '17px';

        const addButton = document.createElement('button');
        addButton.type = 'button';
        addButton.className = 'btn btn-primary mr-2';
        addButton.addEventListener('click', window[addFunction]);
        addButton.innerHTML = '<i class="fa fa-plus" aria-hidden="true"></i>';

        const removeButton = document.createElement('button');
        removeButton.type = 'button';
        removeButton.className = 'btn btn-danger';
        removeButton.addEventListener('click', function() { removeSpecificElement(div.id, type) });
        removeButton.innerHTML = '<i class="fa fa-minus" aria-hidden="true"></i>';

        buttonDiv.appendChild(addButton);
        buttonDiv.appendChild(removeButton);

        div.appendChild(formGroupDiv);
        div.appendChild(buttonDiv);

        return div;
    }

    function createValidatedInput(type, name, placeholder, pattern = null, title = null) {
        const inputDiv = document.createElement('div');
        inputDiv.className = 'mb-2';

        const inputElement = document.createElement('input');
        inputElement.type = type;
        inputElement.name = name;
        inputElement.placeholder = placeholder;
        inputElement.className = 'form-control';
        inputElement.required = true;

        if (pattern) {
            inputElement.pattern = pattern;
            inputElement.title = title;
        }

        const feedbackElement = document.createElement('div');
        feedbackElement.className = 'invalid-feedback';

        inputElement.addEventListener('input', function () {
            validateInput(inputElement, feedbackElement);
        });

        inputDiv.appendChild(inputElement);
        inputDiv.appendChild(feedbackElement);

        return inputDiv;
    }

    function validateInput(inputElement, feedbackElement) {
        if (inputElement.checkValidity()) {
            inputElement.classList.remove('is-invalid');
            inputElement.classList.add('is-valid');
            feedbackElement.textContent = '';
        } else {
            inputElement.classList.remove('is-valid');
            inputElement.classList.add('is-invalid');

            if (inputElement.value.trim() === '') {
                feedbackElement.textContent = 'Please fill out this field.';
            } else if (inputElement.type === 'email') {
                feedbackElement.textContent = 'Please enter a valid email address.';
            } else if (inputElement.type === 'tel') {
                feedbackElement.textContent = 'Please enter a valid phone number.';
            } else if (inputElement.pattern && !new RegExp(inputElement.pattern).test(inputElement.value)) {
                feedbackElement.textContent = inputElement.title;
            }
        }

        checkFormValidity();
    }

    function checkFormValidity() {
        const inputs = document.querySelectorAll('.form-control');
        const isValid = Array.from(inputs).every(input => input.classList.contains('is-valid'));
    }

    function addInput(containerId, type, count) {
        const container = document.getElementById(containerId);
        const div = createColumn(type, `Add ${type.charAt(0).toUpperCase() + type.slice(1)}`, `add${type.charAt(0).toUpperCase() + type.slice(1)}`, count);
        container.appendChild(div);
        updateRemoveButtons(type);
        checkFormValidity();
    }

    function updateRemoveButtons(type) {
        const container = document.getElementById(`${type}-container`);
        const items = container.getElementsByClassName('card-body');
        for (let i = 0; i < items.length; i++) {
            const buttons = items[i].getElementsByClassName('btn-danger');
            for (let j = 0; j < buttons.length; j++) {
                buttons[j].style.display = items.length === 1 ? 'none' : 'inline-block';
            }
        }
    }

</script>

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