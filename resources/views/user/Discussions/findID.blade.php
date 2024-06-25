@extends('cms_login.index_user')

<style>
    #drop-area {
        border: 2px dashed #ccc;
        border-radius: 10px;
        padding: 20px;
        margin-top: 20px;
        cursor: pointer;
    }

    #drop-area p {
        margin: 0;
        font-size: 16px;
        line-height: 20px;
    }

    #preview {
        max-width: 100%;
        max-height: 95px;
        margin-top: 10px;
    }

    #filename {
        margin-top: 10px;
    }

    .center-content {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100%; /* Memastikan konten tetap di tengah vertikal */

    }

    #drop-text, #filename {
        text-align: center;
    }

    /* Modal container */
    .modal {
        display: none;
        position: fixed; 
        z-index: 1; 
        padding-top: 150px; 
        left: 0;
        top: 0;
        width: 100%; 
        height: 100%; 
        overflow: auto; 
        background-color: rgb(0,0,0); 
        background-color: rgba(0,0,0,0.9);
        display: flex;
        justify-content: center;
        align-items: center;
        transition: opacity 0.6s ease, visibility 0.6s ease; /* Smooth transition for opacity and visibility */
    }

    /* Modal content (image) */
    .modal-content {
        margin: auto;
        display: block;
        width: 80%;
        max-width: 700px;
        transition: transform 0.6s ease; /* Smooth transition for scaling */
    }

    /* Close button */
    .close {
        position: absolute;
        top: 15px;
        right: 35px;
        color: #f1f1f1;
        font-size: 40px;
        font-weight: bold;
        transition: 0.6s;
        transition: transform 0.6s ease; /* Smooth transition for scaling */
    }

    .close:hover,
    .close:focus {
        color: #bbb;
        text-decoration: none;
        cursor: pointer;
        transform: scale(1.2); /* Scale up the close icon on hover */
    }

    /* Download button */
    .download {
        position: absolute;
        top: 35px;
        right: 80px; /* 35px (close icon) + 15px (gap) = 50px */
        color: #f1f1f1;
        font-size: 40px;
        text-decoration: none;
        transition: transform 0.6s ease; /* Smooth transition for scaling */
    }

    .download:hover {
        color: #bbb;
        transform: scale(1.2); /* Scale up the download icon on hover */
    }

    .download i {
        font-size: 20px; /* Make sure the icon size matches the close button size */
        vertical-align: middle; /* Align the icon vertically in the middle */
        line-height: 1; /* Ensure the line height is 1 to prevent excess height */
    }

    /* styles.css atau app.css */
    .img-box {
        width: 50%;
        height: 25%; /* Sesuaikan tinggi sesuai kebutuhan */
        display: flex;
        justify-content: center;
        align-items: center;
        overflow: hidden;
    }

    .img-box img {
        width: 100%;
        height: 100%;
        object-fit: cover; /* Atau gunakan 'contain' atau 'fill' sesuai kebutuhan */
        object-position: center; /* Posisi gambar di dalam kotak */
    }

</style>

@section('content')

