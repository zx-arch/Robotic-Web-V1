@extends('cms_login.index_admin')

@section('content')

<div class="container-fluid">
        
    <div class="content">
        <div class="row">
            <div class="col-lg-12">
                <h5 class="p-2">Events</h5>

                <div class="card">
                    
                    <div class="card-header">
                        <a href="{{route('admin.events.add')}}" class="btn btn-success">Add Events</a>
                        <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#createPresensiModal">Create Presensi</a>

                        <div class="modal fade" id="createPresensiModal" tabindex="-1" role="dialog" aria-labelledby="createPresensiModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="createPresensiModalLabel">Create Presensi</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>

                                    <div class="modal-body">

                                        <form id="presensiForm" action="{{route('admin.events.createAttendance')}}" method="post">
                                            @csrf
                                            <div class="form-group">
                                                <label for="event">Event <span class="text-danger fw-bold">*</span></label>
                                                <select class="form-control" id="event" name="event_name" required>
                                                    <option value="" disabled selected>Choose Event</option>
                                                    @forelse ($eventNotSetPresensi as $ev)
                                                        <option value="{{$ev->nama_event}}" data-code="{{\Illuminate\Support\Str::random(15)}}" event-code="{{$ev->code_event}}">{{$ev->nama_event}}</option>
                                                    @endforeach
                                                    <!-- Tambahkan opsi event lain di sini -->
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="status">Status Presensi <span class="text-danger fw-bold">*</span></label>
                                                <select class="form-control" id="status" name="status" required>
                                                    <option value="" disabled selected>Choose Status</option>
                                                    <option value="Enable">Enable</option>
                                                    <option value="Disable">Disable</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="opening_date">Tanggal Buka Presensi <span class="text-danger fw-bold">*</span></label>
                                                <input type="datetime-local" id="opening_date" name="opening_date" required class="form-control w-50" min="{{ date('Y-m-d\TH:i') }}">
                                            </div>
                                            <div class="form-group">
                                                <label for="closing_date">Tanggal Tutup Presensi</label>
                                                <input type="datetime-local" id="closing_date" name="closing_date" class="form-control w-50" min="{{ date('Y-m-d\TH:i') }}"><br>
                                            </div>
                                            <!-- Tempat untuk menampilkan link yang dihasilkan -->
                                            <div id="generatedLink" class="form-group"></div>
                                            <p class="text-danger fw-bold"></p>

                                            <button type="submit" class="btn btn-primary mt-3">Submit</button>
                                            <div class="alert alert-warning mt-3">Note: Apabila tidak menemukan event yang dicari, silakan cek menu list presensi</div>
                                        </form>
                                    </div>

                                </div>
                            </div>
                        </div>

                        @if (session()->has('success_saved'))
                            <div id="w6" class="alert-primary alert alert-dismissible mt-3 w-75" role="alert">
                                {{session('success_saved')}}
                                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span></button>
                            </div>
                        @endif

                        @if (session()->has('error_saved'))
                            <div id="w6" class="alert-danger alert alert-dismissible mt-3 w-75" role="alert">
                                {{session('error_saved')}}
                                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span></button>
                            </div>
                        @endif

                        @if (session()->has('delete_successfull'))
                            <div id="w6" class="alert-warning alert alert-dismissible mt-3 w-75" role="alert">
                                {{session('delete_successfull')}}
                                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span></button>
                            </div>
                        @endif

                        @if (session()->has('error_delete'))
                            <div id="w6" class="alert-danger alert alert-dismissible mt-3 w-75" role="alert">
                                {{session('error_delete')}}
                                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span></button>
                            </div>
                        @endif
                    </div>

                    <div class="card-body">
                        <div class="card-body p-0" style="overflow-x: auto;">
                            <div id="w0" class="gridview table-responsive">
                                <table class="table text-nowrap table-striped table-bordered mb-0">
                                    <thead>
                                        <tr>
                                            <td>#</td>
                                            <td>Code Event</td>
                                            <td>Nama Event</td>
                                            <td>Status</td>
                                            <td>Buka Presensi</td>
                                            <td>Tutup Presensi</td>
                                            <td>Access Code</td>
                                            <td>Total Participant</td>
                                            <td>Peserta Hadir</td>
                                            <td>Peserta Tidak Hadir</td>
                                            <td></td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($listPresensi as $presensi)
                                            <tr>
                                                @php
                                                    $opening_date = \Carbon\Carbon::parse($presensi->opening_date);
                                                    $formattedopening_date = $opening_date->isoFormat('ddd, D MMMM YYYY HH:mm');
                                                    
                                                    $closing_date = \Carbon\Carbon::parse($presensi->closing_date);
                                                    $formattedclosing_date = $closing_date->isoFormat('ddd, D MMMM YYYY HH:mm');
                                                @endphp

                                                <td>{{$loop->index += 1}}</td>
                                                <td>{{$presensi->event_code}}</td>
                                                <td>{{$presensi->event_name}}</td>
                                                <td>{{$presensi->status}}</td>
                                                <td>{{$formattedopening_date}}</td>
                                                <td>{{$formattedclosing_date}}</td>
                                                <td>{{$presensi->access_code}}</td>
                                                <td>{{$presensi->total_peserta}}</td>
                                                <td>{{$presensi->peserta_hadir}}</td>
                                                <td>{{$presensi->peserta_tidak_hadir}}</td>
                                                <td>
                                                    <a class="btn btn-warning btn-sm" href="#" title="Update" aria-label="Update" data-pjax="0"><i class="fa-fw fas fa-edit" aria-hidden></i></a>
                                                    <a class="btn btn-danger btn-sm btn-delete" href="#" title="Delete" aria-label="Delete" data-pjax="0"><i class="fa-fw fas fa-trash" aria-hidden></i></a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="11" class="text-center text-danger">Data Presensi tidak ditemukan!</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection

