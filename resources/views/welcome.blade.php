<!doctype html>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Laravel</title>

        <link rel="stylesheet" href="{{ mix('/css/app.css') }}">
        <script>
            window.laravel = {!! json_encode([
                'csrfToken' => csrf_token()
            ]) !!}
        </script>
    </head>
    <body class="blue-grey lighten-5">
        <nav id="main-nav">
            <div class="nav-wrapper container-fluid grey darken-4">
                <div class="row">
                    <div class="col s12">
                        <a href="#/" class="brand-logo"><i class="material-icons">chat</i>ChatBot</a>

                        <ul class="right hide-on-med-and-down">
                            <li>
                                <a id="nav-mobile" href="#" data-activates="slide-out" class="menu">
                                    <i class="material-icons">menu</i>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>

        <ul id="slide-out" class="side-nav">
            <li>
                <div class="userView">
                    <div class="background"><img src="img/leaf_background.jpg" alt=""></div>
                    <a><img src="https://secure.gravatar.com/avatar/a3a376ce8bdd3962a36b311bb8811912" alt="" class="circle"></a>
                    <a><span class="white-text name">Vinicius Pedrosa</span></a>
                    <a href="https://github.com/vipeol"><span class="white-text email">@vipeol</span></a>
                </div>
            </li>
        </ul>
        <div id="app"></div>
        <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.99.0/js/materialize.min.js"></script>
        <script>
            $('#nav-mobile').sideNav();
        </script>
        <script src="{{ mix('/js/app.js') }}"></script>
    </body>
</html>
