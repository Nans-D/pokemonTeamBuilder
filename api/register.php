<?php
session_start();
require_once '../config.php';
require_once 'class/user.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {

        $link = connexion();
        if (empty($_POST['inputName']) || empty($_POST['inputEmail']) || empty($_POST['inputPassword'])) {
            throw new Exception('Please fill in all fields');
        }

        $name = mysqli_real_escape_string($link, $_POST['inputName']);
        $email = mysqli_real_escape_string($link, $_POST['inputEmail']);
        $password = password_hash(mysqli_real_escape_string($link, $_POST['inputPassword']), PASSWORD_DEFAULT);

        $user = new User(null, $name, $email, $password);
        if (!$user->checkUser()) {
            throw new Exception('Email already exists');
        }
        $user->create();

        if (is_null($user->getId())) {
            throw new Exception('Error while creating user');
        }

        $_SESSION['name'] = $user->getName();

        if (!isset($_SESSION['name'])) {
            throw new Exception('Error while getting user');
        }



        header('Location: ../index.php');
        exit;
    } catch (Exception $e) {
        $response['response'] = 400;
        $response['message'] = $e->getMessage();
        echo json_encode($response);
        exit();
    }
}
