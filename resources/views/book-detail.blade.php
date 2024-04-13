@extends('layouts.main')

@section('title', 'Book Details')

@section('content')

<main id="main">

    <!-- ======= Breadcrumbs ======= -->
    <div class="breadcrumbs">
        <div class="page-header d-flex align-items-center" style="background-image: url('');">
            <div class="container position-relative">
                <div class="row d-flex justify-content-center">
                    <div class="col-lg-6 text-center">
                        <h2>Book Details</h2>
                        <p>Odio et unde deleniti. Deserunt numquam exercitationem. Officiis quo odio sint voluptas consequatur ut a odio voluptatem. Sit dolorum debitis veritatis natus dolores. Quasi ratione sint. Sit quaerat ipsum dolorem.</p>
                    </div>
                </div>
            </div>
        </div>
        <nav>
            <div class="container">
                <ol>
                    <li><a href="/">Home</a></li>
                    <li>Book Details</li>
                </ol>
            </div>
        </nav>
    </div><!-- End Breadcrumbs -->

    <!-- ======= Portfolio Details Section ======= -->
    <section id="portfolio-details" class="portfolio-details">
        <div class="container" data-aos="fade-up">

            <div class="position-relative h-100">
                <div class="slides-1 portfolio-details-slider swiper">
                    <div class="swiper-wrapper align-items-center">

                        <div class="swiper-slide">
                            <img src="{{ $book->cover != null ? asset('cover/' . $book->cover) : asset('cover/cover-default1.png') }}" alt="">
                        </div>

                    </div>
                    <div class="swiper-pagination"></div>
                </div>
                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>

            </div>

            <div class="row justify-content-between gy-4 mt-4">

                <div class="col-lg-12">
                    <div class="portfolio-description">
                        <h2>Title - {{$book->title}}</h2>
                        <p>Price - {{$book->charges}}</p>
                        <p>Security - {{$book->security}}</p>
                        <p>Available Copies - {{ $book->available_copies }}</p>

                        <p>Ratings</p>
                        @for($i=1;$i<6;$i++)
                            @if($book->overall_rating == 0)
                                <i class="bi bi-star" title="Login to rate your books" style="cursor:pointer"></i>
                            @elseif($i <= $book->overall_rating)
                                <i class="bi bi-star" title="Login to rate your books" style="cursor:pointer; color: greenyellow" ></i>
                            @else
                                <i class="bi bi-star" title="Login to rate your books" style="cursor:pointer"></i>
                            @endif
                        @endfor

                        @if (Auth::User() && Auth::User()->role_id === 3 && $book->status == 'in stock')
                            <div class="col-12 col-md-auto">
                                <a href="borrow-req/{{ $book->id }}" class="btn btn-primary me-4">Borrow Request</a>
                            </div>
                        @else
                            <div class="col-12 col-md-auto">
                                <a href="/login" class="btn btn-primary me-4">Login to Borrow</a>
                            </div>
                        @endif

                        @if(!empty($book['comments']))
                            <br>
                            Comments...
                            <br>
                            @foreach($book['comments'] as $comment)
                                {{$comment['comments']}}
                            @endforeach
                        @endif

                    </div>

                </div>

            </div>

        </div>
    </section><!-- End Portfolio Details Section -->

</main><!-- End #main -->

@endsection
