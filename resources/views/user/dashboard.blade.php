<style>
    .custom-card {
        border: none;
        border-radius: 10px;
        color: white;
        transition: transform 0.3s ease, box-shadow 0.3s ease, background 0.3s ease;
        overflow: hidden;
        position: relative;
    }

    .custom-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
    }

    .custom-card .card-title {
        font-size: 1.5rem;
        margin-bottom: 0.5rem;
    }

    .custom-card .card-text {
        font-size: 1rem;
        margin-bottom: 0.5rem;
    }

    .card-img-top {
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
    }

    .highlight-title {
        font-size: 1.5rem;
        font-weight: bold;
        padding: 2px;
        color: #007bff;
        margin-bottom: 20px;
    }

    .carousel-item {
        transition-duration: 6s; /* Ganti nilai ini sesuai kebutuhan Anda */
    }

    .carousel-item > .row {
        display: flex;
        flex-wrap: nowrap;
    }

    .carousel-item > .row > .col-lg-3 {
        flex: 1;
        margin: 5px;
    }

    .custom-card {
        background: white;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }

    .custom-card:hover {
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }

    .card-footer {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .carousel-control-prev, .carousel-control-next {
        display: none; /* Hide the navigation controls */
    }

</style>

@extends('cms_login.index_user')

@section('content')

<main class="content px-3 py-4">
    <div class="container-fluid">
        <div class="box">
            <div class="box-body">
                <div class="row">
                    <div class="col-md-6 col-lg-3">
                        <div class="info-box bg-info">
                            <span class="info-box-icon"><i class='bx bxs-user'></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Total Device Access</span>
                                <span class="info-box-number">0</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="info-box bg-primary">
                            <span class="info-box-icon"><i class='bx bxs-devices'></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Active Devices</span>
                                <span class="info-box-number">10</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="info-box bg-success">
                            <span class="info-box-icon"><i class='bx bxs-bell'></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Notifications</span>
                                <span class="info-box-number">5</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="info-box bg-warning">
                            <span class="info-box-icon"><i class='bx bxs-check-circle'></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Completed Tasks</span>
                                <span class="info-box-number">8</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="p-2 mt-4">

                    <div class="highlight-title">Events Terbaru</div>
                    
                    <div id="eventCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="12000">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <div class="row">
                                    <div class="col-lg-3 col-md-6 mb-4">
                                        <div class="card custom-card my-bg-1">
                                            <div class="card-body">
                                                <h5 class="card-title">Event 1</h5>
                                                <p class="card-text"><strong>Date:</strong> June 1, 2024</p>
                                                <p class="card-text"><strong>Place:</strong> Central Park</p>
                                                <p class="card-text">Join us for a day of fun and activities.</p>
                                            </div>
                                            <div class="card-footer">
                                                <button class="btn btn-primary my-bg-1-btn w-50">Register</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6 mb-4">
                                        <div class="card custom-card my-bg-2">
                                            <div class="card-body">
                                                <h5 class="card-title">Event 2</h5>
                                                <p class="card-text"><strong>Date:</strong> June 5, 2024</p>
                                                <p class="card-text"><strong>Place:</strong> Downtown Hall</p>
                                                <p class="card-text">Don't miss this special occasion with lots of learning opportunities.</p>
                                            </div>
                                            <div class="card-footer">
                                                <button class="btn btn-primary my-bg-2-btn w-50">Register</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6 mb-4">
                                        <div class="card custom-card my-bg-3">
                                            <div class="card-body">
                                                <h5 class="card-title">Event 3</h5>
                                                <p class="card-text"><strong>Date:</strong> June 10, 2024</p>
                                                <p class="card-text"><strong>Place:</strong> City Square</p>
                                                <p class="card-text">A community gathering you don't want to miss.</p>
                                            </div>
                                            <div class="card-footer">
                                                <button class="btn btn-primary my-bg-3-btn w-50">Register</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6 mb-4">
                                        <div class="card custom-card my-bg-4">
                                            <div class="card-body">
                                                <h5 class="card-title">Event 4</h5>
                                                <p class="card-text"><strong>Date:</strong> June 15, 2024</p>
                                                <p class="card-text"><strong>Place:</strong> Riverbank</p>
                                                <p class="card-text">Enjoy a relaxing day by the river.</p>
                                            </div>
                                            <div class="card-footer">
                                                <button class="btn btn-primary my-bg-4-btn w-50">Register</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="carousel-item">
                                <div class="row">
                                    <div class="col-lg-3 col-md-6 mb-4">
                                        <div class="card custom-card">
                                            <div class="card-body">
                                                <h5 class="card-title">Event 5</h5>
                                                <p class="card-text"><strong>Date:</strong> June 20, 2024</p>
                                                <p class="card-text"><strong>Place:</strong> Beachside</p>
                                                <p class="card-text">A beachside event with lots of fun activities.</p>
                                            </div>
                                            <div class="card-footer">
                                                <button class="btn btn-primary w-50">Register</button>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Repeating the first few cards to make the carousel continuous -->
                                    <div class="col-lg-3 col-md-6 mb-4">
                                        <div class="card custom-card">
                                            <div class="card-body">
                                                <h5 class="card-title">Event 1</h5>
                                                <p class="card-text"><strong>Date:</strong> June 1, 2024</p>
                                                <p class="card-text"><strong>Place:</strong> Central Park</p>
                                                <p class="card-text">Join us for a day of fun and activities.</p>
                                            </div>
                                            <div class="card-footer">
                                                <button class="btn btn-primary w-50">Register</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6 mb-4">
                                        <div class="card custom-card">
                                            <div class="card-body">
                                                <h5 class="card-title">Event 2</h5>
                                                <p class="card-text"><strong>Date:</strong> June 5, 2024</p>
                                                <p class="card-text"><strong>Place:</strong> Downtown Hall</p>
                                                <p class="card-text">Don't miss this special occasion with lots of learning opportunities.</p>
                                            </div>
                                            <div class="card-footer">
                                                <button class="btn btn-primary w-50">Register</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6 mb-4">
                                        <div class="card custom-card">
                                            <div class="card-body">
                                                <h5 class="card-title">Event 3</h5>
                                                <p class="card-text"><strong>Date:</strong> June 10, 2024</p>
                                                <p class="card-text"><strong>Place:</strong> City Square</p>
                                                <p class="card-text">A community gathering you don't want to miss.</p>
                                            </div>
                                            <div class="card-footer">
                                                <button class="btn btn-primary w-50">Register</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Additional carousel items can be added here -->
                        </div>
                    </div>
                    
                    <div class="highlight-title mt-2 p-2">My Events</div>

                    <div class="card">
                        <div class="card-body p-0" style="overflow-x: auto;">

                            <div id="w0" class="gridview table-responsive">
                                <table class="table text-nowrap table-striped table-bordered mb-0">
                                    <thead>
                                        <tr>
                                            <td>#</td>
                                            <td>Code Event</td>
                                            <td>Nama Event</td>
                                            <td>Tanggal Event</td>
                                            <td>Location</td>
                                            <td></td>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                </div>
                
            </div>
        </div>
    </div>
</main>

@endsection