@extends('cms_login.index_admin')
@section('content')
<div class="container-fluid">
        
    <div class="content">
        <div class="row">
            <div class="col-lg-12">
                <h5 class="p-2">Courses</h5>

                <div class="card card-default">

                    <div class="card-header">
                        <a href="{{{route('admin.courses.add')}}}" class="btn btn-success"><i class="fa fa-plus mr-1" aria-hidden="true"></i> Add</a>
                        
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
                            <table class="table text-nowrap table-striped table-bordered mb-0" style="min-width: 120%;">
                                <thead>
                                    <tr>
                                        <td>#</td>
                                        <td>Book Title</td>
                                        <td>Terjemahan</td>
                                        <td>Status</td>
                                        <td>Parent</td>
                                        <td>File</td>
                                        <td>Created At</td>
                                        <td>Updated At</td>
                                        <td></td>
                                    </tr>

                                    <form action="{{route('admin.courses.search')}}" id="searchForm" method="get">
                                        @csrf
                                        <tr id="w0-filters" class="filters">
                                            <td></td>
                                            <td><input type="text" class="form-control" name="search[book_title]" onkeypress="handleKeyPress(event)" value="{{(isset($searchData['book_title'])) ? $searchData['book_title'] : ''}}"></td>
                                            
                                            <td>
                                                @php
                                                    $getAvailableLanguage = \App\Models\BookTranslation::select('language_id','language_name')->groupBy('language_id','language_name')->with('translations')->get();
                                                @endphp
                                                <select name=search[terjemahan]" id="terjemahan" class="form-control" onchange="this.form.submit()">
                                                    <option value="" {{(!isset($searchData['terjemahan'])) ? 'selected' : ''}} disabled></option>
                                                    @foreach ($getAvailableLanguage as $language)
                                                        <option value="{{$language->language_id}}" {{(isset($searchData['terjemahan']) && $searchData['terjemahan'] == $language->language_id) ? 'selected' : ''}}>{{$language->language_name}}</option>
                                                    @endforeach
                                                </select>
                                            </td>

                                            <td>
                                                <select name="search[status]" id="status" class="form-control" onchange="this.form.submit()">
                                                    <option value="" {{(!isset($searchData['status'])) ? 'selected' : ''}} disabled></option>
                                                    <option value="1" {{(isset($searchData['status']) && $searchData['status'] == 1) ? 'selected' : ''}}>Enable</option>
                                                    <option value="2" {{(isset($searchData['status']) && $searchData['status'] == 2) ? 'selected' : ''}}>Disable</option>
                                                    <option value="3" {{(isset($searchData['status']) && $searchData['status'] == 3) ? 'selected' : ''}}>Draft</option>
                                                </select>
                                            </td>

                                            <td><input type="text" class="form-control" name="search[parent]" onkeypress="handleKeyPress(event)" value="{{(isset($searchData['parent'])) ? $searchData['parent'] : ''}}"></td>
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
                                    @forelse ($bookTranslations as $translation)
                                        <tr>
                                            <td>{{$loop->index += 1}}</td>
                                            <td>{{$translation->book_title}}</td>
                                            <td>{{$translation->language_name}}</td>
                                            <td>{{\App\Models\MasterStatus::where('id',$translation->status_id)->first()->name}}</td>
                                            <td>{{ $translation->hierarchyCategoryBook->parentCategory->hierarchy_name }}</td>
                                            <td><a href="{{ asset('book/'.\App\Models\Translations::where('id',$translation->language_id)->first()->language_name.'/'.$translation->file) }}" download>{{$translation->file}}</a></td>
                                            <td>{{$translation->created_at}}</td>
                                            <td>{{$translation->updated_at}}</td>
                                            <td>
                                                <a class="btn btn-warning btn-sm" href="#" title="Update" aria-label="Update" data-pjax="0"><i class="fa-fw fas fa-edit" aria-hidden></i></a>
                                                
                                                <a class="btn btn-danger btn-sm btn-delete" href="#">
                                                    <i class="fa-fw fas fa-trash" aria-hidden="true"></i>
                                                </a>

                                            </td>
                                        </tr>    
                                    @empty
                                        <p class="ml-2 mt-3 text-danger">Courses belum tersedia</p>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    @if ($bookTranslations->lastPage() > 1)
                        <nav aria-label="Page navigation example">
                            <ul class="pagination mt-2 ml-2">
                                {{-- Tombol Sebelumnya --}}
                                @if ($bookTranslations->currentPage() > 1)
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $bookTranslations->previousPageUrl() }}" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                @endif
                                            {{-- Tampilkan 4 halaman sebelumnya jika halaman saat ini tidak terlalu dekat dengan halaman pertama --}}
                                @if ($bookTranslations->currentPage() > 6)
                                    @for ($i = $bookTranslations->currentPage() - 3; $i < $bookTranslations->currentPage(); $i++)
                                        @if ($i == 1)
                                            <li class="page-item">
                                                <a class="page-link" href="/admin/courses">{{ $i }}</a>
                                            </li>
                                        @else
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $bookTranslations->url($i) }}">{{ $i }}</a>
                                            </li>
                                        @endif
                                    @endfor
                                @else
                                    @for ($i = 1; $i < $bookTranslations->currentPage(); $i++)
                                        @if ($i == 1)
                                            <li class="page-item">
                                                <a class="page-link" href="/admin/courses">{{ $i }}</a>
                                            </li>
                                        @else
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $bookTranslations->url($i) }}">{{ $i }}</a>
                                            </li>
                                        @endif
                                    @endfor
                                @endif
                                            {{-- Halaman saat ini --}}
                                <li class="page-item active">
                                    <span class="page-link">{{ $bookTranslations->currentPage() }}</span>
                                </li>
                                            {{-- Tampilkan 4 halaman setelahnya jika halaman saat ini tidak terlalu dekat dengan halaman terakhir --}}
                                @if ($bookTranslations->currentPage() < $bookTranslations->lastPage() - 5)
                                    @for ($i = $bookTranslations->currentPage() + 1; $i <= $bookTranslations->currentPage() + 3; $i++)
                                        @if ($i == 1)
                                            <li class="page-item">
                                                <a class="page-link" href="/admin/courses">{{ $i }}</a>
                                            </li>
                                        @else
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $bookTranslations->url($i) }}">{{ $i }}</a>
                                            </li>
                                        @endif
                                    @endfor
                                @else
                                    @for ($i = $bookTranslations->currentPage() + 1; $i <= $bookTranslations->lastPage(); $i++)
                                        @if ($i == 1)
                                            <li class="page-item">
                                                <a class="page-link" href="/admin/courses">{{ $i }}</a>
                                            </li>
                                        @else
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $bookTranslations->url($i) }}">{{ $i }}</a>
                                            </li>
                                        @endif
                                    @endfor
                                @endif

                                {{-- Tombol Selanjutnya --}}
                                @if ($bookTranslations->hasMorePages())
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $bookTranslations->nextPageUrl() }}" aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </nav>
                    @endif

                </div>
            </div>

            @if (isset($bookTranslations) && $bookTranslations->count() > 0 && $bookTranslations->lastPage() > 1)
                <div class="p-2">
                    Showing <b>{{ $bookTranslations->firstItem() }}</b> 
                    to <b>{{ $bookTranslations->lastItem() }}</b>
                    of <b>{{ $bookTranslations->total() }}</b> items.
                </div><br><br>
            @endif

        </div>
    </div>

</div>

@endsection

<script>
    document.addEventListener('DOMContentLoaded', function () {

    // Tambahkan event listener ke tombol delete
        document.querySelectorAll('.btn-delete').forEach(button => {
            button.addEventListener('click', function(event) {
                event.preventDefault();
                const url = this.getAttribute('href');
                
                // Tampilkan SweetAlert konfirmasi penghapusan
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Tutorial yang dihapus tidak akan ditampilkan ke user!",
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
    let successMessage = '{{ session('success') }}';

    function handleKeyPress(event) {
        // Periksa apakah tombol yang ditekan adalah tombol "Enter" (kode 13)
        if (event.keyCode === 13) {
            // Hentikan perilaku bawaan dari tombol "Enter" (yang akan mengirimkan formulir)
            event.preventDefault();
            // Submit formulir secara manual
            document.getElementById('searchForm').submit();
        }
    }
    
    if (successMessage) {
        showNotification(successMessage, 'success');
    }

</script>

@php
    session()->forget('success_submit_save');
    session()->forget('error_submit_save');
@endphp