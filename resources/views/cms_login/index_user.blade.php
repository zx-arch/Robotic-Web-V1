<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="{{asset('plugins/bootstrap-5-dashboard/style.css')}}">
    <link href="{{asset('assets/vendor/boxicons/css/boxicons.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/vendor/remixicon/remixicon.css')}}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <link rel="stylesheet" href="{{asset('assets/css/customcolor.css')}}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <style>
        .custom-file {
            position: relative;
            display: inline-block;
            width: 100%;
            height: calc(2.25rem + 2px);
            margin-bottom: 0;
            margin-top: 10px;
        }

        .custom-file-input {
            position: relative;
            z-index: 2;
            width: 100%;
            height: calc(2.25rem + 2px);
            margin: 0;
            opacity: 0;
        }

        .custom-file-input:focus ~ .custom-file-label {
            border-color: #80bdff;
            box-shadow: none;
        }

        .custom-file-input[disabled] ~ .custom-file-label,
        .custom-file-input:disabled ~ .custom-file-label {
            background-color: #e9ecef;
        }

        .custom-file-input:lang(en) ~ .custom-file-label::after {
            content: "Browse";
        }

        .custom-file-input ~ .custom-file-label[data-browse]::after {
            content: attr(data-browse);
        }

        .custom-file-label {
            position: absolute;
            top: 0;
            right: 0;
            left: 0;
            z-index: 1;
            height: calc(2.25rem + 2px);
            padding: 0.375rem 0.75rem;
            font-weight: 400;
            line-height: 1.5;
            color: #495057;
            background-color: #ffffff;
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
            box-shadow: none;
        }

        .custom-file-label::after {
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            z-index: 3;
            display: block;
            height: 2.25rem;
            padding: 0.375rem 0.75rem;
            line-height: 1.5;
            color: #495057;
            content: "Browse";
            background-color: #e9ecef;
            border-left: inherit;
            border-radius: 0 0.25rem 0.25rem 0;
        }

        @media (max-width: 768px) {
            .username-info {
                width: 100%;
                text-align: left;
            }
        }
    </style>
</head>

<body>
    <div class="wrapper">

        @include('user.partials.sidebar')

        <div class="main">
            @include('user.partials.navbar')
            @yield('content')
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>
    <script src="{{asset('plugins/bootstrap-5-dashboard/script.js')}}"></script>

    <script src="{{asset('assets/js/preview_image.js')}}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const likeButtons = document.querySelectorAll('.like-button');

            likeButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const isLiked = this.getAttribute('data-liked') === 'true';
                    const discussionId = this.getAttribute('data-discussion-id');

                    axios.post(`/like/${discussionId}`, {
                            liked: !isLiked
                        })
                        .then(response => {
                            // Update jumlah like di UI
                            document.getElementById('like-count-' + discussionId).textContent = response.data.likes;

                            // Toggle class btn-light dan btn-primary
                            if (isLiked) {
                                button.classList.remove('btn-primary');
                                button.classList.add('btn-light');
                            } else {
                                button.classList.remove('btn-light');
                                button.classList.add('btn-primary');
                            }

                            // Tandai status liked/unliked
                            button.setAttribute('data-liked', !isLiked ? 'true' : 'false');
                        })
                        .catch(error => {
                            console.error('Error toggling like:', error);
                        });
                });
            });

            document.querySelectorAll('.like-btn-answer').forEach(function (button) {
                button.addEventListener('click', function (event) {
                    event.preventDefault();
                    
                    let answerId = this.getAttribute('data-answer-id');
                    let likeCountElement = document.getElementById('like-count-' + answerId).querySelector('.text-like');
                    let buttonElement = this;
                    const discussionId = this.getAttribute('data-discussion-id');
                    const isLiked = this.getAttribute('data-liked') === 'true';

                    fetch(`/discuss/${discussionId}/answers/${answerId}/like`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ liked: isLiked })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (!likeCountElement) {
                            data.likeCount += 1;
                        }
                        likeCountElement.textContent = data.likeCount + ' Likes';
                        if (isLiked) {
                            likeCountElement.classList.remove('text-primary');
                            likeCountElement.classList.add('text-secondary');
                        } else {
                            likeCountElement.classList.remove('text-secondary');
                            likeCountElement.classList.add('text-primary');
                        }
                        buttonElement.setAttribute('data-liked', data.liked ? 'true' : 'false');
                    });
                });
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
        });
    </script>

</body>

</html>