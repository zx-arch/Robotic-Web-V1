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
                <div class="card-body p-0" style="overflow-x: auto;">
                    <div id="w0" class="gridview table-responsive">
                        <table class="table text-nowrap table-striped table-bordered mb-0">
                            <thead>
                                <tr>
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
                                        <td>{{$loop->index += 1}}</td>
                                        <td>{{$chat->name}}</td>
                                        <td>{{$chat->email}}</td>
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
                    </div>
                </div>
            </div>
            
        </div>
    </div>

</div>

@endsection