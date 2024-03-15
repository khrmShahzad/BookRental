@extends('layouts.main')

@section('title', 'Login')

@section('content')

    <main id="main">
        <!-- ======= Login Section ======= -->
        <section id="login" class="contact">
            <div class="container" data-aos="fade-up">

                <div class="section-header">
                    <h2>Login</h2>
{{--                    <p>Nulla dolorum nulla nesciunt rerum facere sed ut inventore quam porro nihil id ratione ea sunt quis dolorem dolore earum</p>--}}
                </div>

                <div class="row gx-lg-0 gy-4">

                    <div class="col-lg-4">

                        <div class="info-container d-flex flex-column align-items-center justify-content-center">
                            <div class="info-item d-flex">
                                <i class="bi bi-geo-alt flex-shrink-0"></i>
                                <div>
                                    <h4>Location:</h4>
                                    <p>Hafar Al-Batin, University of hafar al-batin, SWE department.</p>
                                </div>
                            </div><!-- End Info Item -->

                            <div class="info-item d-flex">
                                <i class="bi bi-envelope flex-shrink-0"></i>
                                <div>
                                    <h4>Email:</h4>
                                    <p>Rental.Book.info@gmail.com</p>
                                </div>
                            </div><!-- End Info Item -->

                            {{--<div class="info-item d-flex">
                                <i class="bi bi-phone flex-shrink-0"></i>
                                <div>
                                    <h4>Call:</h4>
                                    <p>+1 5589 55488 55</p>
                                </div>
                            </div><!-- End Info Item -->--}}

                            <div class="info-item d-flex">
                                <i class="bi bi-clock flex-shrink-0"></i>
                                <div>
                                    <h4>Open Hours:</h4>
                                    <p>Sunday-Thursday: 11AM - 23PM</p>
                                </div>
                            </div><!-- End Info Item -->
                        </div>

                    </div>

                    <div class="col-lg-8">
                        <form action="" method="POST" role="form" class="php-email-form">
                            @csrf
                            <div class="form-group mt-3">
                                <input type="text" name="username" class="form-control" id="username" placeholder="Enter username" required>
                            </div>
                            <div class="form-group mt-3">
                                <input type="password" name="password" class="form-control" id="password" placeholder="Enter password" required>
                            </div>
                            <div class="my-3">
                                <div class="loading">Loading</div>
                                <div class="error-message"></div>
                                <div class="sent-message">Your message has been sent. Thank you!</div>
                            </div>
                            <div class="text-center"><button type="submit">Login</button></div>
                            <p class="text-center"> Don't have an account? <a href="/register" class="text-decoration-none mb-3">Sign Up</a></p>
                        </form>
                    </div><!-- End Contact Form -->

                </div>

            </div>
        </section><!-- End Login Section -->

    </main><!-- End #main -->
@endsection
