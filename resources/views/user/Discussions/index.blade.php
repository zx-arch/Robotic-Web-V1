@extends('cms_login.index_user')

@section('content')
<main class="content px-3 py-4">
    <div class="container-fluid">
        <div class="box mb-4">
            <div class="box-body d-flex justify-content-between align-items-center">
                <h4 class="m-0 highlight-title">Forum Diskusi</h4>
                <a href="{{route('user.discussions.add')}}" class="btn btn-primary">Add</a>
            </div>
        </div>

        @if (session()->has('success_saved'))
            <div class="alert alert-success w-25">
                {{session('success_saved')}}
            </div>
        @endif

        @if (isset($discussions))

            @forelse ($discussions as $discussion)
                <div class="box mb-3 rounded shadow-lg">
                    <div class="box-body p-3">
                        <h5>{{ $discussion->title }}</h5>
                        <p class="subs-title mt-3">{{ Str::limit($discussion->message, 150) }}</p>
                        <div class="d-flex justify-content-end">
                            <small class="text-muted">{{ $discussion->username }} - {{ $discussion->created_at->diffForHumans() }}</small>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-warning"><strong>Data discussions belum tersedia !</strong></p>
            @endforelse

        @else
            <p class="text-warning"><strong>Data discussions belum tersedia !</strong></p>
        @endif
        
    </div>
</main>
@endsection
