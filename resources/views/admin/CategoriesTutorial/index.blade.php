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
                <h5 class="p-2">Category Tutorial</h5>
                <button id="addCategory" class="btn btn-success mb-3" style="width: 6%;"><i class="fa fa-plus mr-1" aria-hidden="true"></i> Add</button>
                <div class="card card-default">

                    <div class="card-header" id="formAddCat" style="{{ session('success_submit_save') || session('error_submit_save') || session('success_deleted') || session('error_deleted') ? '' : 'display: none;' }}">

                        <form action="{{route('category_tutorial.addSubmit')}}" method="post" id="submitAddCat">
                            @csrf
                            <div class="row w-75" id="inputComponent" style="{{ session('success_submit_save') ? 'display: none;' : '' }}">
                                
                                <div class="col-md-4">
                                    <input type="text" name="category_name" id="category_name" class="form-control" placeholder="Input Category ..">
                                </div>

                                <div class="col-md-4">
                                    <select name="status" required class="form-control" id="status">
                                        <option value="" disabled selected>Select Status ..</option>
                                        <option value="11">Enable</option>
                                        <option value="12">Disable</option>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </div>
                        </form>

                        @if (session()->has('success_deleted'))
                            <div id="w6" class="alert-warning alert alert-dismissible mt-3 w-75" role="alert">
                                {{session('success_deleted')}}
                                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span></button>
                            </div>
                        @endif

                        @if (session()->has('error_find_cat'))
                            <div id="w6" class="alert-warning alert alert-dismissible mt-3 w-75" role="alert">
                                {{session('error_find_cat')}}
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

                    <div class="card-body p-0" style="overflow-x: auto;">
                        <div id="w0" class="gridview table-responsive">
                            <table class="table text-nowrap table-striped table-bordered mb-0" style="min-width: 110%;">
                                <thead>
                                    <tr>
                                        <td>#</td>
                                        <td>Category</td>
                                        <td>Status</td>
                                        <td style="width: 35px;">Ditampilkan User</td>
                                        <td>Created At</td>
                                        <td>Updated At</td>
                                        <td></td>
                                    </tr>

                                    <form action="{{route('category_tutorial.search')}}" id="searchForm" method="get">
                                        @csrf
                                        <tr id="w0-filters" class="filters">
                                            <td></td>
                                            <td>
                                                <select name="search[category]" id="category" class="form-control" oninput="this.form.submit()">
                                                    <option value="" disabled {{(!isset($searchData['category'])) ? 'selected' : ''}}></option>
                                                    @foreach ($getCategory as $tutorial_cat)
                                                        <option value="{{$tutorial_cat->id}}" {{(isset($searchData['category']) && $searchData['category'] == $tutorial_cat->id) ? 'selected' : ''}}>{{$tutorial_cat->category}}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <select id="search-status" class="form-control" name="search[status]" oninput="this.form.submit()">
                                                    <option value="" disabled {{(!isset($searchData['status']) ? 'selected' : '')}}></option>
                                                    <option value="11" {{(isset($searchData['status']) && $searchData['status'] == '11') ? 'selected' : ''}}>Enable</option>
                                                    <option value="12" {{(isset($searchData['status']) && $searchData['status'] == '12') ? 'selected' : ''}}>Disable</option>
                                                </select>
                                            </td>
                                            <td></td>
                                            <td>
                                                <div id="search-created_at-kvdate" class="input-group date">
                                                    <input type="date" id="search-created_at" class="form-control" onchange="this.form.submit()" name="search[created_at]" max="<?php echo date('Y-m-d'); ?>" value="{{(isset($searchData['created_at'])) ? $searchData['created_at'] : ''}}">
                                                </div>
                                            </td>
                                            <td>
                                                <div id="search-updated_at-kvdate" class="input-group date">
                                                    <input type="date" id="search-updated_at" class="form-control" onchange="this.form.submit()" name="search[updated_at]" max="<?php echo date('Y-m-d'); ?>" value="{{(isset($searchData['updated_at'])) ? $searchData['updated_at'] : ''}}">
                                                </div>
                                            </td>
                                            <td></td>
                                        </tr>
                                    </form>

                                </thead>

                                <tbody>
                                    @forelse ($categoryTutorial as $category)
                                        <tr>
                                            <td>{{$loop->index += 1}}</td>
                                            <td>{{$category->category}}</td>
                                            <td>{{isset(\App\Models\MasterStatus::where('id', $category->status_id)->first()->name) ? \App\Models\MasterStatus::where('id', $category->status_id)->first()->name : "Not Valid"}}</td>
                                            <td>{{ $category->is_shown ? 'True' : 'False' }}</td>
                                            <td>{{$category->created_at}}</td>
                                            <td>{{$category->updated_at}}</td>
                                            <td>
                                                <a class="btn btn-warning btn-sm" href="{{route('category_tutorial.update', ['id_cat' =>$category->id])}}" title="Update" aria-label="Update" data-pjax="0"><i class="fa-fw fas fa-edit" aria-hidden></i></a>
                                                
                                                @if ($category->valid_deleted && isset($category->delete_html_code))
                                                    {!! $category->delete_html_code !!}
                                                @endif

                                            </td>
                                            
                                        </tr>
                                    @empty
                                        <p class="ml-2 mt-3 text-danger">Category tutorial belum tersedia</p>
                                    @endforelse
                                </tbody>

                            </table>
                        </div>

                    </div>

                    @if ($categoryTutorial->lastPage() > 1)
                        <nav aria-label="Page navigation example">
                            <ul class="pagination mt-2">
                                {{-- Tombol Sebelumnya --}}
                                @if ($categoryTutorial->currentPage() > 1)
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $categoryTutorial->previousPageUrl() }}" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                @endif
                                {{-- Tampilkan 4 halaman sebelumnya jika halaman saat ini tidak terlalu dekat dengan halaman pertama --}}
                                @if ($categoryTutorial->currentPage() > 6)
                                    @for ($i = $categoryTutorial->currentPage() - 3; $i < $categoryTutorial->currentPage(); $i++)
                                        @if ($i == 1)
                                            <li class="page-item">
                                                <a class="page-link" href="/admin/category_tutorial">{{ $i }}</a>
                                            </li>
                                        @else
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $categoryTutorial->url($i) }}">{{ $i }}</a>
                                            </li>
                                        @endif
                                    @endfor
                                @else
                                    @for ($i = 1; $i < $categoryTutorial->currentPage(); $i++)
                                        @if ($i == 1)
                                            <li class="page-item">
                                                <a class="page-link" href="/admin/category_tutorial">{{ $i }}</a>
                                            </li>
                                        @else
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $categoryTutorial->url($i) }}">{{ $i }}</a>
                                            </li>
                                        @endif
                                    @endfor
                                @endif
                                            {{-- Halaman saat ini --}}
                                <li class="page-item active">
                                    <span class="page-link">{{ $categoryTutorial->currentPage() }}</span>
                                </li>
                                            {{-- Tampilkan 4 halaman setelahnya jika halaman saat ini tidak terlalu dekat dengan halaman terakhir --}}
                                @if ($categoryTutorial->currentPage() < $categoryTutorial->lastPage() - 5)
                                    @for ($i = $categoryTutorial->currentPage() + 1; $i <= $categoryTutorial->currentPage() + 3; $i++)
                                        @if ($i == 1)
                                            <li class="page-item">
                                                <a class="page-link" href="/admin/category_tutorial">{{ $i }}</a>
                                            </li>
                                        @else
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $categoryTutorial->url($i) }}">{{ $i }}</a>
                                            </li>
                                        @endif
                                    @endfor
                                @else
                                    @for ($i = $categoryTutorial->currentPage() + 1; $i <= $categoryTutorial->lastPage(); $i++)
                                        @if ($i == 1)
                                            <li class="page-item">
                                                <a class="page-link" href="/admin/category_tutorial">{{ $i }}</a>
                                            </li>
                                        @else
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $categoryTutorial->url($i) }}">{{ $i }}</a>
                                            </li>
                                        @endif
                                    @endfor
                                @endif
                                {{-- Tombol Selanjutnya --}}
                                @if ($categoryTutorial->hasMorePages())
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $categoryTutorial->nextPageUrl() }}" aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </nav>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function () {

        document.querySelectorAll('.btn-delete').forEach(button => {
            button.addEventListener('click', function(event) {
                event.preventDefault();
                const url = this.getAttribute('href');
                
                // Tampilkan SweetAlert konfirmasi penghapusan
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Category tutorial yang dihapus juga akan menghapus permanen semua tutorial!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Jika pengguna menekan tombol "Ya, hapus", arahkan ke URL penghapusan
                        window.location.href = url;
                    }
                });
            });
        });

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
            text: 'Data akan dihapus beserta semua video dengan kategori terkait!',
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