<main class="content px-3 py-4">
    <div class="container-fluid">
        <div class="box mb-4">
            <div class="box-body d-flex justify-content-between align-items-center">
                <h4 class="m-0 highlight-title">Forum Diskusi</h4>
                <a href="{{route('user.discussions.add')}}" class="btn btn-primary">Add</a>
            </div>
        </div>
        <div class="box mb-3 rounded shadow-lg">
            <div class="box-body p-3">
                <h5 style="font-size: 25px;" class="mb-2">{{$discussion->title}}</h5>
                <div class="d-flex flex-wrap pb-2 mb-3 border-bottom border-dark" style="font-size: 13px;">
                    <div class="flex-item ws-nowrap mr-2 mb-2 me-4" title="{{$discussion->created_at}}">
                        <span class="text-secondary mr-2">Asked</span>
                        <time itemprop="dateCreated" datetime="{{$discussion->created_at}}">{{$discussion->created_at->diffForHumans()}}</time>
                    </div>
                    <div class="flex-item ws-nowrap mr-2 mb-2 me-4">
                        <span class="text-secondary mr-2">Modified</span>
                        <span class="text-link" title="{{$discussion->updated_at}}">{{$time_difference}}</span>
                    </div>
                    <div class="flex-item ws-nowrap mb-2 mr-2" title="Viewed {{$discussion->views}} times">
                        <span class="text-secondary mr-2">Viewed</span>
                        {{$discussion->views}} times
                    </div>
                </div>
                <p>{!! $discussion->message !!}</p>

                @if(asset($discussion->gambar) && isset($discussion->gambar))
                    <div class="img-box">
                        <img src="{{$discussion->gambar}}" alt="">
                    </div>
                @endif

                <div class="d-flex align-items-center mt-2">
                    <button type="button" class="btn {{$discussion->user_id == Auth::user()->id && $discussion->is_clicked_like ? 'btn-primary' : 'btn-light'}} like-button"
                            data-discussion-id="{{$discussion->id}}" data-liked="{{ $discussion->user_id == Auth::user()->id && $discussion->is_clicked_like ? 'true' : 'false' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-hand-thumbs-up" viewBox="0 0 16 16">
                            <path d="M8.864.046C7.908-.193 7.02.53 6.956 1.466c-.072 1.051-.23 2.016-.428 2.59-.125.36-.479 1.013-1.04 1.639-.557.623-1.282 1.178-2.131 1.41C2.685 7.28 2 7.87 2 8.72v4.001c0 .845.682 1.464 1.448 1.545 1.07.11 1.564-.5 2.83-.5 1.292 0 1.5.5 2.5.5s1.5-.5 2.5-.5c.973 0 1.407.444 2.29.488 1.05.047 1.71-.61 1.71-1.499V8.72c0-.81-.487-1.384-1.072-1.724-.543-.32-1.2-.518-1.855-.595-.687-.082-1.354-.2-1.85-.4-.273-.112-.491-.267-.646-.464-.128-.158-.228-.34-.291-.518-.062-.175-.093-.35-.131-.524-.24-1.06-.368-2.288-.74-2.714-.17-.198-.334-.27-.48-.276zM11.5 14a.5.5 0 0 1 .5-.5h.5a.5.5 0 0 1 0 1h-.5a.5.5 0 0 1-.5-.5zm-2-4.95a.5.5 0 0 0-.5-.5H8a.5.5 0 0 0 0 1h1a.5.5 0 0 0 .5-.5z"/>
                        </svg> 
                        <span id="like-count-{{ $discussion->id }}">{{ $discussion->likes }}</span> Like
                    </button>
                </div>
            </div>
        </div>

        <div class="card list-answer-{{$discussion->id}}">
            <div class="card-body">
                <h5 style="font-size: 20px;" class="mb-3">{{ $discussionStats->total_answers ?? 0 }} Answers</h5>
                
                @foreach($answers as $answer)
                    <div class="border-bottom mb-3 pb-2">
                        <p><small class="text-primary fw-bold">{{$answer->username}}</small></p>

                        @if(asset($answer->gambar) && isset($answer->gambar))
                            <img src="{{ asset($answer->gambar) }}" alt="Preview Image" class="thumbnail" width="70" height="70">
                            
                            <div id="myModal" class="modal">
                                <span class="close">&times;</span>
                                <a id="downloadLink" download="image.jpg" class="download" href="#">
                                    <i class="fas fa-download"></i>
                                </a>
                                <img class="modal-content" id="img01">
                            </div>
                        @endif

                        <p>{{ $answer->message }}</p>

                        <div class="d-flex justify-content-between" style="font-size: 13px;">
                            <div class="d-flex justify-content-left flex-wrap align-items-center">
                                <a href="#" class="text-muted me-2 like-count like-btn-answer" data-answer-id="{{ $answer->id }}" data-discussion-id="{{ $discussion->id }}" data-liked="{{ $answer->is_clicked_like ? 'true' : 'false' }}" id="like-count-{{ $answer->id }}">
                                    <small class="text-like {{ $answer->is_clicked_like ? 'text-primary' : 'text-secondary' }}">{{ $answer->like ?? 0 }} Likes</small>
                                </a>
                                <small class="text-muted me-2">{{ $answer->replies->count() ?? 0 }} Comment</small>
                                <a href="#" data-bs-toggle="collapse" data-bs-target="#replyForm-{{ $answer->id }}" aria-expanded="false" aria-controls="replyForm-{{ $answer->id }}">
                                    Reply
                                </a>
                            </div>
                        </div>

                        <div class="collapse mt-2" id="replyForm-{{$answer->id}}">
                            <form action="{{ route('user.discussions.saveReply') }}" method="POST">
                                @csrf
                                <input type="hidden" name="answer_id" value="{{ $answer->id }}">
                                <input type="hidden" name="discussion_id" value="{{ $discussion->id }}">
                                <div class="form-group mb-2">
                                    <textarea class="form-control" name="message" rows="3" required></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary btn-sm">Post Reply</button>
                            </form>
                        </div>

                        @if($answer->replies->count() > 0)
                            <div class="result-comment p-3" style="font-size: 15px;">
                                @foreach($answer->replies as $reply)
                                    <div class="border-bottom mb-3 pb-2">
                                        <p>{{ $reply->message }}</p>
                                        <div class="d-flex justify-content-between" style="font-size: 13px;">
                                            <div class="d-flex justify-content-left flex-wrap align-items-center">
                                                <small class="text-muted me-2"><span class="text-primary">{{ $reply->username ?? 'Anonymous' }}</span> - {{ $reply->created_at->diffForHumans() }}</small>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Form to post a new answer -->
        <div class="card mt-4">
            <div class="card-body">
                <h5 style="font-size: 20px;" class="mb-2">Post an Answer</h5>
                <form action="{{route('user.discussions.saveAnswer')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="discussion_id" value="{{ $discussion->id }}">
                    <div class="form-group mb-3">
                        <label for="message" class="mb-2">Your Answer</label>
                        <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                    </div>
                    <div class="form-group highlight-addon has-success">
                        <label for="poster">Upload gambar</label>
                        <div class="custom-file">
                            <input type="file" name="gambar" id="url_link" class="custom-file-input" accept=".png, .jpg, .jpeg" maxlength="52428800">
                            <label class="custom-file-label" for="url_link">Pilih gambar</label>
                        </div>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="card" id="drop-area">
                        <div class="card-body" style="height: 150px;"> <!-- Perbesar tinggi card-body -->
                            <div class="center-content">
                                <p id="drop-text">Drag & drop gambar di sini <br> <br> max upload 500 KB</p>
                                <img src="#" alt="Preview" id="preview" class="img-fluid d-none">
                                <p id="filename" class="d-none"></p>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Post Answer</button>
                </form>
            </div>
        </div>
    </div>
</main>

@endsection