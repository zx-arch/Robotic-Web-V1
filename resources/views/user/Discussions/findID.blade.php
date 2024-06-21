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
                <div class="d-flex align-items-center mt-2">
                    <button type="button" class="btn {{$checkLike && $checkLike->is_clicked_like ? 'btn-primary' : 'btn-light'}} like-button"
                            data-discussion-id="{{$discussion->id}}" data-liked="{{ $checkLike && $checkLike->is_clicked_like ? 'true' : 'false' }}">
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
                        <p>{{ $answer->message }}</p>
                        <div class="d-flex justify-content-between" style="font-size: 13px;">
                            <small class="text-muted">{{ $answer->like }} Likes</small>
                            <button class="btn btn-link p-0" type="button" data-bs-toggle="collapse" data-bs-target="#replyForm-{{$answer->id}}" aria-expanded="false" aria-controls="replyForm-{{$answer->id}}">
                                Reply
                            </button>
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
                            <div class="result-comment p-3">
                                @foreach($answer->replies as $reply)
                                    <div class="border-bottom mb-3 pb-2">
                                        <p>{{ $reply->message }}</p>
                                        <div class="d-flex justify-content-between" style="font-size: 13px;">
                                            <small class="text-muted">{{ $reply->username ?? 'Anonymous' }} - {{ $reply->created_at->diffForHumans() }}</small>
                                            <button class="btn btn-link p-0" type="button" data-bs-toggle="collapse" data-bs-target="#replyForm-comment-{{$answer->id}}" aria-expanded="false" aria-controls="replyForm-{{$answer->id}}">
                                                Reply
                                            </button>
                                        </div>
                                        <div class="collapse mt-2" id="replyForm-comment-{{$answer->id}}">
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
                <form action="{{route('user.discussions.saveAnswer')}}" method="POST">
                    @csrf
                    <input type="hidden" name="discussion_id" value="{{ $discussion->id }}">
                    <div class="form-group mb-3">
                        <label for="message" class="mb-2">Your Answer</label>
                        <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Post Answer</button>
                </form>
            </div>
        </div>
    </div>
</main>

@endsection

<script>
document.addEventListener('DOMContentLoaded', function () {
    const likeButtons = document.querySelectorAll('.like-button');
    likeButtons.forEach(button => {
        button.addEventListener('click', function () {
            const isLiked = this.getAttribute('data-liked') === 'true';
            const discussionId = this.getAttribute('data-discussion-id');

            axios.post(`/like/${discussionId}`, {
                    liked: !isLiked
                })
                .then(response => {
                    // Update jumlah like di UI
                    document.getElementById('like-count-' + discussionId).textContent = response.data.likes;

                    // Toggle class btn-light dan btn-primary
                    if (isLiked) {
                        button.classList.remove('btn-primary');
                        button.classList.add('btn-light');
                    } else {
                        button.classList.remove('btn-light');
                        button.classList.add('btn-primary');
                    }

                    // Tandai status liked/unliked
                    button.setAttribute('data-liked', !isLiked ? 'true' : 'false');
                })
                .catch(error => {
                    console.error('Error toggling like:', error);
                });
        });
    });
});
</script>