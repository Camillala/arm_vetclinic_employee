<?php
    session_start();
    $pdo = new mysqli('localhost','root','mysql','proekt');

    // Проверка соединения
    if ($pdo->connect_error) {
        die("Connection failed: " . $pdo->connect_error);
    }

    $login = trim($_POST['login']);
    $password = trim($_POST['password']);

        if (!empty($login) && !empty($password)) {

            $result = $pdo->query("SELECT * FROM users WHERE login='$login' AND password='$password'");
            $data = mysqli_fetch_array($result);

            if ($data) {
                if ($password == $data['password']) {
                    
                    $_SESSION['user'] = $data['login'];

                    if ($login == "admin") {
                        header("Location:/proekt/admin.php");
                    }
                    else {
                        header("Location:/proekt/lk.php");
                    }
                } 
                else {
                    echo "Неправильный пароль.";
                }
            }    
        else {
            echo "error";
            }
        }

    $pdo->close();
?>