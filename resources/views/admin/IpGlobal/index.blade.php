<style>
    body {
        max-height: 1000vh;
    }
</style>
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
                <div class="col-md-4 col-sm-6 col-12">
                    <div class="info-box bg-info d-flex align-items-center">
                        <span class="info-box-icon"><i class="fas fa-user"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total IP Address</span>
                            <span class="info-box-number">{{ $publicIp->total() }}</span>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 col-sm-6 col-12">
                    <div class="info-box bg-success d-flex align-items-center">
                        <span class="info-box-icon"><i class="fas fa-check"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total IP Unblocked</span>
                            <span class="info-box-number">{{ $ipUnblocked }}</span>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 col-sm-6 col-12">
                    <div class="info-box bg-danger d-flex align-items-center">
                        <span class="info-box-icon"><i class="fas fa-check"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total IP Blocked</span>
                            <span class="info-box-number">{{ $ipBlocked }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-3">

                @if (session()->has('success_unlocked'))
                    <div class="card-header">
                        <div id="w6" class="alert-warning alert alert-dismissible mt-3 w-50" role="alert">
                            {{session('success_unlocked')}}
                            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span></button>
                        </div>
                    </div>
                @endif

                @if (session()->has('error_unlocked'))
                    <div class="card-header">
                        <div id="w6" class="alert-warning alert alert-dismissible mt-3 w-50" role="alert">
                            {{session('error_unlocked')}}
                            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span></button>
                        </div>
                    </div>
                @endif

                @if (session()->has('success_locked'))
                    <div class="card-header">
                        <div id="w6" class="alert-warning alert alert-dismissible mt-3 w-50" role="alert">
                            {{session('success_locked')}}
                            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span></button>
                        </div>
                    </div>
                @endif

                @if (session()->has('error_locked'))
                    <div class="card-header">
                        <div id="w6" class="alert-warning alert alert-dismissible mt-3 w-50" role="alert">
                            {{session('error_locked')}}
                            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span></button>
                        </div>
                    </div>
                @endif

                @if (session()->has('success_blocked'))
                    <div class="card-header">
                        <div id="w6" class="alert-warning alert alert-dismissible mt-3 w-50" role="alert">
                            {{session('success_blocked')}}
                            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span></button>
                        </div>
                    </div>
                @endif

                @if (session()->has('error_blocked'))
                    <div class="card-header">
                        <div id="w6" class="alert-warning alert alert-dismissible mt-3 w-50" role="alert">
                            {{session('error_blocked')}}
                            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span></button>
                        </div>
                    </div>
                @endif

                <div class="card-body p-0">
                    <div id="w0" class="gridview table-responsive">
                        <table class="table text-nowrap table-striped table-bordered mb-0">
                            <thead>
                                <tr>
                                    <td>#</td>
                                    <td>IP address</td>
                                    <td>Netmask</td>
                                    <td>Country</td>
                                    <td>Is anonymous proxy</td>
                                    <td>Is satellite provider</td>
                                    <td>Is blocked</td>
                                    <td></td>
                                </tr>

                                <form action="{{route('ip_global.search')}}" id="searchForm" method="get">
                                    @csrf
                                    <tr id="w0-filters" class="filters">
                                        <td></td>
                                        <td><input type="text" class="form-control" name="search[network]" onkeypress="handleKeyPress(event)" value="{{(isset($searchData['network'])) ? $searchData['network'] : ''}}"></td>
                                        <td><input type="text" class="form-control" name="search[netmask]" onkeypress="handleKeyPress(event)" value="{{(isset($searchData['netmask'])) ? $searchData['netmask'] : ''}}"></td>
                                        <td></td>
                                        <td>
                                            <select name="search[is_anonymous_proxy]" id="is_anonymous_proxy" class="form-control" onchange="this.form.submit()">
                                                <option value="" {{(!isset($searchData['is_anonymous_proxy'])) ? 'selected' : ''}} disabled></option>
                                                <option value="1" {{(isset($searchData['is_anonymous_proxy']) && $searchData['is_anonymous_proxy'] == 1) ? 'selected' : ''}}>True</option>
                                                <option value="0" {{(isset($searchData['is_anonymous_proxy']) && $searchData['is_anonymous_proxy'] == 0) ? 'selected' : ''}}>False</option>
                                            </select>
                                        </td>
                                        <td>
                                            <select name="search[is_satellite_provider]" id="is_satellite_provider" class="form-control" onchange="this.form.submit()">
                                                <option value="" {{(!isset($searchData['is_satellite_provider'])) ? 'selected' : ''}} disabled></option>
                                                <option value="1" {{(isset($searchData['is_satellite_provider']) && $searchData['is_satellite_provider'] == 1) ? 'selected' : ''}}>True</option>
                                                <option value="0" {{(isset($searchData['is_satellite_provider']) && $searchData['is_satellite_provider'] == 0) ? 'selected' : ''}}>False</option>
                                            </select>
                                        </td>
                                        <td>
                                            <select name="search[is_blocked]" id="is_blocked" class="form-control" onchange="this.form.submit()">
                                                <option value="" {{(!isset($searchData['is_blocked'])) ? 'selected' : ''}} disabled></option>
                                                <option value="1" {{(isset($searchData['is_blocked']) && $searchData['is_blocked'] == '1') ? 'selected' : ''}}>True</option>
                                                <option value="0" {{(isset($searchData['is_blocked']) && $searchData['is_blocked'] == '0') ? 'selected' : ''}}>False</option>
                                            </select>
                                        </td>
                                    </tr>
                                </form>

                            </thead>

                            <tbody>
                                @forelse ($publicIp as $ip)
                                    <tr>
                                        <td>{{$loop->index += 1}}</td>
                                        <td>{{$ip->network}}</td>
                                        <td>{{$ip->netmask}}</td>
                                        <td>{{$ip->country_name}}</td>
                                        <td>{{ $ip->is_anonymous_proxy ? 'True' : 'False' }}</td>
                                        <td>{{ $ip->is_satellite_provider ? 'True' : 'False' }}</td>
                                        <td>{{$ip->is_blocked ? 'True' : 'False'}}</td>

                                        @if ($ip->is_locked == 0)
                                            <td>
                                                
                                                @if (!$ip->is_blocked)
                                                    <a class="btn btn-warning btn-sm btn-delete" href="{{route('ip_global.blocked', ['id' => encrypt($ip->id)])}}"><i class="fa-fw fas fa-ban" aria-hidden></i></a>
                                                    <a class="btn btn-info btn-sm btn-locked" href="{{route('ip_global.locked', ['id' => encrypt($ip->id)])}}"><i class="fa-fw fa fa-lock" aria-hidden></i></a>
                                                @else
                                                    <a href="{{route('ip_global.unlocked', ['id' => encrypt($ip->id)])}}" class="btn btn-success btn-sm btn-unlocked"><i class="fas fa-check"></i></a>
                                                @endif

                                            </td>
                                        @else
                                            <td></td>
                                        @endif
                                    </tr>

                                @empty
                                    <p class="ml-2 mt-3 text-danger">IP address belum tersedia</p>
                                @endforelse

                            </tbody>
                        </table>
                    </div>
                </div>

                @if ($publicIp->lastPage() > 1)
                    <nav aria-label="Page navigation example">
                        <ul class="pagination mt-2">

                            {{-- Tombol Sebelumnya --}}
                            @if ($publicIp->currentPage() > 1)
                                <li class="page-item">
                                    <a class="page-link" href="{{ $publicIp->previousPageUrl() }}" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                            @endif
                            
                            {{-- Tampilkan 4 halaman sebelumnya jika halaman saat ini tidak terlalu dekat dengan halaman pertama --}}
                            @if ($publicIp->currentPage() > 6)
                                @for ($i = $publicIp->currentPage() - 3; $i < $publicIp->currentPage(); $i++)
                                    @if ($i == 1)
                                        <li class="page-item">
                                            <a class="page-link" href="/admin/ip_global">{{ $i }}</a>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $publicIp->url($i) }}">{{ $i }}</a>
                                        </li>
                                    @endif
                                @endfor
                            @else
                                @for ($i = 1; $i < $publicIp->currentPage(); $i++)
                                    @if ($i == 1)
                                        <li class="page-item">
                                            <a class="page-link" href="/admin/ip_global">{{ $i }}</a>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $publicIp->url($i) }}">{{ $i }}</a>
                                        </li>
                                    @endif
                                @endfor
                            @endif

                            {{-- Halaman saat ini --}}
                            <li class="page-item active">
                                <span class="page-link">{{ $publicIp->currentPage() }}</span>
                            </li>
                            
                            {{-- Tampilkan 4 halaman setelahnya jika halaman saat ini tidak terlalu dekat dengan halaman terakhir --}}
                            @if ($publicIp->currentPage() < $publicIp->lastPage() - 5)
                                @for ($i = $publicIp->currentPage() + 1; $i <= $publicIp->currentPage() + 3; $i++)
                                    @if ($i == 1)
                                        <li class="page-item">
                                            <a class="page-link" href="/admin/ip_global">{{ $i }}</a>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $publicIp->url($i) }}">{{ $i }}</a>
                                        </li>
                                    @endif
                                @endfor
                            @else
                                @for ($i = $publicIp->currentPage() + 1; $i <= $publicIp->lastPage(); $i++)
                                    @if ($i == 1)
                                        <li class="page-item">
                                            <a class="page-link" href="/admin/ip_global">{{ $i }}</a>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $publicIp->url($i) }}">{{ $i }}</a>
                                        </li>
                                    @endif
                                @endfor
                            @endif
                            
                            {{-- Tombol Selanjutnya --}}
                            @if ($publicIp->hasMorePages())
                                <li class="page-item">
                                    <a class="page-link" href="{{ $publicIp->nextPageUrl() }}" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </nav>
                @endif

            </div>

            @if (isset($publicIp) && $publicIp->count() > 0)
                <div>
                    Showing <b>{{ $publicIp->firstItem() }}</b> 
                    to <b>{{ $publicIp->lastItem() }}</b>
                    of <b>{{ $publicIp->total() }}</b> items.
                </div><br>
            @endif
        </div>
    </div>
</div>

@endsection

<script>
    document.addEventListener('DOMContentLoaded', function () {

        document.querySelectorAll('.btn-locked').forEach(button => {
            button.addEventListener('click', function(event) {
                event.preventDefault();
                const url = this.getAttribute('href');
                
                // Tampilkan SweetAlert konfirmasi penghapusan
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Ip address yang di-lock tidak dapat di-block!",
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

        document.querySelectorAll('.btn-unlocked').forEach(button => {
            button.addEventListener('click', function(event) {
                event.preventDefault();
                const url = this.getAttribute('href');
                
                // Tampilkan SweetAlert konfirmasi penghapusan
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Ip address user dapat masuk ke sistem lagi!",
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

        // Tambahkan event listener ke tombol delete
        document.querySelectorAll('.btn-delete').forEach(button => {
            button.addEventListener('click', function(event) {
                event.preventDefault();
                const url = this.getAttribute('href');
                
                // Tampilkan SweetAlert konfirmasi penghapusan
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "User dengan ip address tersebut tidak akan dapat mengakses sistem!",
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

    });
</script>

<script>
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