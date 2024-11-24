<?php
session_start();
//echo '<pre>';
//print_r($_SESSION);
/*
 * Destroy Session
 */
session_destroy();

/*
 * Or unset only user
 */
session_unset($_SESSION['username']);

header('location:index.html?msg=logout_success');