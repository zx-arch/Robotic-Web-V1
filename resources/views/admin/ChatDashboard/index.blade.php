@extends('cms_login.index_admin')

<style>
    td:nth-child(5) {
        word-wrap: break-word;
        max-width: 300px;
        white-space: pre-wrap;
        line-height: 1.5;
    }
</style>

<!-- Memuat jQuery dari CDN -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Memuat jQuery UI dari CDN -->
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

@section('content')
<div class="container-fluid">

    <div class="box">
        <div class="box-body">
            <h5 class="p-2">Chat</h5>

            <div class="card card-default">

                <div class="col-lg-12">
                    <button id="deleteChat" class="btn btn-danger mb-2 mt-1" style="display: none;"><i class="fa fa-trash mr-1" aria-hidden="true"></i> Delete</button>
                    
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

                </div>

                <div class="card-body p-0" style="overflow-x: auto;">
                    <div id="w0" class="gridview table-responsive">
                        <table class="table text-nowrap table-striped table-bordered mb-0">
                            <thead>
                                <tr>
                                    <td></td>
                                    <td>ID</td>
                                    <td>Name</td>
                                    <td>Email</td>
                                    <td>Subject</td>
                                    <td>Message</td>
                                    <td>Dibuat</td>
                                </tr>
                            </thead>
                            <tbody>
                                
                                @forelse ($chats as $chat)
                                    <tr>
                                        <td><input type="checkbox" name="check[delete]" class="form-control" style="max-width: 15px;" id="delete_id" value="{{$chat->id}}"></td>
                                        <td>{{$chat->id}}</td>
                                        <td>{{$chat->name}}</td>
                                        <td><a href="mailto:{{$chat->email}}">{{$chat->email}}</a></td>
                                        <td>{{$chat->subject}}</td>
                                        <td>{{$chat->message}}</td>
                                        <td>{{$chat->created_at}}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" style="font-weight: bold;" class="text-danger">Chat belum tersedia</td>
                                    </tr>
                                @endforelse

                            </tbody>
                        </table>
                    
                        <form id="deleteForm" action="{{route('admin.chat_dashboard.delete')}}" method="POST">
                            @csrf
                            <input type="hidden" name="delete_ids" id="delete_ids">
                        </form>

                    </div>
                </div>
                    @if ($chats->lastPage() > 1)
                        <nav aria-label="Page navigation example">
                            <ul class="pagination mt-2 ml-2">

                                {{-- Tombol Sebelumnya --}}
                                @if ($chats->currentPage() > 1)
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $chats->previousPageUrl() }}" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                @endif
                                
                                {{-- Tampilkan 4 halaman sebelumnya jika halaman saat ini tidak terlalu dekat dengan halaman pertama --}}
                                @if ($chats->currentPage() > 6)
                                    @for ($i = $chats->currentPage() - 3; $i < $chats->currentPage(); $i++)
                                        @if ($i == 1)
                                            <li class="page-item">
                                                <a class="page-link" href="/admin/chat">{{ $i }}</a>
                                            </li>
                                        @else
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $chats->url($i) }}">{{ $i }}</a>
                                            </li>
                                        @endif
                                    @endfor

                                @else
                                    @for ($i = 1; $i < $chats->currentPage(); $i++)
                                        @if ($i == 1)
                                            <li class="page-item">
                                                <a class="page-link" href="/admin/chat">{{ $i }}</a>
                                            </li>
                                        @else
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $chats->url($i) }}">{{ $i }}</a>
                                            </li>
                                        @endif
                                    @endfor
                                @endif
                                
                                {{-- Halaman saat ini --}}
                                <li class="page-item active">
                                    <span class="page-link">{{ $chats->currentPage() }}</span>
                                </li>
                                
                                {{-- Tampilkan 4 halaman setelahnya jika halaman saat ini tidak terlalu dekat dengan halaman terakhir --}}
                                @if ($chats->currentPage() < $chats->lastPage() - 5)
                                    @for ($i = $chats->currentPage() + 1; $i <= $chats->currentPage() + 3; $i++)
                                        @if ($i == 1)
                                            <li class="page-item">
                                                <a class="page-link" href="/admin/chat">{{ $i }}</a>
                                            </li>
                                        @else
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $chats->url($i) }}">{{ $i }}</a>
                                            </li>
                                        @endif
                                    @endfor
                                    
                                @else
                                    @for ($i = $chats->currentPage() + 1; $i <= $chats->lastPage(); $i++)
                                        @if ($i == 1)
                                            <li class="page-item">
                                                <a class="page-link" href="/admin/chat">{{ $i }}</a>
                                            </li>
                                        @else
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $chats->url($i) }}">{{ $i }}</a>
                                            </li>
                                        @endif
                                    @endfor
                                @endif

                                {{-- Tombol Selanjutnya --}}
                                @if ($chats->hasMorePages())
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $chats->nextPageUrl() }}" aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </nav>
                    @endif

                </div>
            </div>

            @if (isset($chats) && $chats->count() > 0 && $chats->lastPage() > 1)
                <div class="p-2">
                    Showing <b>{{ $chats->firstItem() }}</b> 
                    to <b>{{ $chats->lastItem() }}</b>
                    of <b>{{ $chats->total() }}</b> items.
                </div><br><br>
            @endif
            
        </div>
    </div>

</div>

@endsection

<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>

