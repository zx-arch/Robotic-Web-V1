@extends('cms_login.index_user')
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
        max-height: 95px;
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
        height: 100%; /* Memastikan konten tetap di tengah vertikal */

    }

    #drop-text, #filename {
        text-align: center;
    }

</style>

@section('content')

<main class="content px-3 py-4">
    <div class="container-fluid">
        <div class="box mb-4">
            <div class="box-body">
                <h4 class="highlight-title">Add Discussions</h4>
                <form action="{{route('user.discussions.saveAdd')}}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-4">
                        <label for="title" class="form-label">Judul <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" required value="{{ old('title') }}">
                        <div class="invalid-feedback">
                            @error('title') {{ $message }} @enderror   
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="message" class="form-label">Pesan <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('message') is-invalid @enderror" id="message" name="message" rows="4" required>{{ old('message') }}</textarea>
                        <div class="invalid-feedback">
                            @error('message') {{ $message }} @enderror   
                        </div>
                    </div>

                    <div class="mb-4 position-relative">
                        <label for="hashtags" class="form-label">Hashtags <span class="text-danger">*</span></label>
                        <input type="text" class="form-control text-primary @error('hashtags') is-invalid @enderror" id="hashtags" name="hashtags" placeholder="Tambahkan hashtags" autocomplete="off" value="{{ old('hashtags') }}">
                        <div class="invalid-feedback">
                            @error('hashtags') {{ $message }} @enderror   
                        </div>
                        <div id="hashtagSuggestions" class="list-group position-absolute mt-1" style="z-index: 1000; width: 100%; max-height: 200px; overflow-y: auto;"></div>
                    </div>

                    <div class="form-group highlight-addon has-success">
                        <label for="poster">Upload gambar</label>
                        <div class="custom-file">
                            <input type="file" name="gambar" id="url_link" class="custom-file-input" accept=".png, .jpg, .jpeg" maxlength="52428800">
                            <label class="custom-file-label" for="url_link">Pilih gambar</label>
                        </div>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="card" id="drop-area">
                        <div class="card-body" style="height: 150px;">
                            <div class="center-content">
                                <p id="drop-text">Drag & drop gambar di sini <br> <br> max upload 500 KB</p>
                                <img src="#" alt="Preview" id="preview" class="img-fluid d-none">
                                <p id="filename" class="d-none"></p>

                                <!-- Menampilkan pesan error jika ada -->
                                @error('gambar')
                                    <div class="alert alert-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-warning mt-4">Submit</button>
                </form>
            </div>
        </div>
    </div>
</main>

