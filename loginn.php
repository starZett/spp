<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SPP | Login</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="dist/css/login.css">
</head>

<body>

    <div class="login_form_container">
        <div class="login_form">
            <h2>Login SPP</h2>
            <form action="aksi/user.php" method="post">
                <input type="hidden" name="aksi" value="login">
                <div class="input_group">
                    <i class="fa fa-user"></i>
                    <input type="text" placeholder="Username" name="username" class="input_text" autocomplete="off" />
                </div>
                <div class="input_group">
                    <i class="fa fa-unlock-alt"></i>
                    <input type="password" placeholder="Password" name="password" class="input_text" autocomplete="off" />
                </div>
                <div class="button_group" id="login_button">
                    <button><a class><span style="color: white;">Login</span></a></button>
                </div>
                <div class="fotter">
                    <a>Forgot Password ?</a>
                    <a>Sign Up</a>
                </div>
        </div>
    </div>

    <script src="plugins/jquery/jquery.min.js"></script>
    <script src="dist/js/login.js"></script>
</body>

</html>