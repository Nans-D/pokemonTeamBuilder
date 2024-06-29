<?php
require_once '../config.php';
require_once './class/user.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $link = connexion();
        if (empty($_POST['inputEmail']) || empty($_POST['inputPassword'])) {
            throw new Exception('Please fill in all fields');
        }


        $email = mysqli_real_escape_string($link, $_POST['inputEmail']);
        $password = mysqli_real_escape_string($link, $_POST['inputPassword']);
        $user = new User(null, null, $email, $password);

        if (!$user->Login()) {
            throw new Exception('Wrong email or password');
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
