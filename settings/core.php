<?php
// start session
session_start(); 

// for header redirection
ob_start();

// function to check for login
function check_login()
{
    if (isset($_SESSION['customer_id']) && !empty($_SESSION['customer_id'])) {
        return true;
    }
    return false;
}

// function to get user ID
function get_user_id()
{
    if (check_login()) {
        return $_SESSION['customer_id'];
    }
    return false;
}

// function to check for role (admin, customer, etc)
function check_admin()
{
    if (check_login()) {
        // check if user role is 1 (admin)
        if (isset($_SESSION['user_role']) && $_SESSION['user_role'] == 1) {
            return true;
        }
    }
    return false;
}

// function to get user role
function get_user_role()
{
    if (check_login()) {
        return isset($_SESSION['user_role']) ? $_SESSION['user_role'] : 2;
    }
    return false;
}

// function to get user name
function get_user_name()
{
    if (check_login()) {
        return isset($_SESSION['customer_name']) ? $_SESSION['customer_name'] : 'User';
    }
    return false;
}

// function to redirect to login if not logged in
function require_login()
{
    if (!check_login()) {
        header('Location: ../login/login.php');
        exit();
    }
}

// function to require admin privileges
function require_admin()
{
    if (!check_login()) {
        header('Location: ../login/login.php');
        exit();
    }
    
    if (!check_admin()) {
        header('Location: ../index.php');
        exit();
    }
}
?>