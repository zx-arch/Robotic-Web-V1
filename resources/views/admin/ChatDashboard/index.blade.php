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
                                    <td>#</td>
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
                                        <td><input type="checkbox" name="check[delete]" id="delete_id" value="{{$chat->id}}"></td>
                                        <td>{{$loop->index += 1}}</td>
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
            </div>
            
        </div>
    </div>

</div>

@endsection

<script>

    document.addEventListener('DOMContentLoaded', function () {

        function updateButton() {
            var checkedCheckboxes = document.querySelectorAll('input[name="check[delete]"]:checked');
            var deleteButton = document.getElementById('deleteChat');
            var deleteForm = document.getElementById('deleteForm');
            var deleteIdsInput = document.getElementById('delete_ids');

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
        
    });
</script>