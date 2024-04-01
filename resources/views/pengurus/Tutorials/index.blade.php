@extends('cms_login.index_pengurus')
<!-- Memuat jQuery dari CDN -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Memuat jQuery UI dari CDN -->
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

@section('content')
<div class="container-fluid">

    <div class="content">
        <div class="row">
            <div class="col-lg-12">
                <h5 class="p-2">Tutorials</h5>

                <div class="card card-default">

                    <div class="card-header">
                        <a href="{{route('pengurus.tutorials.add')}}" class="btn btn-success"><i class="fa fa-plus mr-1" aria-hidden="true"></i> Add</a>
                        
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

                    <div class="card-body p-0" style="overflow-x: auto;">
                        <div id="w0" class="gridview table-responsive">
                            <table class="table text-nowrap table-striped table-bordered mb-0" style="min-width: 110%;">
                                <thead>
                                    <tr>
                                        <td>#</td>
                                        <td>Video Name</td>
                                        <td >Link</td>
                                        <td>Category</td>
                                        <td>Status</td>
                                        <td>Created At</td>
                                        <td>Updated At</td>
                                        <td></td>
                                    </tr>

                                    <form action="{{route('pengurus.tutorials.search')}}" id="searchForm" method="get">
                                        @csrf
                                        <tr id="w0-filters" class="filters">
                                            <td></td>
                                            <td><input type="text" class="form-control" name="search[video_name]" onkeypress="handleKeyPress(event)" value="{{(isset($searchData['video_name'])) ? $searchData['video_name'] : ''}}"></td>
                                            <td></td>
                                            <td>
                                                <select name="search[category]" id="category" class="form-control" onchange="this.form.submit()">
                                                    <option value="" disabled {{(!isset($searchData['category'])) ? 'selected' : ''}}></option>
                                                    @foreach ($getCategory as $tutorial_cat)
                                                        <option value="{{$tutorial_cat->id}}" {{(isset($searchData['category']) && $searchData['category'] == $tutorial_cat->id) ? 'selected' : ''}}>{{$tutorial_cat->category}}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <select name="search[status_id]" id="status" class="form-control" required oninput="this.form.submit()">
                                                    <option value="" disabled {{(!isset($searchData['status_id'])) ? 'selected' : ''}}></option>
                                                    <option value="4" {{(isset($searchData['status_id']) && $searchData['status_id'] == 4) ? 'selected' : ''}}>Enable</option>
                                                    <option value="5" {{(isset($searchData['status_id']) && $searchData['status_id'] == 5) ? 'selected' : ''}}>Disable</option>
                                                    <option value="6" {{(isset($searchData['status_id']) && $searchData['status_id'] == 6) ? 'selected' : ''}}>Draft</option>
                                                </select>
                                            <td>
                                                <div id="search-created_at-kvdate" class="input-group date">
                                                    <input type="date" id="search-created_at" class="form-control" oninput="this.form.submit()" name="search[created_at]" max="<?php echo date('Y-m-d'); ?>" value="{{(isset($searchData['created_at'])) ? $searchData['created_at'] : ''}}">
                                                </div>
                                            </td>
                                            <td>
                                                <div id="search-updated_at-kvdate" class="input-group date">
                                                    <input type="date" id="search-updated_at" class="form-control" oninput="this.form.submit()" name="search[updated_at]" max="<?php echo date('Y-m-d'); ?>" value="{{(isset($searchData['updated_at'])) ? $searchData['updated_at'] : ''}}">
                                                </div>
                                            </td>
                                            <td></td>
                                        </tr>
                                    </form>

                                </thead>

                                <tbody>
                                    @forelse ($tutorials as $tutorial)
                                        <tr>
                                            <td>{{$loop->index += 1}}</td>
                                            <td>{{$tutorial->video_name}}</td>

                                            <td>
                                                <img src="{{$tutorial->thumbnail}}" width="65" height="45" class="img-icon" alt="">
                                                <a href="{{$tutorial->url ?? $tutorial->path_video }}" class="glightbox ml-2">{{$tutorial->video_name}}</a>
                                            </td>
                                            
                                            <td>{{$tutorial->category_name}}</td>
                                            
                                            @if ($tutorial->deleted_at == null)
                                                <td>{{App\Models\MasterStatus::where('id',$tutorial->status_id)->first()->name}}</td>
                                            @else
                                                <td class="text-danger">Deleted by admin</td>
                                            @endif

                                            <td>{{$tutorial->created_at}}</td>
                                            <td>{{$tutorial->updated_at ?? '-'}}</td>

                                            @if ($tutorial->deleted_at == null)
                                                <td>
                                                    <a class="btn btn-warning btn-sm" href="{{ route('pengurus.tutorials.update', ['video_id' => encrypt($tutorial->id)]) }}" title="Update" aria-label="Update" data-pjax="0"><i class="fa-fw fas fa-edit" aria-hidden></i></a>
                                                </td>
                                            @endif
                                            
                                        </tr>
                                    @empty
                                        <p class="ml-2 mt-3 text-danger">Tutorials belum tersedia</p>
                                    @endforelse
                                </tbody>

                            </table>
                        </div>

                    </div>

                    @if ($tutorials->lastPage() > 1)
                        <nav aria-label="Page navigation example">
                            <ul class="pagination mt-2">
                                {{-- Tombol Sebelumnya --}}
                                @if ($tutorials->currentPage() > 1)
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $tutorials->previousPageUrl() }}" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                @endif
                                            {{-- Tampilkan 4 halaman sebelumnya jika halaman saat ini tidak terlalu dekat dengan halaman pertama --}}
                                @if ($tutorials->currentPage() > 6)
                                    @for ($i = $tutorials->currentPage() - 3; $i < $tutorials->currentPage(); $i++)
                                        @if ($i == 1)
                                            <li class="page-item">
                                                <a class="page-link" href="/admin/tutorials">{{ $i }}</a>
                                            </li>
                                        @else
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $tutorials->url($i) }}">{{ $i }}</a>
                                            </li>
                                        @endif
                                    @endfor
                                @else
                                    @for ($i = 1; $i < $tutorials->currentPage(); $i++)
                                        @if ($i == 1)
                                            <li class="page-item">
                                                <a class="page-link" href="/admin/tutorials">{{ $i }}</a>
                                            </li>
                                        @else
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $tutorials->url($i) }}">{{ $i }}</a>
                                            </li>
                                        @endif
                                    @endfor
                                @endif
                                            {{-- Halaman saat ini --}}
                                <li class="page-item active">
                                    <span class="page-link">{{ $tutorials->currentPage() }}</span>
                                </li>
                                            {{-- Tampilkan 4 halaman setelahnya jika halaman saat ini tidak terlalu dekat dengan halaman terakhir --}}
                                @if ($tutorials->currentPage() < $tutorials->lastPage() - 5)
                                    @for ($i = $tutorials->currentPage() + 1; $i <= $tutorials->currentPage() + 3; $i++)
                                        @if ($i == 1)
                                            <li class="page-item">
                                                <a class="page-link" href="/admin/tutorials">{{ $i }}</a>
                                            </li>
                                        @else
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $tutorials->url($i) }}">{{ $i }}</a>
                                            </li>
                                        @endif
                                    @endfor
                                @else
                                    @for ($i = $tutorials->currentPage() + 1; $i <= $tutorials->lastPage(); $i++)
                                        @if ($i == 1)
                                            <li class="page-item">
                                                <a class="page-link" href="/admin/tutorials">{{ $i }}</a>
                                            </li>
                                        @else
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $tutorials->url($i) }}">{{ $i }}</a>
                                            </li>
                                        @endif
                                    @endfor
                                @endif
                                            {{-- Tombol Selanjutnya --}}
                                @if ($tutorials->hasMorePages())
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $tutorials->nextPageUrl() }}" aria-label="Next">
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