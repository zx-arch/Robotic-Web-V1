@extends('cms_login.index_admin')
<!-- Memuat jQuery dari CDN -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Memuat jQuery UI dari CDN -->
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

@section('content')
<div class="container-fluid">

    <div class="content">
        <div class="row">
            <div class="col-lg-12">
                <h5 class="p-2">Edit Category Tutorial</h5>

                <div class="card card-default">

                    <div class="card-header" id="formAddCat" style="{{ session('success_submit_save') ? '' : 'display: none;' }}">


                        @if (session()->has('success_deleted'))
                            <div id="w6" class="alert-warning alert alert-dismissible mt-3 w-75" role="alert">
                                {{session('success_deleted')}}
                                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span></button>
                            </div>
                        @endif

                        @if (session()->has('error_deleted'))
                            <div id="w6" class="alert-danger alert alert-dismissible mt-3 w-75" role="alert">
                                {{session('error_deleted')}}
                                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span></button>
                            </div>
                        @endif

                        @if (session()->has('success_restore'))
                            <div id="w6" class="alert-warning alert alert-dismissible mt-3 w-75" role="alert">
                                {{session('success_restore')}}
                                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span></button>
                            </div>
                        @endif

                        @if (session()->has('error_restore'))
                            <div id="w6" class="alert-danger alert alert-dismissible mt-3 w-75" role="alert">
                                {{session('error_restore')}}
                                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span></button>
                            </div>
                        @endif

                        @if (session()->has('success_submit_save'))
                            <div id="w6" class="alert-info alert alert-dismissible mt-3 w-75" role="alert">
                                {{session('success_submit_save')}}
                                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span></button>
                            </div>
                        @endif

                        @if (session()->has('error_submit_save'))
                            <div id="w6" class="alert-danger alert alert-dismissible mt-3 w-75" role="alert">
                                {{session('error_submit_save')}}
                                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span></button>
                            </div>
                        @endif

                        @if (session()->has('error_view'))
                            <div id="w6" class="alert-danger alert alert-dismissible mt-3 w-75" role="alert">
                                {{session('error_view')}}
                                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span></button>
                            </div>
                        @endif
                    </div>

                    <div class="card-body p-3" style="overflow-x: auto;">
                        
                            <form action="{{route('category_tutorial.saveUpdate', ['id_cat' => $findCat->id])}}" method="post">
                                @csrf
                                @method('PUT')

                                <div class="row">

                                    <div class="col-lg-6">
                                        <div class="row">

                                            <div class="col-md-12">

                                                <div class="form-group highlight-addon has-success mt-2">
                                                    <label for="video_name">Category Name <span class="text-danger">*</span></label>
                                                    <input type="text" name="category_name" id="category_name" value="{{$findCat->category}}" class="form-control" placeholder="Input Category ..">
                                                    <div class="invalid-feedback"></div>
                                                </div>

                                                <div class="form-group highlight-addon has-success">
                                                    <label for="status">Status <span class="text-danger">*</span></label>
                                                    <select name="status" required class="form-control" id="status">
                                                        <option value="" disabled {{isset($findCat) ? '' : 'selected'}}>Select Status ..</option>
                                                        <option value="11" {{isset($findCat->status_id) && $findCat->status_id == 11 ? 'selected' : ''}}>Enable</option>
                                                        <option value="12" {{isset($findCat->status_id) && $findCat->status_id == 12 ? 'selected' : ''}}>Disable</option>
                                                    </select>
                                                    <div class="invalid-feedback"></div>
                                                </div>

                                                <div class="form-group highlight-addon has-success">
                                                    <label for="is_shown">Ditampilkan User <span class="text-danger">*</span></label>
                                                    <select name="is_shown" required class="form-control" id="is_shown">
                                                        <option value="" disabled {{isset($findCat) ? '' : 'selected'}}>Choose One ..</option>
                                                        <option value="1" {{isset($findCat->is_shown) && $findCat->is_shown == 1 ? 'selected' : ''}}>True</option>
                                                        <option value="0" {{isset($findCat->is_shown) && $findCat->is_shown == 0 ? 'selected' : ''}}>False</option>
                                                    </select>
                                                    <div class="invalid-feedback"></div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    
                                </div>

                                <button type="submit" class="btn btn-success">Update</button>

                            </form>


                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function () {

        // Tambahkan event listener untuk tombol addCategory
        document.getElementById('addCategory').addEventListener('click', function () {
            // Tampilkan formAddCat
            document.getElementById('formAddCat').style.display = 'block';
        });
        // Dapatkan elemen input nama kategori
        var categoryNameInput = document.getElementById('category_name');

        // Dapatkan elemen dropdown status
        var statusDropdown = document.getElementsByName('status')[0];

        // Dapatkan elemen form berdasarkan ID
        var formAddCat = document.getElementById('submitAddCat');

        // Tambahkan event listener untuk input nama kategori
        categoryNameInput.addEventListener('input', function() {
            checkInputsAndSubmit();
        });

        // Tambahkan event listener untuk dropdown status
        statusDropdown.addEventListener('change', function() {
            checkInputsAndSubmit();
        });

        // Fungsi untuk memeriksa kedua input dan menyerahkan form jika sudah diisi
        function checkInputsAndSubmit() {
            var categoryName = categoryNameInput.value.trim();
            var status = statusDropdown.value;

            // Periksa apakah kedua input sudah diisi
            if (categoryName == '' || status == '') {
                // Jika sudah diisi, submit form
                formAddCat.preventDefault();
            }
        }


        // Inisialisasi datepicker
        $('#usersearch-created_at').datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true,
            todayHighlight: true,
            endDate: new Date() // Batasi tanggal maksimum menjadi hari ini
        });

        // Menampilkan tanggal di input teks saat tanggal dipilih
        $('#usersearch-created_at').on('changeDate', function (e) {
            var selectedDate = e.format('dd-mm-yyyy');
            $('#usersearch-created_at').val(selectedDate);
        });

        document.getElementById('buttonDelete').addEventListener('click', function(e) {
            e.preventDefault();
            // Tampilkan SweetAlert konfirmasi
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: 'Gambar akan dihapus permanen!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                // Jika konfirmasi diterima, submit form
                if (result.isConfirmed) {
                    document.getElementById('deleteAll').submit();
                }
            });
        });
    });
</script>

<script>
    function confirmDelete(event) {
        // Menghentikan tindakan default pengguna saat mengklik tautan
        event.preventDefault();

            // Menampilkan konfirmasi menggunakan SweetAlert
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: 'Data akan dihapus permanen!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            // Jika pengguna menekan "Ya, hapus", maka lanjutkan dengan mengarahkan ke tautan delete
            if (result.isConfirmed) {
                window.location.href = event.target.href;
            }
        });
    }

    function handleKeyPress(event) {
        // Periksa apakah tombol yang ditekan adalah tombol "Enter" (kode 13)
        if (event.keyCode === 13) {
            // Hentikan perilaku bawaan dari tombol "Enter" (yang akan mengirimkan formulir)
            event.preventDefault();
            // Submit formulir secara manual
            document.getElementById('searchForm').submit();
        }
    }

    let successMessage = '{{ session('success') }}';
    if (successMessage) {
        showNotification(successMessage, 'success');
    }
</script>