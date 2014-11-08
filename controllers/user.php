<?php

use PicoFarad\Router;
use PicoFarad\Response;
use PicoFarad\Request;
use PicoFarad\Session;
use PicoFarad\Template;

// Logout and destroy session
Router\get_action('logout', function() {

    Model\RememberMe\destroy();
    Session\close();
    Response\redirect('?action=login');
});

// Display form login
Router\get_action('login', function() {

    if (isset($_SESSION['user'])) {
        Response\redirect('?action=unread');
    }

    Response\html(Template\load('login', array(
        'errors' => array(),
        'values' => array(),
        'databases' => Model\Database\get_list(),
        'current_database' => Model\Database\select()
    )));
});

// Check credentials and redirect to unread items
Router\post_action('login', function() {

    $values = Request\values();
    list($valid, $errors) = Model\User\validate_login($values);

    if ($valid) {
        Response\redirect('?action=unread');
    }

    Response\html(Template\load('login', array(
        'errors' => $errors,
        'values' => $values,
        'databases' => Model\Database\get_list(),
        'current_database' => Model\Database\select()
    )));
});
