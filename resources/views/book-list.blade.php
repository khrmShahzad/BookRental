{{--@extends('layouts.mainLayout')--}}
@extends('layouts.main')

@section('title', 'Book List')

@section('content')

    @include('hero')

    <main id="main">

        <!-- ======= Books Section ======= -->
        <section id="books" class="portfolio sections-bg">
            <div class="container" data-aos="fade-up">

                <div class="section-header">
                    <h2>Books</h2>
                    <p>Great Platform to have a variety of knowledgeable books</p>
                </div>

                <div class="portfolio-isotope" data-portfolio-filter="*" data-portfolio-layout="masonry" data-portfolio-sort="original-order" data-aos="fade-up" data-aos-delay="100">

                    <div>
                        <ul class="portfolio-flters">
                            <li data-filter="*" class="filter-active">All</li>

                            @foreach ($categories as $category)
                                <li data-filter=".filter-{{ $category->id }}">{{ $category->name }}</li>
                            @endforeach
                        </ul><!-- End Portfolio Filters -->
                    </div>

                    <div class="mb-4"><input type="text" name="books" id="searchInput" class="form-control" placeholder="Type To Search Book..."/></div>

                    <div class="row gy-4 portfolio-container">

                        @foreach ($books as $item)

                            <div class="col-xl-4 col-md-6 portfolio-item filter-{{$item->category_id}}">
                                <div class="portfolio-wrap">
                                    <a href="{{ $item->cover != null ? asset('cover/' . $item->cover) : asset('cover/cover-default1.png') }}" data-gallery="portfolio-gallery-app" class="glightbox"><img src="{{ $item->cover != null ? asset('cover/' . $item->cover) : asset('cover/cover-default1.png') }}" class="img-fluid" alt=""></a>
                                    <div class="portfolio-info">
                                        <h4><a href="book-detail/{{ $item->id }}" title="More Details">Book Title - {{ $item->title }}</a></h4>
                                        <p>Book Code - {{ $item->book_code }}</p>
                                        <p>Author Name - {{ $item->author }}</p>
                                        <p>Book Price - {{ $item->charges }}</p>
                                        <p>Status - {{ $item->status }}</p>
                                        <p>Security - {{ $item->security }}</p>
                                        <p style="display: inline">Ratings</p>
                                        @for($i=1;$i<6;$i++)
                                            @if($item->overall_rating == 0)
                                                <i class="bi bi-star" title="Login to rate your books" style="cursor:pointer"></i>
                                            @elseif($i <= $item->overall_rating)
                                                <i class="bi bi-star" title="Login to rate your books" style="cursor:pointer; color: greenyellow" ></i>
                                            @else
                                                <i class="bi bi-star" title="Login to rate your books" style="cursor:pointer"></i>
                                            @endif
                                        @endfor
                                        @if (Auth::User() && Auth::User()->role_id === 3 && $item->available_copies > 0)
                                            <div class="col-12 col-md-auto">
                                                <a href="borrow-req/{{ $item->id }}" class="btn btn-primary me-4">Borrow Request</a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div><!-- End Book Item -->

                        @endforeach

                    </div><!-- End Portfolio Container -->

                </div>

            </div>
        </section><!-- End Portfolio Section -->

        @if($recent_books)
        <!-- ======= Recent Blog Posts Section ======= -->
        <section id="recent-posts" class="recent-posts sections-bg">
            <div class="container" data-aos="fade-up">

                <div class="section-header">
                    <h2>Recently Added Books</h2>
                    <p>Recently added books to borrow</p>
                </div>

                <div class="row gy-4">

                    @foreach($recent_books as $recent)
                    <div class="col-xl-4 col-md-6">
                        <article>

                            <div class="post-img">
                                <img src="{{ $recent->cover != null ? asset('cover/' . $recent->cover) : asset('cover/cover-default1.png') }}" alt="" class="img-fluid">
                            </div>

                            <p class="post-category">{{ $recent->title }}</p>

                            <h2 class="title">
                                <a href="book-detail/{{ $recent->id }}">Click to see details</a>
                            </h2>

                            <div class="d-flex align-items-center">
                                <img src="{{ $recent->cover != null ? asset('cover/' . $recent->cover) : asset('cover/cover-default.png') }}" alt="" class="img-fluid post-author-img flex-shrink-0">
                                <div class="post-meta">
                                    <p class="post-author">Price - {{ $recent->charges }}</p>
                                    <p class="post-author">Security - {{ $recent->security }}</p>
{{--                                    <p class="post-date">Status - {{ $recent->status }}</p>--}}
                                    <p class="post-date">Available Copies - {{ $recent->available_copies }}</p>
                                </div>
                            </div>

                        </article>
                    </div><!-- End post list item -->
                    @endforeach

                </div><!-- End recent posts list -->

            </div>
        </section><!-- End Recent Blog Posts Section -->
        @endif

    </main><!-- End #main -->

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#searchInput').on('input', function () {
                var searchValue = $(this).val().toLowerCase();
                $('.portfolio-item').each(function () {
                    var bookTitle = $(this).find('h4 a').text().toLowerCase();
                    var authorName = $(this).find('p:contains("Author Name")').text().toLowerCase();
                    var bookCode = $(this).find('p:contains("Book Code")').text().toLowerCase();
                    if (bookTitle.includes(searchValue) || authorName.includes(searchValue) || bookCode.includes(searchValue)) {
                        $(this).show();
                        $(this).css('position','');
                    } else {
                        $(this).hide();
                    }
                });
            });
        });
    </script>

@endsection
