<!-- Start Navigation -->
<header class="header-section">
    <nav class="navbar navbar-default bootsnav">
        <!-- Start Top Search -->
        <div class="top-search">
            <div class="container">
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-search"></i></span>
                    <input type="text" class="form-control" id="header-search" placeholder="Search">
                    <span class="input-group-addon close-search"><i class="fa fa-times"></i></span>
                </div>
                <div id="header-suggestions-container" class="list-group"></div> <!-- Suggestions container -->
            </div>
        </div>
        <!-- End Top Search -->

        <div class="container">
            <!-- Start Atribute Navigation -->
            <div class="attr-nav">
                <ul>
                    <li class="search"><a href="#"><i class="fa fa-search"></i></a></li>
                </ul>
            </div>
            <!-- End Atribute Navigation -->

            <!-- Start Header Navigation -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-menu">
                    <i class="fa fa-bars"></i>
                </button>
                <a class="navbar-brand" href="{{ route('home.index') }}">
                    <img src="{{ asset('assets/Website/images/svgviewer-output.svg') }}" class="logo" alt="">
                </a>
            </div>
            <!-- End Header Navigation -->

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="navbar-menu">
                <ul class="nav navbar-nav navbar-right" data-in="fadeInDown" data-out="fadeOutUp">
                    <li id="home-link"><a href="{{ route('home.index') }}">Home</a></li>
                    <li id="about-link"><a href="{{ route('about.index') }}">About Team</a></li>
                    <li id="resources-link" class="dropdown megamenu-fw">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Resources</a>
                        <ul class="dropdown-menu megamenu-content" role="menu">
                            <li>
                                <div class="row">
                                    <div class="col-menu col-md-3">
                                        <h6 class="title">Mathematics</h6>
                                        <div class="content">
                                            <ul class="menu-col">
                                                @foreach ($branches->where('department_id', 1) as $branch)
                                                    <li><a href="{{ route('resources.filter', ['branch' => $branch->id]) }}">{{ $branch->name }}</a></li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-menu col-md-3">
                                        <h6 class="title">Physics</h6>
                                        <div class="content">
                                            <ul class="menu-col">
                                                @foreach ($branches->where('department_id', 2) as $branch)
                                                    <li><a href="{{ route('resources.filter', ['branch' => $branch->id]) }}">{{ $branch->name }}</a></li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-menu col-md-3">
                                        <h6 class="title">Chemistry</h6>
                                        <div class="content">
                                            <ul class="menu-col">
                                                @foreach ($branches->where('department_id', 3) as $branch)
                                                    <li><a href="{{ route('resources.filter', ['branch' => $branch->id]) }}">{{ $branch->name }}</a></li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-menu col-md-3">
                                        <h6 class="title">Geology</h6>
                                        <div class="content">
                                            <ul class="menu-col">
                                                @foreach ($branches->where('department_id', 4) as $branch)
                                                    <li><a href="{{ route('resources.filter', ['branch' => $branch->id]) }}">{{ $branch->name }}</a></li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-menu col-md-3">
                                        <h6 class="title">Botany</h6>
                                        <div class="content">
                                            <ul class="menu-col">
                                                @foreach ($branches->where('department_id', 5) as $branch)
                                                    <li><a href="{{ route('resources.filter', ['branch' => $branch->id]) }}">{{ $branch->name }}</a></li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-menu col-md-3">
                                        <h6 class="title">Animals</h6>
                                        <div class="content">
                                            <ul class="menu-col">
                                                @foreach ($branches->where('department_id', 6) as $branch)
                                                    <li><a href="{{ route('resources.filter', ['branch' => $branch->id]) }}">{{ $branch->name }}</a></li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-menu col-md-3">
                                        <h6 class="title">All Resources</h6>
                                        <div class="content">
                                            <ul class="menu-col">
                                                <li><a href="{{ route('resources.index') }}">Public Resources</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Tools</a>
                        <ul class="dropdown-menu">
                            {{-- <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Research</a>
                                <ul class="dropdown-menu">
                                    <li><a href="#">Custom Menu</a></li>
                                    <li><a href="#">Custom Menu</a></li>
                                    <li><a href="#">Custom Menu</a></li>
                                </ul>
                            </li>
                            <li><a href="#">Custom Menu</a></li>
                            <li><a href="#">Custom Menu</a></li>
                            <li><a href="#">Custom Menu</a></li> --}}
                        </ul>
                    </li>
                    <li id="contact-link"><a href="">Contact Us</a></li>
                </ul>
            </div><!-- /.navbar-collapse -->
        </div>
    </nav>
    <!-- End Navigation -->
</header>
