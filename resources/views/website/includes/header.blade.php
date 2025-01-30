   <!-- Start Navigation -->
   <header class="header-section">
       <nav class="navbar navbar-default bootsnav">
           <!-- Start Top Search -->
           {{-- <div class="top-search">
               <div class="container">
                   <div class="input-group">
                       <span class="input-group-addon"><i class="fa fa-search"></i></span>
                       <input  type="text" class="form-control" placeholder="Search">
                       <span class="input-group-addon close-search"><i class="fa fa-times"></i></span>
                   </div>
               </div>
           </div> --}}

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
                   <a class="navbar-brand" href="{{ route('home.index') }}"><img
                           src="{{ asset('assets/Website/images/svgviewer-output.svg') }}" class="logo" alt=""></a>
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
                               @php
                                   $departments = DB::table('departments')->get();
                                   $branches = DB::table('branches')->get();
                               @endphp
                               <li>
                                   <div class="row">
                                       <div class="col-menu col-md-3">
                                           <h6 class="title">Mathematics</h6>
                                           <div class="content">
                                               <ul class="menu-col">
                                                   @foreach ($branches->where('department_id', 1) as $branch)
                                                       <li><a
                                                               href="{{ route('resources.filter.departmentbranch', [$branch->department_id, $branch->id]) }}">{{ $branch->name }}</a>
                                                       </li>
                                                   @endforeach
                                               </ul>
                                           </div>
                                       </div><!-- end col-3 -->
                                       <div class="col-menu col-md-3">
                                           <h6 class="title">Physics</h6>
                                           <div class="content">
                                               <ul class="menu-col">
                                                    @foreach ($branches->where('department_id', 2) as $branch)
                                                         <li><a
                                                                href="{{ route('resources.filter.departmentbranch', [$branch->department_id, $branch->id]) }}">{{ $branch->name }}</a>
                                                         </li>
                                                    @endforeach
                                                   {{-- <li><a href="#">Physics</a></li>
                                                   <li><a href="#">Physics and Electronics</a></li> --}}
                                               </ul>
                                           </div>
                                       </div><!-- end col-3 -->
                                       <div class="col-menu col-md-3">
                                           <h6 class="title">Chemistry</h6>
                                           <div class="content">
                                               <ul class="menu-col">
                                                    @foreach ($branches->where('department_id', 3) as $branch)
                                                         <li><a
                                                              href="{{ route('resources.filter.departmentbranch', [$branch->department_id, $branch->id]) }}">{{ $branch->name }}</a>
                                                         </li>
                                                    @endforeach
                                                   {{-- <li><a href="#">Chemistry</a></li>
                                                   <li><a href="#">Chemistry and Microbiology</a></li>
                                                   <li><a href="#">Chemistry and Botany</a></li>
                                                   <li><a href="#">Chemistry and Zoology</a></li>
                                                   <li><a href="#">Chemistry and Physics</a></li> --}}
                                               </ul>
                                           </div>
                                       </div>
                                       <div class="col-menu col-md-3">
                                           <h6 class="title">Geology</h6>
                                           <div class="content">
                                               <ul class="menu-col">
                                                    @foreach ($branches->where('department_id', 4) as $branch)
                                                         <li><a
                                                                href="{{ route('resources.filter.departmentbranch', [$branch->department_id, $branch->id]) }}">{{ $branch->name }}</a>
                                                         </li>
                                                    @endforeach
                                                   {{-- <li><a href="#">Geology</a></li>
                                                   <li><a href="#">Geophysics</a></li>
                                                   <li><a href="#">Chemistry and Geology</a></li> --}}
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
                                                         <li><a
                                                              href="{{ route('resources.filter.departmentbranch', [$branch->department_id, $branch->id]) }}">{{ $branch->name }}</a>
                                                         </li>
                                                    @endforeach
                                                   {{-- <li><a href="#">Botany</a></li>
                                                   <li><a href="#">Microbiology</a></li> --}}
                                               </ul>
                                           </div>
                                       </div>
                                       <div class="col-menu col-md-3">
                                           <h6 class="title">Animals</h6>
                                           <div class="content">
                                               <ul class="menu-col">
                                                    @foreach ($branches->where('department_id', 6) as $branch)
                                                         <li><a
                                                              href="{{ route('resources.filter.departmentbranch', [$branch->department_id, $branch->id]) }}">{{ $branch->name }}</a>
                                                         </li>
                                                    @endforeach
                                                   {{-- <li><a href="#">Zoology</a></li>
                                                   <li><a href="#">Entomology</a></li>
                                                   <li><a href="#">Insect Chemistry</a></li> --}}
                                               </ul>
                                           </div>
                                       </div>
                                       <div class="col-menu col-md-3">
                                           <h6 class="title">All Resources</h6>
                                           <div class="content">
                                               <ul class="menu-col">
                                                   <li><a href="{{ route('resources.index') }}">Public Resources</a>
                                                   </li>
                                               </ul>
                                           </div>
                                       </div>
                                       {{-- <div class="col-menu col-md-3">
                                           <h6 class="title">Title Menu Four</h6>
                                           <div class="content">
                                               <ul class="menu-col">
                                                   <li><a href="#">Custom Menu</a></li>
                                                   <li><a href="#">Custom Menu</a></li>
                                                   <li><a href="#">Custom Menu</a></li>
                                                   <li><a href="#">Custom Menu</a></li>
                                                   <li><a href="#">Custom Menu</a></li>
                                                   <li><a href="#">Custom Menu</a></li>
                                               </ul>
                                           </div>
                                       </div><!-- end col-3 --> --}}
                                   </div><!-- end row -->
                               </li>
                           </ul>
                       </li>
                       <li class="dropdown">
                           <a href="#" class="dropdown-toggle" data-toggle="dropdown">Tools</a>
                           <ul class="dropdown-menu">
                               {{-- <li><a href="#">Custom Menu</a></li> --}}
                               {{-- <li><a href="#">Custom Menu</a></li> --}}
                               <li class="dropdown">
                                   <a href="#" class="dropdown-toggle" data-toggle="dropdown">Research</a>
                                   <ul class="dropdown-menu">
                                       <li><a href="#">Custom Menu</a></li>
                                       <li><a href="#">Custom Menu</a></li>
                                       {{-- <li class="dropdown">
                                           <a href="#" class="dropdown-toggle" data-toggle="dropdown">Sub
                                               Menu</a>
                                           <ul class="dropdown-menu">
                                               <li><a href="#">Custom Menu</a></li>
                                               <li><a href="#">Custom Menu</a></li>
                                               <li><a href="#">Custom Menu</a></li>
                                               <li><a href="#">Custom Menu</a></li>
                                           </ul>
                                       </li> --}}
                                       <li><a href="#">Custom Menu</a></li>
                                   </ul>
                               </li>
                               {{-- <li><a href="#">Custom Menu</a></li> --}}
                               <li><a href="#">Custom Menu</a></li>
                               <li><a href="#">Custom Menu</a></li>
                               <li><a href="#">Custom Menu</a></li>
                           </ul>
                       </li>
                       {{-- <li><a href="#">Portfolio</a></li> --}}
                       <li id="contact-link"><a href="">Contact Us</a></li>
                   </ul>
               </div><!-- /.navbar-collapse -->
           </div>

           {{-- <!-- Start Side Menu -->
    <div class="side">
        <a href="#" class="close-side"><i class="fa fa-times"></i></a>
        <div class="widget">
            <h6 class="title">Custom Pages</h6>
            <ul class="link">
                <li><a href="#">About</a></li>
                <li><a href="#">Services</a></li>
                <li><a href="#">Blog</a></li>
                <li><a href="#">Portfolio</a></li>
                <li><a href="#">Contact</a></li>
            </ul>
        </div>
        <div class="widget">
            <h6 class="title">Additional Links</h6>
            <ul class="link">
                <li><a href="#">Retina Homepage</a></li>
                <li><a href="#">New Page Examples</a></li>
                <li><a href="#">Parallax Sections</a></li>
                <li><a href="#">Shortcode Central</a></li>
                <li><a href="#">Ultimate Font Collection</a></li>
            </ul>
        </div>
    </div>
    <!-- End Side Menu --> --}}
       </nav>
       <!-- End Navigation -->

   </header>
