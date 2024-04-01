<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Intan Robotic</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="{{asset('assets/img/apple-touch-icon.png')}}" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Jost:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{asset('assets/vendor/aos/aos.css')}}" rel="stylesheet">
    <link href="{{asset('assets/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/vendor/bootstrap-icons/bootstrap-icons.css')}}" rel="stylesheet">
    <link href="{{asset('assets/vendor/boxicons/css/boxicons.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/vendor/glightbox/css/glightbox.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/vendor/remixicon/remixicon.css')}}" rel="stylesheet">
    <link href="{{asset('assets/vendor/swiper/swiper-bundle.min.css')}}" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="{{asset('assets/css/style.css')}}" rel="stylesheet">
</head>

<body>

    @include('cms.partials.navbar')

    @yield('content')
    
    <main id="main">

        @include('cms.partials.clients')
        @include('cms.partials.about')
        @include('cms.partials.courses')
        @include('cms.partials.contact')
        @include('cms.partials.footer')

    </main>
    

    <div id="preloader"></div>
    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Cek apakah ada session 'valid'
            if ("{{ session()->has('valid_book') }}") {
                // Dapatkan elemen col-xl-4 pertama
                var firstCol = document.getElementById('courses');
                
                // Arahkan pengguna ke posisi elemen pertama tersebut
                if (firstCol) {
                    firstCol.scrollIntoView({ behavior: 'smooth' });
                }
            }

            if ("{{ session()->has('error_submit_chat') }}") {
                // Dapatkan elemen col-xl-4 pertama
                var firstCol = document.getElementById('contact');
                
                // Arahkan pengguna ke posisi elemen pertama tersebut
                if (firstCol) {
                    firstCol.scrollIntoView({ behavior: 'smooth' });
                }
            }
            
            if ("{{ session()->has('success_submit_chat') }}") {
                // Dapatkan elemen col-xl-4 pertama
                var firstCol = document.getElementById('contact');
                
                // Arahkan pengguna ke posisi elemen pertama tersebut
                if (firstCol) {
                    firstCol.scrollIntoView({ behavior: 'smooth' });
                }
            }

            if ("{{ session()->has('error_access_book') }}") {
                // Dapatkan elemen col-xl-4 pertama
                var firstCol = document.getElementById('courses');
                
                // Arahkan pengguna ke posisi elemen pertama tersebut
                if (firstCol) {
                    firstCol.scrollIntoView({ behavior: 'smooth' });
                }
            }

            // Dapatkan elemen-elemen yang diperlukan
            var levelSelect = document.getElementById('levelSelect');
            var translationSelect = document.getElementById('translationSelect');
            var courseForm = document.getElementById('courseForm');
            var chapterSelect = document.getElementById('chapterSelect');

            // Tambahkan event listener untuk setiap perubahan pada input
            // levelSelect.addEventListener('change', checkAllInputsSelected);
            translationSelect.addEventListener('input', checkAllInputsSelected);

            // Fungsi untuk memeriksa apakah semua input telah dipilih
            function checkAllInputsSelected() {

                if (translationSelect.value != '') {

                    if ("{{ !session()->has('getChapter') || session()->has('jenis_materi') != null && session('jenis_materi') != 'ai_programming' }}") {
                        // Menghapus elemen <select> dengan id 'levelSelect' dan parentnya dengan kelas 'col-md-4'
                        var existingLevelSelect = document.getElementById('levelSelect');

                        if (existingLevelSelect) {
                            var colDiv = existingLevelSelect.closest('.col-md-4');
                            if (colDiv) {
                                colDiv.parentNode.removeChild(colDiv);
                            }
                        }

                        var selectedLanguageId = this.value; // Mengambil nilai id yang dipilih dari translationSelect

                        var levelSelect = document.createElement("select");
                        levelSelect.id = "levelSelect";
                        levelSelect.name = "level";
                        levelSelect.className = "form-control";
                        levelSelect.required = true;

                        // Buat opsi pertama (disabled dan selected)
                        var optionDefault = document.createElement("option");
                        optionDefault.value = "";
                        optionDefault.text = "Pilih Level ..";
                        optionDefault.disabled = true;
                        optionDefault.selected = true;
                        levelSelect.appendChild(optionDefault);
                        

                        var getLevels = {!! session('getLevels') !!};
                        var count = 0;
                        //console.log(getLevels);
                        getLevels.forEach(function(level) {
                            // Jika id bahasa dari level ini sama dengan yang dipilih
                            if (level.language_id == selectedLanguageId) {
                                // Buat elemen option untuk levelSelect
                                var option = document.createElement('option');
                                option.value = level.id;
                                option.text = level.name; // Sesuaikan dengan struktur data Anda
                                levelSelect.appendChild(option); // Tambahkan opsi ke levelSelect
                            }
                            count++;
                        });

                        // Sisipkan elemen select ke dalam elemen div dengan kelas "col-md-4"
                        var colDiv = document.createElement("div");
                        colDiv.className = "col-md-4";
                        colDiv.appendChild(levelSelect);

                        // Sisipkan elemen div dengan kelas "col-md-4" ke dalam elemen div dengan kelas "row w-75"
                        var rowDiv = document.querySelector('.row.w-75');
                        rowDiv.appendChild(colDiv);

                        levelSelect.addEventListener('change', function() {
                            if (this.value !== '') { // Jika levelSelect memiliki nilai yang valid
                                courseForm.submit(); // Submit formulir
                            }
                        });
                    } else {
                        courseForm.submit();

                        // // Menghapus elemen <select> dengan id 'CevelSelect' dan parentnya dengan kelas 'col-md-4'
                        // var existingChapterSelect = document.getElementById('chapterSelect');

                        // if (existingChapterSelect) {
                        //     var colDiv = existingChapterSelect.closest('.col-md-4');
                        //     if (colDiv) {
                        //         colDiv.parentNode.removeChild(colDiv);
                        //     }
                        // }

                        // var selectedLanguageId = this.value; // Mengambil nilai id yang dipilih dari translationSelect
                        // console.log(selectedLanguageId);
                        // var chapterSelect = document.createElement("select");
                        // chapterSelect.id = "chapterSelect";
                        // chapterSelect.name = "chapter";
                        // chapterSelect.className = "form-control";
                        // chapterSelect.required = true;

                        // // Buat opsi pertama (disabled dan selected)
                        // var optionDefault = document.createElement("option");
                        // optionDefault.value = "";
                        // optionDefault.text = "Select Chapter ..";
                        // optionDefault.disabled = true;
                        // optionDefault.selected = true;
                        // chapterSelect.appendChild(optionDefault);
                        
                        // var getChapter = {!! session('getChapter') !!};
                        // console.log(getChapter);
                        // getChapter.forEach(function(chapter) {
                        //     // Jika id bahasa dari chapter ini sama dengan yang dipilih
                        //     if (chapter.language_id == selectedLanguageId) {
                        //         // Buat elemen option untuk ChapterSelect
                        //         var option = document.createElement('option');
                        //         option.value = chapter.hierarchy_id;
                        //         option.text = chapter.name; // Sesuaikan dengan struktur data Anda
                        //         chapterSelect.appendChild(option); // Tambahkan opsi ke chapterSelect
                        //     }
                        // });

                        // // Sisipkan elemen select ke dalam elemen div dengan kelas "col-md-4"
                        // var colDiv = document.createElement("div");
                        // colDiv.className = "col-md-4";
                        // colDiv.appendChild(chapterSelect);

                        // // Sisipkan elemen div dengan kelas "col-md-4" ke dalam elemen div dengan kelas "row w-75"
                        // var rowDiv = document.querySelector('.row.w-75');
                        // rowDiv.appendChild(colDiv);

                        // chapterSelect.addEventListener('change', function() {
                        //     if (this.value !== '') { // Jika chapterSelect memiliki nilai yang valid
                        //         courseForm.submit(); // Submit formulir
                        //     }
                        // });
                    }


                    // levelSelect.style.display = 'block';
                } 
                
                
            }

        });
    </script>

    @php
        session()->forget('valid_book');
        session()->forget('getBook');
        session()->forget('error_access_book');
        session()->forget('request_input_book');
        session()->forget('error_submit_chat');
        session()->forget('not_available_book');
    @endphp
    
    <!-- Vendor JS Files -->
    <script src="{{asset('assets/vendor/aos/aos.js')}}"></script>
    <script src="{{asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('assets/vendor/glightbox/js/glightbox.min.js')}}"></script>
    <script src="{{asset('assets/vendor/isotope-layout/isotope.pkgd.min.js')}}"></script>
    <script src="{{asset('assets/vendor/swiper/swiper-bundle.min.js')}}"></script>
    <script src="{{asset('assets/vendor/waypoints/noframework.waypoints.js')}}"></script>
    <script src="{{asset('assets/vendor/php-email-form/validate.js')}}"></script>

    <!-- Template Main JS File -->
    <script src="{{asset('assets/js/main.js')}}"></script>

</body>

</html>