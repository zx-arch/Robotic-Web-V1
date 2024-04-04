<section id="tutorial" class="contact">
    <div class="container" data-aos="fade-up">

        <div class="section-title">
            <h2>Tutorial</h2>
        </div>

        <div class="row">
            @foreach ($tutorials as $tutor)
                <div class="col-lg-3 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="300">
                <div class="contact-item">
                    <img src="{{$tutor->thumbnail}}" onclick="window.location.href=`{{$tutor->url}}`" class="img-fluid btn-watch-video" alt="Tutorial 3">
                    <a href="{{$tutor->url}}" class="glightbox btn-watch-video">{{$tutor->video_name}}</a>
                </div>
            </div>
            @endforeach
            

        </div>
    </div>
</section>
