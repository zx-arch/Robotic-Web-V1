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
            <div class="row">
                <div class="col-md-3 col-sm-6 col-12">
                    <div class="info-box bg-info">
                        <span class="info-box-icon"><i class="fas fa-user"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Semua User</span>
                            <span class="info-box-number">{{\App\Models\Users::all()->count()}}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-12">
                    <div class="info-box bg-success">
                        <span class="info-box-icon"><i class="fas fa-check"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total User Aktif</span>
                            <span class="info-box-number">{{\App\Models\Users::where('status', 'active')->get()->count()}}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-12">
                    <div class="info-box bg-warning">
                        <span class="info-box-icon"><i class="fas fa-times"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total User Belum Aktif</span>
                            <span class="info-box-number">{{\App\Models\Users::where('status', 'inactive')->get()->count() ?? 0}}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-12">
                    <div class="info-box bg-danger">
                        <span class="info-box-icon"><i class="fas fa-times"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total User Dihapus</span>
                            <span class="info-box-number">{{\App\Models\Users::onlyTrashed()->get()->count()}}</span>
                        </div>
                    </div>
                </div>
            </div>
                        
            <div class="card mt-3">

                <div class="card-header">
                    <a href="{{route('daftar_pengguna.add')}}" class="btn btn-success">Add User</a>
                    
                    @if (session('success_deleted'))
                        <div id="w6" class="alert-warning alert alert-dismissible mt-3 w-75" role="alert">
                            {{session('success_deleted')}}
                            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span></button>
                        </div>
                    @endif

                    @if (session('error_deleted'))
                        <div id="w6" class="alert-warning alert alert-dismissible mt-3 w-75" role="alert">
                            {{session('error_deleted')}}
                            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span></button>
                        </div>
                    @endif

                    @if (session()->has('success_submit_save'))
                        <div id="w6" class="alert-primary alert alert-dismissible mt-3 w-75" role="alert">
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
                    
                    @if (session()->has('success_restore'))
                        <div id="w6" class="alert-primary alert alert-dismissible mt-3 w-75" role="alert">
                            {{session('success_restore')}}
                            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span></button>
                        </div>
                    @endif

                    @if ($errors->has('email'))
                        <div id="w6" class="alert-danger alert alert-dismissible mt-3 w-75" role="alert">
                            {{ $errors->first('email') }}
                            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span></button>
                        </div>
                    @endif

                    @if (session()->has('error_restore'))
                        <div id="w6" class="alert-danger alert alert-dismissible mt-3 w-75" role="alert">
                            {{session('error_restore')}}
                            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span></button>
                        </div>
                    @endif
                </div>

                <div class="card-body p-0">
                    <div id="w0" class="gridview table-responsive">
                        <table class="table text-nowrap table-striped table-bordered mb-0">
                            <colgroup>
                                <col style="width: 5%">
                                <col>
                                <col>
                                <col>
                                <col style="width: 140px">
                            </colgroup>
                            <thead>
                                <tr>
                                    <th>
                                        <a href="/dashmitra/web/user/index?UserSearch%5Bid%5D=&amp;UserSearch%5Busername%5D=&amp;UserSearch%5Bemail%5D=&amp;UserSearch%5Bstatus%5D=&amp;UserSearch%5Bcreated_at%5D=17-03-2024&amp;UserSearch%5Blogged_at%5D=&amp;sort=id" data-sort="id">Id</a>
                                    </th>
                                    <th>
                                        <a href="/dashmitra/web/user/index?UserSearch%5Bid%5D=&amp;UserSearch%5Busername%5D=&amp;UserSearch%5Bemail%5D=&amp;UserSearch%5Bstatus%5D=&amp;UserSearch%5Bcreated_at%5D=17-03-2024&amp;UserSearch%5Blogged_at%5D=&amp;sort=username" data-sort="username">Username</a>
                                    </th>
                                    <th>
                                        <a href="/dashmitra/web/user/index?UserSearch%5Bid%5D=&amp;UserSearch%5Busername%5D=&amp;UserSearch%5Bemail%5D=&amp;UserSearch%5Bstatus%5D=&amp;UserSearch%5Bcreated_at%5D=17-03-2024&amp;UserSearch%5Blogged_at%5D=&amp;sort=email" data-sort="email">E-mail</a>
                                    </th>
                                    <th>
                                        <a href="/dashmitra/web/user/index?UserSearch%5Bid%5D=&amp;UserSearch%5Busername%5D=&amp;UserSearch%5Bemail%5D=&amp;UserSearch%5Bstatus%5D=&amp;UserSearch%5Bcreated_at%5D=17-03-2024&amp;UserSearch%5Blogged_at%5D=&amp;sort=status" data-sort="status">Status</a>
                                    </th>
                                    <th>
                                        <a class="asc" href="/dashmitra/web/user/index?UserSearch%5Bid%5D=&amp;UserSearch%5Busername%5D=&amp;UserSearch%5Bemail%5D=&amp;UserSearch%5Bstatus%5D=&amp;UserSearch%5Bcreated_at%5D=17-03-2024&amp;UserSearch%5Blogged_at%5D=&amp;sort=-created_at" data-sort="-created_at">Created at</a>
                                    </th>
                                    <th>
                                        <a href="/dashmitra/web/user/index?UserSearch%5Bid%5D=&amp;UserSearch%5Busername%5D=&amp;UserSearch%5Bemail%5D=&amp;UserSearch%5Bstatus%5D=&amp;UserSearch%5Bcreated_at%5D=17-03-2024&amp;UserSearch%5Blogged_at%5D=&amp;sort=logged_at" data-sort="logged_at">Last login</a>
                                    </th>
                                    <th class="action-column">&nbsp;</th>
                                </tr>
                                <form action="{{route('daftar_pengguna.search')}}" id="searchForm" method="get">
                                    @csrf
                                    <tr id="w0-filters" class="filters">
                                        <td></td>
                                        <td><input type="text" class="form-control" name="search[username]" onkeypress="handleKeyPress(event)" value="{{(isset($searchData['username'])) ? $searchData['username'] : ''}}"></td>
                                        <td><input type="text" class="form-control" name="search[email]" onkeypress="handleKeyPress(event)" value="{{(isset($searchData['email'])) ? $searchData['email'] : ''}}"></td>
                                        <td>
                                            <select id="search-status" class="form-control" name="search[status]" onchange="this.form.submit()">
                                                <option value="" disabled {{(!isset($searchData['status']) ? 'selected' : '')}}></option>
                                                <option value="active" {{(isset($searchData['status']) && $searchData['status'] == 'active') ? 'selected' : ''}}>Active</option>
                                                <option value="inactive" {{(isset($searchData['status']) && $searchData['status'] == 'inactive') ? 'selected' : ''}}>InActive</option>
                                            </select>
                                        </td>
                                        <td>
                                            <div id="search-created_at-kvdate" class="input-group date">
                                                <input type="date" id="search-created_at" class="form-control" onchange="this.form.submit()" name="search[created_at]" max="<?php echo date('Y-m-d'); ?>" value="{{(isset($searchData['created_at'])) ? $searchData['created_at'] : ''}}">
                                            </div>
                                        </td>
                                        <td>
                                            <div id="search-logged_at-kvdate" class="input-group date">
                                                <input type="date" id="search-logged_at" class="form-control" onchange="this.form.submit()" name="search[last_login]" max="<?php echo date('Y-m-d'); ?>" value="{{(isset($searchData['last_login'])) ? $searchData['last_login'] : ''}}">
                                            </div>
                                        </td>
                                        <td>&nbsp;</td>
                                    </tr>
                                </form>
                            </thead>
                            <tbody>
                                @forelse ($users as $user)
                                    <tr data-key="33542">
                                        <td>{{$loop->index += 1;}}</td>
                                        <td>{{$user->username}}</td>
                                        <td><a href="mailto:{{$user->email}}">{{$user->email}}</a></td>
                                        <td>{{$user->status}}</td>
                                        <td>{{ \Carbon\Carbon::parse($user->created_at)->format('M d, Y, h:i:s A') }}</td>
                                        <td>{!! $user->last_login ? $user->last_login : '<span class="not-set">(not set)</span>' !!}</td>
                                        @if ($user->status != 'deleted')
                                            <td>
                                                <a class="btn btn-info btn-sm" href="{{route('daftar_pengguna.view', ['user_id' => encrypt($user->id)])}}" title="View" aria-label="View" data-pjax="0"><i class="fa-fw fas fa-eye" aria-hidden></i></a>
                                                <a class="btn btn-warning btn-sm" href="{{ route('daftar_pengguna.update', ['user_id' => encrypt($user->id)]) }}" title="Update" aria-label="Update" data-pjax="0"><i class="fa-fw fas fa-edit" aria-hidden></i></a>
                                                <a class="btn btn-danger btn-sm btn-delete" href="{{ route('daftar_pengguna.delete', ['user_id' => encrypt($user->id)]) }}" title="Delete" aria-label="Delete" data-pjax="0"><i class="fa-fw fas fa-trash" aria-hidden></i></a>
                                            </td>
                                        @else
                                            <td>
                                                <a class="btn btn-warning btn-sm" href="{{route('daftar_pengguna.restore', ['user_id' => encrypt($user->id)])}}" title="View" aria-label="View" data-pjax="0">Restore</a>
                                            </td>
                                        @endif
                                        
                                    </tr>
                                @empty
                                    <p class="ml-2 mt-3 text-danger">Pengguna belum tersedia</p>
                                @endforelse
                            </tbody>
                        </table>
                        <nav id="w1"></nav>
                    </div>
                </div>

                @if ($users->lastPage() > 1)
                    <nav aria-label="Page navigation example">
                        <ul class="pagination mt-2">
                            {{-- Tombol Sebelumnya --}}
                            @if ($users->currentPage() > 1)
                                <li class="page-item">
                                    <a class="page-link" href="{{ $users->previousPageUrl() }}" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                            @endif
                                        {{-- Tampilkan 4 halaman sebelumnya jika halaman saat ini tidak terlalu dekat dengan halaman pertama --}}
                            @if ($users->currentPage() > 6)
                                @for ($i = $users->currentPage() - 3; $i < $users->currentPage(); $i++)
                                    @if ($i == 1)
                                        <li class="page-item">
                                            <a class="page-link" href="/admin/daftar_pengguna">{{ $i }}</a>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $users->url($i) }}">{{ $i }}</a>
                                        </li>
                                    @endif
                                @endfor
                            @else
                                @for ($i = 1; $i < $users->currentPage(); $i++)
                                    @if ($i == 1)
                                        <li class="page-item">
                                            <a class="page-link" href="/admin/daftar_pengguna">{{ $i }}</a>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $users->url($i) }}">{{ $i }}</a>
                                        </li>
                                    @endif
                                @endfor
                            @endif
                                        {{-- Halaman saat ini --}}
                            <li class="page-item active">
                                <span class="page-link">{{ $users->currentPage() }}</span>
                            </li>
                                        {{-- Tampilkan 4 halaman setelahnya jika halaman saat ini tidak terlalu dekat dengan halaman terakhir --}}
                            @if ($users->currentPage() < $users->lastPage() - 5)
                                @for ($i = $users->currentPage() + 1; $i <= $users->currentPage() + 3; $i++)
                                    @if ($i == 1)
                                        <li class="page-item">
                                            <a class="page-link" href="/admin/daftar_pengguna">{{ $i }}</a>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $users->url($i) }}">{{ $i }}</a>
                                        </li>
                                    @endif
                                @endfor
                            @else
                                @for ($i = $users->currentPage() + 1; $i <= $users->lastPage(); $i++)
                                    @if ($i == 1)
                                        <li class="page-item">
                                            <a class="page-link" href="/admin/daftar_pengguna">{{ $i }}</a>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $users->url($i) }}">{{ $i }}</a>
                                        </li>
                                    @endif
                                @endfor
                            @endif
                                        {{-- Tombol Selanjutnya --}}
                            @if ($users->hasMorePages())
                                <li class="page-item">
                                    <a class="page-link" href="{{ $users->nextPageUrl() }}" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </nav>
                @endif

            </div>
            
            @if (isset($users) && $users->count() > 0)
                <div>
                    Showing <b>{{ $users->firstItem() }}</b> 
                    to <b>{{ $users->lastItem() }}</b>
                    of <b>{{ $users->total() }}</b> items.
                </div>
            @endif
        </div>
    </div>
</div>

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
        
        document.querySelectorAll('.btn-delete').forEach(button => {
            button.addEventListener('click', function(event) {
                event.preventDefault();
                const url = this.getAttribute('href');
                
                // Tampilkan SweetAlert konfirmasi penghapusan
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data daftar pengguna ini akan di non-active!",
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

    });
</script>

<script>
    function showNotification(message, type) {
        Swal.fire({
            title: 'Notification',
            text: message,
            icon: type,
            timer: 3000,
            showConfirmButton: false
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
@endsection
