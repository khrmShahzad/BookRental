<!-- ======= Header ======= -->
<header id="header" class="header d-flex align-items-center">

    <div class="container-fluid container-xl d-flex align-items-center justify-content-between">
        <a href="/" class="logo d-flex align-items-center">
            <!-- Uncomment the line below if you also wish to use an image logo -->
            <!-- <img src="assets/img/logo.png" alt=""> -->
            <h1>Rental Books<span>.</span></h1>
        </a>
        <nav id="navbar" class="navbar">
            <ul>
                <li><a href="/">Home</a></li>
{{--                <li><a href="#about">About</a></li>--}}
{{--                <li><a href="#services">Services</a></li>--}}
                <li><a href="/#books">Books</a></li>
{{--                <li><a href="#team">Team</a></li>--}}
{{--                <li><a href="#contact">Contact</a></li>--}}

                @if (Auth::User())
{{--                    <li><a href="/dashboard">Dashboard</a></li>--}}
                    <li><a href="/profile">Profile</a></li>
                    <li><a href="/logout">Logout</a></li>
                @else
                    <li><a href="/login">Login</a></li>
                    <li><a href="/register">Register</a></li>
                @endif
            </ul>
        </nav><!-- .navbar -->

        <i class="mobile-nav-toggle mobile-nav-show bi bi-list"></i>
        <i class="mobile-nav-toggle mobile-nav-hide d-none bi bi-x"></i>

    </div>
</header><!-- End Header -->
<!-- End Header -->
