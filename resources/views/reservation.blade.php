<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>KOPPEE - Coffee Shop HTML Template</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Free Website Template" name="keywords">
    <meta content="Free Website Template" name="description">

    <!-- Favicon -->
    <link rel="stylesheet" href="{{ asset('css/your-style.css') }}">
    <link rel="stylesheet" href="{{ asset('bootstrap/bootstrap.min.css') }}">
    <script src="{{ asset('js/your-scripts.js') }}"></script>    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@200;400&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap JS (modal requires this) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/style.min.css" rel="stylesheet">
</head>

<body>
    <!-- Navbar Start -->
    <div class="container-fluid p-0 nav-bar">
        <nav class="navbar navbar-expand-lg bg-none navbar-dark py-3">
            <a href="index.blade.php" class="navbar-brand px-lg-4 m-0">
                <h1 class="m-0 display-4 text-uppercase text-white">KOPPEE</h1>
            </a>

            <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-between" id="navbarCollapse">
                <div class="navbar-nav ml-auto p-4">
                    <a href="{{route('index')}}" class="nav-item nav-link active">Home</a>
                    @if(Auth::check() && Auth::user()->is_admin)
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-light">
                            Admin
                        </a>
                    @endif
                    <a href="{{route('service')}}" class="nav-item nav-link">Service</a>
                    <a href="{{route('menu.index')}}" class="nav-item nav-link">Menu</a>
                    @if(Auth::check())
                        <a href="{{route('reserve')}}" class="nav-item nav-link active">Order</a>
                    @endif

                    @if(Auth::check())
                        <span class="nav-item nav-link text-white">
                        <i class="fas fa-user"></i> {{ Auth::user()->name }}
                    </span>

                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm ms-2">Logout</button>
                        </form>
                    @else
                        <button onclick="openModal()" class="btn btn-primary btn-sm ms-2">
                            <i class="fas fa-sign-in-alt"></i> Login
                        </button>
                    @endif
                </div>
            </div>
        </nav>
    </div>
    <!-- Login Modal (Place this OUTSIDE nav completely!) -->
    <div id="popup"  style="display:none;      position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background-color: rgba(0, 0, 0, 0.6); /* semi-transparent */ z-index: 1050; align-items: center; justify-content: center;">
        <div class=" p-4 bg-white rounded shadow" style="background-color: #fff;  padding: 30px;  border-radius: 10px; max-width: 400px; width: 90%; z-index: 1051; box-shadow: 0 0 10px rgba(0, 0, 0, 0.25);">
            <span class="close-btn float-end fs-3" onclick="closeModal()" style="cursor:pointer">&times;</span>
            <h4 class="mb-3">Login</h4>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-3">
                    <label for="phone" class="form-label">Phone Number</label>
                    <input type="text" name="phone" id="phone" class="form-control" required>
                    @error('phone')<small class="text-danger">{{ $message }}</small>@enderror
                </div>

                <button type="submit" class="btn btn-primary w-100">Login</button>
            </form>

            @if ($errors->any())
                <ul class="mt-3">
                    @foreach ($errors->all() as $error)
                        <li class="text-danger">{{ $error }}</li>
                    @endforeach
                </ul>
            @endif

            <div class="mt-3">
                <a href="{{ route('register.form') }}" class="text-primary">Don't have an account? Register</a>
            </div>
        </div>
    </div>
    <!-- Navbar End -->


    <!-- Page Header Start -->
    <div class="container-fluid page-header mb-5 position-relative overlay-bottom">
        <div class="d-flex flex-column align-items-center justify-content-center pt-0 pt-lg-5" style="min-height: 400px">
            <h1 class="display-4 mb-3 mt-0 mt-lg-5 text-white text-uppercase">Reservation</h1>
            <div class="d-inline-flex mb-lg-5">
                <p class="m-0 text-white"><a class="text-white" href="">Home</a></p>
                <p class="m-0 text-white px-2">/</p>
                <p class="m-0 text-white">Reservation</p>
            </div>
        </div>
    </div>
    <!-- Page Header End -->


    <!-- Reservation Start -->
    <div class="container my-5">
        <h2 class="mb-4">Your Order</h2>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @elseif(session('warning'))
            <div class="alert alert-warning">{{ session('warning') }}</div>
        @endif

        {{-- Table selection --}}
        @if(!$order || !$order->table)
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Select a Table Before Ordering</h5>
                    <form method="POST" action="{{ route('reservation.chooseTable') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                @if(!$order || is_null($order->table_id))
                                    <form method="POST" action="{{ route('reservation.selectTable') }}">
                                        @csrf
                                        <label for="table_id">Select a table:</label>
                                        <select name="table_id" id="table_id" class="form-select mb-3" required>
                                            <option value="">-- Choose a table --</option>
                                            @foreach($tables as $table)
                                                <option value="{{ $table->id }}">{{ $table->title }} (Seats: {{ $table->seats }})</option>
                                            @endforeach
                                        </select>
                                        <button type="submit" class="btn btn-primary">Confirm Table</button>
                                    </form>
                                @else
                                    <p>You have already selected Table: {{ $order->table->title }}</p>
                                @endif

                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @else
            <p><strong>Table:</strong> {{ $order->table->title }} (Seats: {{ $order->table->seats }})</p>
        @endif

        {{-- Order summary --}}
        @if($order && $order->orderItems->count() > 0)
            <table class="table table-bordered align-middle text-center">
                <thead class="table-dark">
                <tr>
                    <th>Title</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @php $total = 0; @endphp
                @foreach ($order->orderItems as $item)
                    @php
                        $subtotal = $item->price * $item->quantity;
                        $total += $subtotal;
                    @endphp
                    <tr>
                        <td>{{ $item->product->title ?? 'Unknown Product' }}</td>
                        <td>${{ number_format($item->price, 2) }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>${{ number_format($subtotal, 2) }}</td>
                        <td>
                            <form method="POST" action="{{ route('reservation.removeItem', $item->id) }}" onsubmit="return confirm('Remove this item?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">Remove</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <div class="d-flex justify-content-between align-items-center mt-4">
                <h4>Total: <span class="text-success">${{ number_format($total, 2) }}</span></h4>

                <div class="d-flex gap-2">
                    @if($order->table)
                        <form method="POST" action="{{ route('reservation.submit') }}">
                            @csrf
                            <button type="submit" class="btn btn-success">Place Order</button>
                        </form>
                    @endif

                    <form method="POST" action="{{ route('reservation.clear') }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-warning">Clear Order</button>
                    </form>
                </div>
            </div>
        @else
            <p class="text-muted">Your order is currently empty.</p>
        @endif
    </div>

    <!-- Reservation End -->


    <!-- Footer Start -->
    <div class="container-fluid footer text-white mt-5 pt-5 px-0 position-relative overlay-top">
        <div class="row mx-0 pt-5 px-sm-3 px-lg-5 mt-4">
            <div class="col-lg-3 col-md-6 mb-5">
                <h4 class="text-white text-uppercase mb-4" style="letter-spacing: 3px;">Get In Touch</h4>
                <p><i class="fa fa-map-marker-alt mr-2"></i>123 Street, New York, USA</p>
                <p><i class="fa fa-phone-alt mr-2"></i>+012 345 67890</p>
                <p class="m-0"><i class="fa fa-envelope mr-2"></i>info@example.com</p>
            </div>
            <div class="col-lg-3 col-md-6 mb-5">
                <h4 class="text-white text-uppercase mb-4" style="letter-spacing: 3px;">Follow Us</h4>
                <p>Amet elitr vero magna sed ipsum sit kasd sea elitr lorem rebum</p>
                <div class="d-flex justify-content-start">
                    <a class="btn btn-lg btn-outline-light btn-lg-square mr-2" href="#"><i class="fab fa-twitter"></i></a>
                    <a class="btn btn-lg btn-outline-light btn-lg-square mr-2" href="#"><i class="fab fa-facebook-f"></i></a>
                    <a class="btn btn-lg btn-outline-light btn-lg-square mr-2" href="#"><i class="fab fa-linkedin-in"></i></a>
                    <a class="btn btn-lg btn-outline-light btn-lg-square" href="#"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-5">
                <h4 class="text-white text-uppercase mb-4" style="letter-spacing: 3px;">Open Hours</h4>
                <div>
                    <h6 class="text-white text-uppercase">Monday - Friday</h6>
                    <p>8.00 AM - 8.00 PM</p>
                    <h6 class="text-white text-uppercase">Saturday - Sunday</h6>
                    <p>2.00 PM - 8.00 PM</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-5">
                <h4 class="text-white text-uppercase mb-4" style="letter-spacing: 3px;">Newsletter</h4>
                <p>Amet elitr vero magna sed ipsum sit kasd sea elitr lorem rebum</p>
                <div class="w-100">
                    <div class="input-group">
                        <input type="text" class="form-control border-light" style="padding: 25px;" placeholder="Your Email">
                        <div class="input-group-append">
                            <button class="btn btn-primary font-weight-bold px-3">Sign Up</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid text-center text-white border-top mt-4 py-4 px-sm-3 px-md-5" style="border-color: rgba(256, 256, 256, .1) !important;">
            <p class="mb-2 text-white">Copyright &copy; <a class="font-weight-bold" href="#">Domain</a>. All Rights Reserved.</a></p>
            <p class="m-0 text-white">Designed by <a class="font-weight-bold" href="https://htmlcodex.com">HTML Codex</a> Distributed by <a href="https://themewagon.com" target="_blank">ThemeWagon</a></a></p>
        </div>
    </div>
    <!-- Footer End -->


    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="fa fa-angle-double-up"></i></a>


    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/tempusdominus/js/moment.min.js"></script>
    <script src="lib/tempusdominus/js/moment-timezone.min.js"></script>
    <script src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>

    <!-- Contact Javascript File -->
    <script src="mail/jqBootstrapValidation.min.js"></script>
    <script src="mail/contact.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
    <script>
        function openModal() {
            document.getElementById('popup').style.display = 'flex';
        }

        function closeModal() {
            document.getElementById('popup').style.display = 'none';
        }

        // Optional: close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('popup');
            if (event.target === modal) {
                closeModal();
            }
        }
    </script>
</body>

</html>
