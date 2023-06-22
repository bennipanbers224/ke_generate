<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Verify Signing File</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="{{asset('img/favicon.ico')}}" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500&family=Roboto:wght@500;700;900&display=swap" rel="stylesheet"> 

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="{{asset('lib/animate/animate.min.css')}}" rel="stylesheet">
    <link href="{{asset('lib/owlcarousel/assets/owl.carousel.min.css')}}" rel="stylesheet">
    <link href="{{asset('lib/lightbox/css/lightbox.min.css')}}" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="{{asset('css/style.css')}}" rel="stylesheet">
    <style>
        .footer {
        position: fixed;
        left: 0;
        bottom: 0;
        width: 100%;
        background-color: grey;
        color: white;
        text-align: center;
        }
    </style>
</head>

<body>
    <!-- Spinner Start -->
    <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-auto bg-light sticky-top" style = "width:30%;">
                <div class="d-flex flex-sm-column flex-row flex-nowrap bg-light align-items-center sticky-top">
                <br>
                <div class="profile-userpic">
					<img style="width : 100px; height:100px;" src="{{asset('images/user.png')}}" class="img-responsive" alt="">
				</div>
				<div class="profile-usertitle">
					<div class="profile-usertitle-name" style="text-align: center;">
                    {{auth()->user()->name}}
					</div>
					<div class="profile-usertitle-job" style="text-align: center;">
                    {{auth()->user()->status}}
					</div>
				</div>
				<div class="profile-userbuttons">
					<a href="/logout"><button type="button" class="btn btn-danger btn-sm">Logout</button></a>
				</div>
                    @if(auth()->user()->status == 'Admin')
                        <ul class="nav nav-pills nav-flush flex-sm-column flex-row flex-nowrap mb-auto mx-auto text-center align-items-center">
                            <li class="nav-item">
                                <a href="/" class="nav-link py-5 px-5" title="" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Home">
                                    <div class="card" style="width: 200px; height:150px;">
                                        <img src="{{asset('images/sign.png')}}" class="card-img-top" alt="..." style="width: 200px; height:150px;">
                                        <div class="card-body">
                                            <p class="card-text">Generate File Signature</p>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <br><br>
                            <li>
                                <a href="/toVerify" class="nav-link py-3 px-2" title="" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Dashboard">
                                    <div class="card" style="width: 200px; height:150px;">
                                        <img src="{{asset('images/documents.png')}}" class="card-img-top" alt="..." style="width: 200px; height:150px;">
                                        <div class="card-body">
                                            <p class="card-text">Verification File</p>
                                        </div>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    @else
                        <ul class="nav nav-pills nav-flush flex-sm-column flex-row flex-nowrap mb-auto mx-auto text-center align-items-center">
                            
                            <li class="nav-item">
                                <a href="/" class="nav-link py-5 px-5" title="" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Home">
                                    <div class="card" style="width: 200px; height:150px;">
                                        <img src="{{asset('images/sign.png')}}" class="card-img-top" alt="..." style="width: 200px; height:150px;">
                                        <div class="card-body">
                                            <p class="card-text">File For Signing</p>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <br><br>
                            <li>
                                <a href="/toVerify" class="nav-link py-3 px-2" title="" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Dashboard">
                                    <div class="card" style="width: 200px; height:150px;">
                                        <img src="{{asset('images/documents.png')}}" class="card-img-top" alt="..." style="width: 200px; height:150px;">
                                        <div class="card-body">
                                            <p class="card-text">Verification File</p>
                                        </div>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    @endif
                </div>
            </div>
            <div class="col-sm p-3 min-vh-100">
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Footer Start -->
    
    <!-- Footer End -->

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{asset('lib/wow/wow.min.js')}}"></script>
    <script src="{{asset('lib/easing/easing.min.js')}}"></script>
    <script src="{{asset('lib/waypoints/waypoints.min.js')}}"></script>
    <script src="{{asset('lib/counterup/counterup.min.js')}}"></script>
    <script src="{{asset('lib/owlcarousel/owl.carousel.min.js')}}"></script>
    <script src="{{asset('lib/isotope/isotope.pkgd.min.js')}}"></script>
    <script src="{{asset('lib/lightbox/js/lightbox.min.js')}}"></script>

    <!-- Template Javascript -->
    <script src="{{asset('js/main.js')}}"></script>
</body>

</html>