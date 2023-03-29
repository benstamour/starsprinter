<?php
    // confirm that password input matches when signing up

    $pwd1 = $_REQUEST['pwd1'];
    $pwd2 = $_REQUEST['pwd2'];

    if($pwd1 == $pwd2)
    {
        $msg = "Passwords match!";
    }
    else
    {
        $msg = "Passwords do not match";
    }
    echo $msg;
?>