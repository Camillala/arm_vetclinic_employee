<?php
        $pdo = new mysqli('localhost','root','mysql','proekt');

        // Проверка соединения
        if ($pdo->connect_error) {
            die("Connection failed: " . $pdo->connect_error);
        }

        session_start();

        if(isset($_SESSION["user"])) {
            // Получаем логин пользователя из сессии
            $login = $_SESSION["user"];
        
            // Запрос к базе данных для получения информации о пользователе
            $sql = "SELECT * FROM users WHERE login = '$login'";
            $result = $pdo->query($sql);
        
            if ($result->num_rows > 0) {
                // Выводим данные пользователя в поля ввода на веб-странице
                $row = $result->fetch_assoc();
                $currentUserId = $row["id"];
                $photo = $row["photo"];
                $email = $row["email"];
                $name = $row["name"];
                $familia = $row["surname"];
                $otchestvo = $row["otchestvo"];
                $birthday = $row["birthday"];
                $obrazovanie = $row["obrazovanie"];
                $specialnost = $row["specialnost"];
                $dolzhnost = $row["dolzhnost"];
                $LPU = $row["LPU"];
                $otdelenie = $row["otdelenie"];
                $number = $row["number"];
                $telegram = $row["telegram"];
            }
        }
        
        $pdo->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Специалисты</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/doctors.css">
    <link rel="shortcut icon" href="images/logo.svg" type="image/x-icon">
</head>

<body>
    <header>
        <img class="logo" src="images/logo_fav.svg" alt="">


        <nav>
            <a class="nav__item" href="patients_all.php">ПАЦИЕНТЫ</a>
            <a class="nav__item" href="doctors.php">СПЕЦИАЛИСТЫ</a>
            <a class="nav__item" href="timing.php">РАСПИСАНИЕ</a>
        </nav>
        <a class="btn__login user" href="lk.php">
            <img class="avatar" src="<?php echo $photo; ?>" alt="">
            <div class="user__name">
                <strong><?php echo $name; ?> </strong> <br><?php echo $specialnost; ?></br>
            </div>
        </a>
    </header>

    <div class="found_pages">
        <main>
            <div class="block__list">
                <div class="list__header">
                    <div class="head__item">ФИО</div>
                    <div class="head__item">Специальность</div>
                    <div class="search__container">
                        <form method="GET" action="">
                            <input type="text" name="search" placeholder="Поиск">
                            <button type="submit">Поиск</button>
                        </form>
                    </div>
                </div>
                
                <?php

                    $pdo = new mysqli('localhost', 'root', 'mysql', 'proekt');

                    // Проверка соединения
                    if ($pdo->connect_error) {
                        die("Connection failed: " . $pdo->connect_error);
                    }

                    // Поиск
                    if (isset($_GET['search'])) {
                        $search = $_GET['search'];
                        // Запрос к базе данных для поиска врачей по введенному тексту
                        $sql = "SELECT * FROM users WHERE login != 'admin' AND id != '$currentUserId' AND (surname LIKE '%$search%' OR name LIKE '%$search%' OR otchestvo LIKE '%$search%' OR specialnost LIKE '%$search%')";
                    } else {
                        // Запрос к базе данных для получения списка врачей без админа и текущего пользователя
                        $sql = "SELECT * FROM users WHERE login != 'admin' AND id != '$currentUserId'";
                    }

                    $result = $pdo->query($sql);

                    // Проверка наличия данных в результате запроса
                    if ($result->num_rows > 0) {
                        // Вывод данных о врачах
                        while ($row = $result->fetch_assoc()) {
                            echo "<div class='list__item'>";
                            echo "<div class='list__element'>" . $row["surname"] . " " . $row["name"] . " " . $row["otchestvo"] . "</div>";
                            echo "<div class='list__element'>" . $row["specialnost"] . "</div>";
                            echo "<a class='btn btn__color' href='doctors_lk.php?id=" . $row["id"] . "'>Узнать подробнее</a>";
                            echo "</div>";
                        }
                    } else {
                        echo "0 результатов";
                    }

                    $pdo->close();
                ?>
            </div>
        </main>
    </div>
                    
</body>

</html>