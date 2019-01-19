<?php

$time_to_logout = 60 * 60;

if( !isset($_SESSION['login']) && isset($_SESSION['activity'])) {
    $session_life = time() - $_SESSION['activity']; 
    
    if($session_life > $time_to_logout) {
       header("Location: logout.php");
    }
    
    $_SESSION['activity'] = time();
}
