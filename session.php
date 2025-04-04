<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Start session only if it's not already started
}
?>
