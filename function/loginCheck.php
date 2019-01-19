<?php

if ( !isset($_COOKIE["userId"]) && !isset($_SESSION["userId"]) ) {
    header('Location: login.php');
} else {
    $user = new User();
}