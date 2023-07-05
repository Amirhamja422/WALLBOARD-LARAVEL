<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!--favicon-->
    <link rel="icon" href="{{ URL::asset('images/favicon-32x32.png') }}" type="image/png" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <title>iHelpBD - Login</title>

    <link href="{{ URL::asset('css/app.css') }}" rel="stylesheet">

</head>

<body class="bg-login" style="background-image: url({{ URL::asset('images/bg-login-img.jpg') }})">
    <!--wrapper-->
    <div class="wrapper">
        <div class="section-authentication-signin d-flex align-items-center justify-content-center my-5 my-lg-0">
            <div class="container-fluid">
                <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3">
                    <div class="col mx-auto">
                        <div class="text-center" style="margin-top: -10em">
                            <img src="{{ URL::asset('images/ihelp.png') }}" width="180" alt="" />
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <div class="border p-4 rounded">
                                    <div class="text-center text-danger" style="font-weight: 500;">
                                        @error('message')
                                            <p>{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="login-separater text-center mb-4" style="margin-top: -18px;"><span>SIGN
                                            IN WITH EMAIL</span>
                                        <hr />
                                    </div>

                                    <div class="form-body">
                                        <form class="row g-3" action="{{ route('auth.authenticate') }}" method="POST">
                                            @csrf
                                            <div class="col-12">
                                                <label for="inputEmailAddress" class="form-label">Email
                                                    Address</label>
                                                @error('email')
                                                    <span class="text-danger error_txt">{{ $message }}</span>
                                                @enderror
                                                <input type="email"
                                                    class="form-control {{ $errors->has('email') ? 'error_box' : '' }}"
                                                    id="inputEmailAddress" name="email" placeholder="Email Address"
                                                    required>
                                            </div>
                                            <div class="col-12">
                                                <label for="inputChoosePassword" class="form-label">Enter
                                                    Password</label>
                                                @error('password')
                                                    <span class="text-danger error_txt">{{ $message }}</span>
                                                @enderror
                                                <div class="input-group" id="show_hide_password">
                                                    <input type="password"
                                                        class="form-control border-end-0 {{ $errors->has('password') ? 'error_box' : '' }}"
                                                        id="inputChoosePassword" name="password"
                                                        placeholder="Enter Password" required><a href="javascript:;"
                                                        class="input-group-text bg-transparent"><i
                                                            class="bi bi-eye-slash"></i></a>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox"
                                                        id="flexSwitchCheckChecked" checked>
                                                    <label class="form-check-label"
                                                        for="flexSwitchCheckChecked">Remember Me</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6 text-end"><a
                                                    href="authentication-forgot-password.html">Forgot Password ?</a>
                                            </div>
                                            <div class="col-12">
                                                <div class="d-grid">
                                                    <button type="submit" class="btn btn-primary"><i
                                                            class="bi bi-unlock"></i>Sign in</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end row-->
            </div>
        </div>
    </div>
    <!--end wrapper-->

    <script type="text/javascript" src="{{ URL::asset('js/app.js') }}"></script>
    <script>
        $(document).ready(function() {
            $("#show_hide_password a").on('click', function(event) {
                event.preventDefault();
                if ($('#show_hide_password input').attr("type") == "text") {
                    $('#show_hide_password input').attr('type', 'password');
                    $('#show_hide_password i').addClass("bx-hide");
                    $('#show_hide_password i').removeClass("bi-eye");
                } else if ($('#show_hide_password input').attr("type") == "password") {
                    $('#show_hide_password input').attr('type', 'text');
                    $('#show_hide_password i').removeClass("bx-hide");
                    $('#show_hide_password i').addClass("bi-eye");
                }
            });
        });
    </script>
</body>

</html>