<script>
    document.addEventListener('DOMContentLoaded', function () {
        
        // Event listener untuk select event
        document.getElementById('event').addEventListener('change', function() {
            // Ambil nilai dan data-code dari option yang dipilih
            const selectedOption = this.options[this.selectedIndex];
            const selectedEvent = this.value;
            const eventCode = selectedOption.getAttribute('event-code');
            const accessCode = selectedOption.getAttribute('data-code');

            if (selectedEvent) {
                const eventSlug = selectedEvent.toLowerCase().replace(/\s+/g, '-');
                // Buat URL link
                const generatedUrl = `{{ env('APP_URL') }}/event/${eventSlug}`;
                // Tampilkan link yang dihasilkan dengan border dan ikon copy
                const generatedLink = `
                    <div style="border: 1px solid rgba(0, 0, 0, 0.1); padding: 5px; border-radius: 5px;">
                        <a href="${generatedUrl}" target="_blank" style="word-wrap: break-word;">${generatedUrl}</a>
                        <i class="far fa-copy ml-2" style="cursor: pointer;" onclick="copyToClipboard('${generatedUrl}')"></i>
                        <div class="mt-2">Code Access: ${accessCode}</div>
                    </div>
                    <input type="hidden" value="${accessCode}" name="access_code">
                    <input type="hidden" value="${eventCode}" name="event_code">`;

                document.getElementById('generatedLink').innerHTML = generatedLink;

            } else {
                document.getElementById('generatedLink').innerHTML = ''; // Kosongkan jika tidak ada event yang dipilih
            }
        });

        // Fungsi untuk menyalin teks ke clipboard
        function copyToClipboard(text) {
            const input = document.createElement('textarea');
            input.value = text;
            document.body.appendChild(input);
            input.select();
            document.execCommand('copy');
            document.body.removeChild(input);
            alert('Link copied to clipboard!');
        }

        document.querySelectorAll('.btn-delete').forEach(button => {
            button.addEventListener('click', function(event) {
                event.preventDefault();
                const url = this.getAttribute('href');
                
                // Tampilkan SweetAlert konfirmasi penghapusan
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Event yang dihapus tidak dapat dipulihkan!",
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