@endsection

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.querySelector('form');
        const titleInput = document.getElementById('title');
        const messageInput = document.getElementById('message');
        const gambarInput = document.getElementById('gambar');

        // Fungsi untuk menampilkan pesan error di invalid-feedback
        function showErrorMessage(inputElement, message) {
            const feedbackElement = inputElement.nextElementSibling; // Mencari sibling setelah input
            if (feedbackElement && feedbackElement.classList.contains('invalid-feedback')) {
                feedbackElement.textContent = message;
            }
        }

        // Fungsi untuk memvalidasi judul (minimal 3 kata)
        function validateTitle() {
            const titleValue = titleInput.value.trim();
            const wordsCount = titleValue.split(/\s+/).length;

            if (wordsCount < 3) {
                titleInput.setCustomValidity('Judul harus terdiri dari minimal 3 kata.');
                showErrorMessage(titleInput, 'Judul harus terdiri dari minimal 3 kata.');
                titleInput.classList.add('is-invalid');
                titleInput.classList.remove('is-valid');
            } else {
                titleInput.setCustomValidity('');
                showErrorMessage(titleInput, '');
                titleInput.classList.add('is-valid');
                titleInput.classList.remove('is-invalid');
            }
        }

        // Fungsi untuk memvalidasi pesan (minimal 20 karakter)
        function validateMessage() {
            const messageValue = messageInput.value.trim();

            if (messageValue.length < 20) {
                messageInput.setCustomValidity('Pesan harus memiliki minimal 20 karakter.');
                showErrorMessage(messageInput, 'Pesan harus memiliki minimal 20 karakter.');
                messageInput.classList.add('is-invalid');
                messageInput.classList.remove('is-valid');
            } else {
                messageInput.setCustomValidity('');
                showErrorMessage(messageInput, '');
                messageInput.classList.add('is-valid');
                messageInput.classList.remove('is-invalid');
            }
        }

        // Event listener untuk validasi saat mengetik atau blur
        titleInput.addEventListener('input', validateTitle);
        messageInput.addEventListener('input', validateMessage);

        // Submit form
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }

            form.classList.add('was-validated');
        });

        const hashtagInput = document.getElementById('hashtags');
        const hashtagSuggestions = document.getElementById('hashtagSuggestions');
        let currentSuggestionIndex = -1;

        const availableHashtags = JSON.parse('{!! $hashtags !!}');

        hashtagInput.addEventListener('input', function () {
            const value = this.value.trim();
            const lastWord = value.split(' ').pop();
            
            let query = '';
            if (lastWord.startsWith('#') && lastWord.length > 1) {
                query = lastWord.slice(1).toLowerCase();
            } else if (lastWord.length > 1) {
                query = lastWord.toLowerCase();
            }

            if (query.length > 0) {
                const suggestions = availableHashtags.filter(tag => tag.tag_name.toLowerCase().includes(query));
                displaySuggestions(suggestions, query);
            } else {
                hideSuggestions();
            }
        });

        hashtagInput.addEventListener('keydown', function (e) {
            const items = hashtagSuggestions.getElementsByClassName('list-group-item');

            if (e.key === 'ArrowDown') {
                e.preventDefault();
                if (currentSuggestionIndex < items.length - 1) {
                    currentSuggestionIndex++;
                    highlightSuggestion(items);
                    scrollToItem(items[currentSuggestionIndex]);
                }
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                if (currentSuggestionIndex > 0) {
                    currentSuggestionIndex--;
                    highlightSuggestion(items);
                    scrollToItem(items[currentSuggestionIndex]);
                }
            } else if (e.key === 'Enter') {
                e.preventDefault();
                if (currentSuggestionIndex > -1 && currentSuggestionIndex < items.length) {
                    items[currentSuggestionIndex].click();
                }
            }
        });

        function displaySuggestions(suggestions, query) {
            hashtagSuggestions.innerHTML = '';
            currentSuggestionIndex = -1;
            suggestions.forEach((suggestion, index) => {
                const suggestionItem = document.createElement('a');
                suggestionItem.href = '#';
                suggestionItem.classList.add('list-group-item', 'list-group-item-action', 'd-flex', 'justify-content-between', 'align-items-center');
                
                const tagText = document.createElement('span');
                tagText.textContent = suggestion.tag_name;

                const tagCount = document.createElement('span');
                tagCount.textContent = suggestion.count + ' postingan';

                suggestionItem.appendChild(tagText);
                suggestionItem.appendChild(tagCount);

                suggestionItem.addEventListener('click', function (e) {
                    e.preventDefault();
                    const currentValue = hashtagInput.value;
                    const words = currentValue.split(' ');
                    words.pop();
                    words.push(suggestion.tag_name);
                    hashtagInput.value = words.join(' ') + ' ';
                    hideSuggestions();
                });

                hashtagSuggestions.appendChild(suggestionItem);
            });

            if (suggestions.length > 0) {
                hashtagSuggestions.style.display = 'block'; // Tampilkan daftar rekomendasi jika ada
            } else {
                hashtagSuggestions.style.display = 'none'; // Sembunyikan jika tidak ada rekomendasi
            }
        }

        function highlightSuggestion(items) {
            Array.from(items).forEach((item, index) => {
                if (index === currentSuggestionIndex) {
                    item.classList.add('active');
                } else {
                    item.classList.remove('active');
                }
            });
        }

        function scrollToItem(item) {
            const container = hashtagSuggestions;
            const containerTop = container.scrollTop;
            const containerBottom = containerTop + container.offsetHeight;
            const itemTop = item.offsetTop;
            const itemBottom = itemTop + item.offsetHeight;

            if (itemBottom > containerBottom) {
                container.scrollTop = itemBottom - container.offsetHeight;
            } else if (itemTop < containerTop) {
                container.scrollTop = itemTop;
            }
        }

        function hideSuggestions() {
            hashtagSuggestions.innerHTML = '';
            hashtagSuggestions.style.display = 'none';
        }

        document.addEventListener('click', function (e) {
            if (!hashtagSuggestions.contains(e.target) && e.target !== hashtagInput) {
                hideSuggestions();
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
    });
</script>