<?php


echo "login";

/*$this->session = \Config\Services::session();
$this->session->start();
$newdata = [
    'username'  => 'launy',
    'email'     => 'macflodla@gmail.com',
    'id'     => 1,
    'token' => "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyfQ.SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c",
    'logged_in' => true,
];
$this->session->set("userdata", $newdata);*/
?>

<div class="wrapper fadeInDown">
    <div id="formContent">
        <!-- Tabs Titles -->
        <!-- Icon -->
        <div class="fadeIn first">

        </div>

        <!-- Login Form -->
        <form class="form-inner" id="form-login">
            <input type="text" id="mail" class="fadeIn second" name="mail"  placeholder="login">
            <input type="password" id="password" class="fadeIn third" name="password"
                   placeholder="password">
            <input type="submit" class="fadeIn fourth" value="Log In" id="form_sbm">
        </form>

        <!-- Remind Passowrd -->
        <div id="formFooter">
            <a class="underlineHover" href="#">Forgot Password?</a></br>
            <a class="underlineHover" href="#">Not account ? Register</a>
        </div>

    </div>
</div>

<script>
    $(document).ready(function () {
        $('#form-login').submit(function (event) {
            event.preventDefault();
            var settings = {
                "url": "http://localhost/commerce/api/V1/user/login",
                "method": "POST",
                "timeout": 0,
                "headers": {
                    "Content-Type": "application/json",
                    "Cookie": "ci_session=qh8pltqufa6rlo4fceufjurft0pgjufp"
                },
                "data": JSON.stringify({
                    "password": $('#password').val(),
                    "mail": $('#mail').val()
                }),
            };

            $.ajax(settings).done(function (response) {
                //redirection path pour set session
            });
        })
    });
</script>