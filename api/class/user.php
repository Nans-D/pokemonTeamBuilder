<?php

class User
{
    private $id;
    private $name;
    private $email;
    private $password;

    public function __construct($id = null, $name = null, $email = null, $password = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
    }

    public function checkUser()
    {
        $link = connexion();
        $sql = "SELECT * FROM user WHERE email = '$this->email'";
        // // echo $sql;
        // exit;
        $result = mysqli_query($link, $sql);
        if ($result) {
            if (mysqli_num_rows($result) > 0) {
                return false;
            } else {
                return true;
            }
        }
    }

    public function create()
    {
        $link = connexion();
        $sql = "INSERT INTO user (id, name, email, password) VALUES (NULL, '$this->name', '$this->email', '$this->password')";
        $result = mysqli_query($link, $sql);
        if ($result) {
            $this->id = mysqli_insert_id($link);
            return true;
        }
        return false;
    }

    public function Login()
    {
        $link = connexion();
        $sql = "SELECT * FROM user WHERE email = '$this->email'";
        $result = mysqli_query($link, $sql);
        if ($result) {
            $row = mysqli_fetch_assoc($result);
            if (password_verify($this->password, $row['password'])) {
                session_start();
                $_SESSION['idUser'] = $row['id'];
                $_SESSION['email'] = $row['email'];
                $_SESSION['name'] = $row['name'];
                return true;
            }
        }
        return false;
    }


    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getEmail()
    {
        return $this->email;
    }
}
