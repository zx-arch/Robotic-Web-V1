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

            @php
                $ip = App\Models\ListIP::query();
                $ipUnblocked = $ip->where('is_blocked', false)->count();
                $ipBlocked = $ip->where('is_blocked', true)->count();
            @endphp

            <div class="row">
                <div class="col-md-4 col-sm-6 col-12">
                    <div class="info-box bg-info d-flex align-items-center">
                        <span class="info-box-icon"><i class="fas fa-user"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total IP Address</span>
                            <span class="info-box-number">{{ $ipUnblocked + $ipBlocked }}</span>
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

                                <form action="{{route('ip_address.search')}}" id="searchForm" method="get">
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
                                @forelse ($listIP as $ip)
                                    <tr>
                                        <td>{{$loop->index += 1}}</td>
                                        <td>{{$ip->network}}</td>
                                        <td>{{$ip->netmask}}</td>
                                        <td>{{$ip->country_name}}</td>
                                        <td>{{ $ip->is_anonymous_proxy ? 'True' : 'False' }}</td>
                                        <td>{{ $ip->is_satellite_provider ? 'True' : 'False' }}</td>
                                        <td>{{$ip->is_blocked ? 'True' : 'False'}}</td>
                                        <td>
                                            <a class="btn btn-warning btn-sm btn-delete" href="#"><i class="fa-fw fas fa-ban" aria-hidden></i></a>
                                        </td>
                                    </tr>
                                @empty
                                    <p class="ml-2 mt-3 text-danger">IP address belum tersedia</p>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                @if ($listIP->lastPage() > 1)
                    <nav aria-label="Page navigation example">
                        <ul class="pagination mt-2">
                            {{-- Tombol Sebelumnya --}}
                            @if ($listIP->currentPage() > 1)
                                <li class="page-item">
                                    <a class="page-link" href="{{ $listIP->previousPageUrl() }}" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                            @endif
                            
                            {{-- Tampilkan 4 halaman sebelumnya jika halaman saat ini tidak terlalu dekat dengan halaman pertama --}}
                            @if ($listIP->currentPage() > 6)
                                @for ($i = $listIP->currentPage() - 3; $i < $listIP->currentPage(); $i++)
                                    @if ($i == 1)
                                        <li class="page-item">
                                            <a class="page-link" href="/admin/ip_address">{{ $i }}</a>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $listIP->url($i) }}">{{ $i }}</a>
                                        </li>
                                    @endif
                                @endfor
                            @else
                                @for ($i = 1; $i < $listIP->currentPage(); $i++)
                                    @if ($i == 1)
                                        <li class="page-item">
                                            <a class="page-link" href="/admin/ip_address">{{ $i }}</a>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $listIP->url($i) }}">{{ $i }}</a>
                                        </li>
                                    @endif
                                @endfor
                            @endif

                            {{-- Halaman saat ini --}}
                            <li class="page-item active">
                                <span class="page-link">{{ $listIP->currentPage() }}</span>
                            </li>
                            
                            {{-- Tampilkan 4 halaman setelahnya jika halaman saat ini tidak terlalu dekat dengan halaman terakhir --}}
                            @if ($listIP->currentPage() < $listIP->lastPage() - 5)
                                @for ($i = $listIP->currentPage() + 1; $i <= $listIP->currentPage() + 3; $i++)
                                    @if ($i == 1)
                                        <li class="page-item">
                                            <a class="page-link" href="/admin/ip_address">{{ $i }}</a>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $listIP->url($i) }}">{{ $i }}</a>
                                        </li>
                                    @endif
                                @endfor
                            @else
                                @for ($i = $listIP->currentPage() + 1; $i <= $listIP->lastPage(); $i++)
                                    @if ($i == 1)
                                        <li class="page-item">
                                            <a class="page-link" href="/admin/ip_address">{{ $i }}</a>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $listIP->url($i) }}">{{ $i }}</a>
                                        </li>
                                    @endif
                                @endfor
                            @endif
                            
                            {{-- Tombol Selanjutnya --}}
                            @if ($listIP->hasMorePages())
                                <li class="page-item">
                                    <a class="page-link" href="{{ $listIP->nextPageUrl() }}" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </nav>
                @endif

            </div>

            @if (isset($listIP) && $listIP->count() > 0)
                <div>
                    Showing <b>{{ $listIP->firstItem() }}</b> 
                    to <b>{{ $listIP->lastItem() }}</b>
                    of <b>{{ $listIP->total() }}</b> items.
                </div><br>
            @endif
        </div>
    </div>
</div>

@endsection

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