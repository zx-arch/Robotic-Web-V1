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
            <h5 class="p-2">Aktivitas Pengguna</h5>

            <div class="card mt-3 mx-auto">

                <div class="card-body p-0">

                    <div class="list-group">

                        @forelse ($activities as $activity)
                            <li class="list-group-item list-group-item-action flex-column align-items-start">
                                <div class="row">
                                    <div class="col-lg-3">
                                        <p>
                                            <b>{{$activity->city}}</b><br>
                                            {{$activity->created_at}}
                                        </p>
                                    </div>
                                    <div class="col-lg-8">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h5 class="mb-1">{{$activity->action}}</h5>
                                            <p>{{ $activity->ip_address }}</p>
                                            
                                        </div>

                                        <small>{{$activity->user_agent}}</small>
                                    </div>
                                </div>
                            </li>
                        @empty
                            <p class="ml-2 mt-3 text-danger">Log aktivitas belum tersedia</p>
                        @endforelse

                    </div>
                </div>

                @if ($activities->lastPage() > 1)
                    <nav aria-label="Page navigation example">
                        <ul class="pagination mt-2">
                            {{-- Tombol Sebelumnya --}}
                            @if ($activities->currentPage() > 1)
                                <li class="page-item">
                                    <a class="page-link" href="{{ $activities->previousPageUrl() }}" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                            @endif
                                        {{-- Tampilkan 4 halaman sebelumnya jika halaman saat ini tidak terlalu dekat dengan halaman pertama --}}
                            @if ($activities->currentPage() > 6)
                                @for ($i = $activities->currentPage() - 3; $i < $activities->currentPage(); $i++)
                                    @if ($i == 1)
                                        <li class="page-item">
                                            <a class="page-link" href="{{$activities->url($i)}}">{{ $i }}</a>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $activities->url($i) }}">{{ $i }}</a>
                                        </li>
                                    @endif
                                @endfor
                            @else
                                @for ($i = 1; $i < $activities->currentPage(); $i++)
                                    @if ($i == 1)
                                        <li class="page-item">
                                            <a class="page-link" href="{{$activities->url($i)}}">{{ $i }}</a>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $activities->url($i) }}">{{ $i }}</a>
                                        </li>
                                    @endif
                                @endfor
                            @endif
                                        {{-- Halaman saat ini --}}
                            <li class="page-item active">
                                <span class="page-link">{{ $activities->currentPage() }}</span>
                            </li>
                                        {{-- Tampilkan 4 halaman setelahnya jika halaman saat ini tidak terlalu dekat dengan halaman terakhir --}}
                            @if ($activities->currentPage() < $activities->lastPage() - 5)
                                @for ($i = $activities->currentPage() + 1; $i <= $activities->currentPage() + 3; $i++)
                                    @if ($i == 1)
                                        <li class="page-item">
                                            <a class="page-link" href="{{$activities->url($i)}}">{{ $i }}</a>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $activities->url($i) }}">{{ $i }}</a>
                                        </li>
                                    @endif
                                @endfor
                            @else
                                @for ($i = $activities->currentPage() + 1; $i <= $activities->lastPage(); $i++)
                                    @if ($i == 1)
                                        <li class="page-item">
                                            <a class="page-link" href="{{$activities->url($i)}}">{{ $i }}</a>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $activities->url($i) }}">{{ $i }}</a>
                                        </li>
                                    @endif
                                @endfor
                            @endif
                                        {{-- Tombol Selanjutnya --}}
                            @if ($activities->hasMorePages())
                                <li class="page-item">
                                    <a class="page-link" href="{{ $activities->nextPageUrl() }}" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </nav>
                @endif

            </div>
            
            @if (isset($activities) && $activities->count() > 0)
                <div>
                    Showing <b>{{ $activities->firstItem() }}</b> 
                    to <b>{{ $activities->lastItem() }}</b>
                    of <b>{{ $activities->total() }}</b> items.
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
