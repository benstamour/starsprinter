<?php
    // validate email and password combination
    include("connect.php");

    $email = $_REQUEST['email'];
    $pwd = $_REQUEST['pwd'];

    $sql = 'SELECT `email` FROM `users` WHERE `email` = "'.$email.'" && `password` = "'.$pwd.'"';
    $result = mysqli_query($link, $sql);

    $msg = "Email and password do not match";
    while($row = mysqli_fetch_row($result))
    {
        if($row[0] == "")
        {
            $msg = "Email and password do not match";
        }
        else
        {
            $msg = "Email and password match!";
        }
    }
    echo $msg;
?>