<script>
    // Simpan referensi ke fungsi log asli dari console
    var originalConsoleLog = console.log;

    // Override fungsi log dari console untuk mencegah pesan log dari Pusher
    console.log = function() {
        // Periksa apakah pesan log berasal dari Pusher
        if (arguments.length > 0 && typeof arguments[0] === 'string' && arguments[0].includes('Pusher :')) {
            // Jika ya, jangan cetak pesan log
            return;
        }
        // Jika bukan dari Pusher, cetak pesan log seperti biasa
        originalConsoleLog.apply(console, arguments);
    };
    // Pesan log dari Pusher yang mencetak ke konsol akan dihentikan, tetapi pesan log lainnya akan tetap dicetak ke konsol.

    document.addEventListener('DOMContentLoaded', function () {

        function updateButton() {
            var checkedCheckboxes = document.querySelectorAll('input[name="check[delete]"]:checked');
            var deleteButton = document.getElementById('deleteChat');
            var deleteForm = document.getElementById('deleteForm');
            var deleteIdsInput = document.getElementById('delete_ids');

            // Tambahkan logika untuk menentukan apakah tombol hapus harus ditampilkan atau disembunyikan
            if (checkedCheckboxes.length > 0) {
                deleteButton.style.display = 'inline-block';
                deleteForm.style.display = 'inline-block';

                // Hitung total ceklis yang diceklis
                var totalChecked = checkedCheckboxes.length;
                deleteButton.innerHTML = '<i class="fa fa-trash mr-1" aria-hidden="true"></i> '+totalChecked+' Items Deleted';

                // Kumpulkan ID dari input yang diceklis
                var deleteIds = Array.from(checkedCheckboxes).map(function(checkbox) {
                    return checkbox.value;
                }).join(', ');

                deleteIdsInput.value = deleteIds;
                
                deleteButton.addEventListener('click', function () {
                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: "Chat yang dihapus tidak dapat dipulihkan!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, hapus!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Jika pengguna menekan tombol "Ya, hapus", arahkan ke URL penghapusan
                            deleteForm.submit();
                        }
                    });
                });

            } else {
                deleteButton.style.display = 'none';
                deleteForm.style.display = 'none';
            }
        }

        // Panggil fungsi saat ada perubahan pada input ceklis
        var checkboxes = document.querySelectorAll('input[name="check[delete]"]');
        checkboxes.forEach(function(checkbox) {
            checkbox.addEventListener('change', updateButton);
        });
        
        fetch('/api/pusher-key')
            .then(response => response.json())
            .then(data => {
                var pusher = new Pusher(data.pusher_app_key, {
                    cluster: data.pusher_app_cluster
                });

                var channel = pusher.subscribe('notify-channel');
                channel.bind('form-submit', function(data) {
                    // Update tbody with new data
                    var tbody = document.querySelector('tbody');
                    tbody.innerHTML = ''; // Clear tbody first

                    data.message.chats.forEach(function(chat) {
                        var tr = document.createElement('tr');

                        var checkboxTd = document.createElement('td');
                        var checkbox = document.createElement('input');
                        checkbox.setAttribute('type', 'checkbox');
                        checkbox.setAttribute('name', 'check[delete]');
                        checkbox.setAttribute('class', 'form-control');
                        checkbox.setAttribute('style', 'max-width: 15px;');
                        checkbox.setAttribute('id', 'delete_id');
                        checkbox.setAttribute('value', chat.id);
                        checkboxTd.appendChild(checkbox);

                        // Add event listener to the newly created checkbox
                        checkbox.addEventListener('change', updateButton);

                        var idTd = document.createElement('td');
                        idTd.textContent = chat.id;

                        var nameTd = document.createElement('td');
                        nameTd.textContent = chat.name;

                        var emailTd = document.createElement('td');
                        var emailLink = document.createElement('a');
                        emailLink.setAttribute('href', 'mailto:' + chat.email);
                        emailLink.textContent = chat.email;
                        emailTd.appendChild(emailLink);

                        var subjectTd = document.createElement('td');
                        subjectTd.textContent = chat.subject;

                        var messageTd = document.createElement('td');
                        messageTd.textContent = chat.message;

                        var createdAtTd = document.createElement('td');

                        // Buat objek Date dari string waktu UTC
                        var utcDate = new Date(chat.created_at);

                        // Ubah zona waktu ke Asia/Jakarta
                        var jakartaDate = new Date(utcDate.toLocaleString('en-ID', { timeZone: 'Asia/Jakarta' }));

                        // Format tanggal dan waktu sesuai kebutuhan
                        var formattedDateTime = jakartaDate.toISOString().split('T')[0] + ' ' + ('0' + jakartaDate.getHours()).slice(-2) + ':' + ('0' + jakartaDate.getMinutes()).slice(-2) + ':' + ('0' + jakartaDate.getSeconds()).slice(-2);

                        createdAtTd.textContent = formattedDateTime;

                        tr.appendChild(checkboxTd);
                        tr.appendChild(idTd);
                        tr.appendChild(nameTd);
                        tr.appendChild(emailTd);
                        tr.appendChild(subjectTd);
                        tr.appendChild(messageTd);
                        tr.appendChild(createdAtTd);

                        tbody.appendChild(tr);
                    });

                    // Call the function to update the delete button status
                    updateButton();
                })

                .catch(error => {
                    console.error('Error:', error);
                    // Jika terjadi kesalahan saat fetching data pusher, panggil fungsi updateButton untuk memastikan tombol hapus tetap ditampilkan atau disembunyikan
                    updateButton();
                });
            })

        .catch(error => {
            console.error('Error:', error);
            // Jika terjadi kesalahan saat fetching data pusher, panggil fungsi updateButton untuk memastikan tombol hapus tetap ditampilkan atau disembunyikan
            updateButton();
        });
    });

</script>
