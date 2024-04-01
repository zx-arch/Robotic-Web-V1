@extends('cms_login.index_admin')
<!-- Memuat jQuery dari CDN -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Memuat jQuery UI dari CDN -->
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

<!-- Memuat CSS untuk jQuery UI (dibutuhkan untuk styling datepicker) -->

@section('content')
<div class="container-fluid">

    <div class="box">
        <div class="box-body">
            <h5 class="p-2">Bahasa Terjemahan</h5>

            <div class="card mt-3 mx-auto">

                <div class="card-header">
                    <a href="{{route('language.add')}}" class="btn btn-success">Add</a>

                    @if (session()->has('success_add_language'))
                        <div id="w6" class="alert-info alert alert-dismissible mt-3 w-75" role="alert">
                            {{session('success_add_language')}}
                            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span></button>
                        </div>
                    @endif

                    @if (session()->has('error_add_language'))
                        <div id="w6" class="alert-danger alert alert-dismissible mt-3 w-75" role="alert">
                            {{session('error_add_language')}}
                            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span></button>
                        </div>
                    @endif
                </div>

                <div class="card-body p-0">

                    <div id="w0" class="gridview table-responsive">
                        <table class="table text-nowrap table-striped table-bordered mb-0">
                            <thead>
                                <tr>
                                    <td>ID</td>
                                    <td>Language Code</td>
                                    <td>Language Name</td>
                                </tr>
                                <form action="{{ route('language.search') }}" id="searchForm" method="get">
                                    @csrf
                                    <tr id="w0-filters" class="filters">
                                        <td></td>
                                        <td><input type="text" class="form-control" name="search[language_code]" onkeypress="handleKeyPress(event)" value="{{(isset($searchData['language_code'])) ? $searchData['language_code'] : ''}}"></td>
                                        <td><input type="text" class="form-control" name="search[language_name]" onkeypress="handleKeyPress(event)" value="{{(isset($searchData['language_name'])) ? $searchData['language_name'] : ''}}"></td>
                                    </tr>
                                </form>
                            </thead>
                            <tbody>
                                @forelse ($translation as $terjemahan)
                                    <tr>
                                        <td>{{$terjemahan->id}}</td>
                                        <td>{{$terjemahan->language_code}}</td>
                                        <td>{{$terjemahan->language_name}}</td>
                                    </tr>
                                @empty
                                    <p class="ml-2 mt-3 text-danger">Terjemahan belum tersedia</p>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                @if ($translation->lastPage() > 1)
                    <nav aria-label="Page navigation example">
                        <ul class="pagination mt-2">
                            {{-- Tombol Sebelumnya --}}
                            @if ($translation->currentPage() > 1)
                                <li class="page-item">
                                    <a class="page-link" href="{{ $translation->previousPageUrl() }}" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                            @endif
                                        {{-- Tampilkan 4 halaman sebelumnya jika halaman saat ini tidak terlalu dekat dengan halaman pertama --}}
                            @if ($translation->currentPage() > 6)
                                @for ($i = $translation->currentPage() - 3; $i < $translation->currentPage(); $i++)
                                    @if ($i == 1)
                                        <li class="page-item">
                                            <a class="page-link" href="{{$translation->url($i)}}">{{ $i }}</a>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $translation->url($i) }}">{{ $i }}</a>
                                        </li>
                                    @endif
                                @endfor
                            @else
                                @for ($i = 1; $i < $translation->currentPage(); $i++)
                                    @if ($i == 1)
                                        <li class="page-item">
                                            <a class="page-link" href="{{$translation->url($i)}}">{{ $i }}</a>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $translation->url($i) }}">{{ $i }}</a>
                                        </li>
                                    @endif
                                @endfor
                            @endif
                                        {{-- Halaman saat ini --}}
                            <li class="page-item active">
                                <span class="page-link">{{ $translation->currentPage() }}</span>
                            </li>
                                        {{-- Tampilkan 4 halaman setelahnya jika halaman saat ini tidak terlalu dekat dengan halaman terakhir --}}
                            @if ($translation->currentPage() < $translation->lastPage() - 5)
                                @for ($i = $translation->currentPage() + 1; $i <= $translation->currentPage() + 3; $i++)
                                    @if ($i == 1)
                                        <li class="page-item">
                                            <a class="page-link" href="{{$translation->url($i)}}">{{ $i }}</a>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $translation->url($i) }}">{{ $i }}</a>
                                        </li>
                                    @endif
                                @endfor
                            @else
                                @for ($i = $translation->currentPage() + 1; $i <= $translation->lastPage(); $i++)
                                    @if ($i == 1)
                                        <li class="page-item">
                                            <a class="page-link" href="{{$translation->url($i)}}">{{ $i }}</a>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $translation->url($i) }}">{{ $i }}</a>
                                        </li>
                                    @endif
                                @endfor
                            @endif
                                        {{-- Tombol Selanjutnya --}}
                            @if ($translation->hasMorePages())
                                <li class="page-item">
                                    <a class="page-link" href="{{ $translation->nextPageUrl() }}" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </nav>
                @endif

            </div>
            
            @if (isset($translation) && $translation->count() > 0)
                <div>
                    Showing <b>{{ $translation->firstItem() }}</b> 
                    to <b>{{ $translation->lastItem() }}</b>
                    of <b>{{ $translation->total() }}</b> items.
                </div>
            @endif
        </div>
    </div>
</div>

@endsection
<script>
    document.addEventListener('DOMContentLoaded', function () {

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
</script>
