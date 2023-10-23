<!doctype html>
<html lang="en">

<head>
    <title>Register</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>

    <div class="container">
        <div class="row">
            <div class="col-md-4 offset-md-4">
                <h2 class="text-center text-dark mt-5">Register</h2>
                <div class="card my-5">
                    <form class="card-body cardbody-color p-lg-5" method="POST" action="{{ route('register.post') }}">
                        @csrf
                        <div class="mb-3">
                            <input type="text" class="form-control" id="name" name="name"
                                placeholder="Name">
                        </div>
                        <div class="mb-3">
                            <input type="email" class="form-control" id="email" aria-describedby="emailHelp" name="email"
                                placeholder="Email">
                        </div>
                        <div class="mb-3">
                            <input type="password" class="form-control" id="password" placeholder="Password" name="password">
                        </div>
                        <div class="mb-3">
                            <input type="password" class="form-control"  name="password_confirmation" id="password_confirmation" placeholder="Password Confirmation">
                        </div>
                        <div class="text-center"><button type="submit"
                                class="btn btn-color px-5 mb-5 w-100">Register</button></div>
                        <div id="emailHelp" class="form-text text-center mb-5 text-dark">Have an account? <a href="{{ route('login') }}" class="text-dark fw-bold"> Login</a>
                        </div>
                        @include('_message')
                        @if ($errors->has('name'))
                        <div class="alert alert-danger">
                            {{ $errors->first('name') }}
                        </div>
                        @endif
                        @if ($errors->has('email'))
                        <div class="alert alert-danger">
                            {{ $errors->first('email') }}
                        </div>
                        @endif

                        @if ($errors->has('password'))
                        <div class="alert alert-danger">
                            {{ $errors->first('password') }}
                        </div>
                        @endif

                        @if ($errors->has('password_confirmation'))
                        <div class="alert alert-danger">
                            {{ $errors->first('password_confirmation') }}
                        </div>
                        @endif
                    </form>
                    
                </div>

            </div>
        </div>
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>
</body>

</html>
