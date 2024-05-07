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
                $photo = $row["photo"];
                $currentUserId = $row["id"];
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
    <title>Пациенты</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/patients_all.css">
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
            
            <img class="avatar" src="<?php echo $photo; ?>" alt="" width="10" height="10">

            <div class="user__name">
                <strong><?php echo $name; ?> </strong> <br><?php echo $specialnost; ?></br>
            </div>
        </a>
    </header>
        
    <div class="found_pages">
        <main>

        
            <div class="block__list">
                <div class="list__header">
                    
                    <div class="head__item">Имя</div>
                    <div class="head__item">Дата рождения</div>
                    <div class="head__item">Хозяин</div>
                    <div class="search__container">
                        <form method="GET" action="">
                            <input type="text" name="search" placeholder="Поиск">
                            <button type="submit">Поиск</button>
                        </form>
                    </div>
                    
                </div>
                <?php
                    $pdo = new mysqli('localhost','root','mysql','proekt');

                    // Проверка соединения
                    if ($pdo->connect_error) {
                        die("Connection failed: " . $pdo->connect_error);
                    }

                    // Инициализация переменной для поискового запроса
                    $searchText = isset($_GET['search']) ? $_GET['search'] : '';

                    // Запрос к базе данных для получения списка пациентов, привязанных к текущему доктору
                    $sql = "SELECT patients.* FROM patients 
                            INNER JOIN doctors_patients ON patients.id_patient = doctors_patients.id_patient 
                            WHERE doctors_patients.id_doctor = $currentUserId";

                    // Если есть текст поиска, добавляем условие в запрос
                    if (!empty($searchText)) {
                        $searchText = $pdo->real_escape_string($searchText);
                        $sql .= " AND (patients.klichka LIKE '%$searchText%' OR patients.birthday LIKE '%$searchText%' OR CONCAT(patients.name_hozyaina, ' ', patients.familiya) LIKE '%$searchText%')";
                    }

                    $result = $pdo->query($sql);

                    // Проверка наличия данных в результате запроса
                    if ($result->num_rows > 0) {
                        // Вывод данных о пациентах
                        while ($row = $result->fetch_assoc()) {
                            echo "<div class='list__item'>";
                            echo "<div class='list__element'>" . $row["klichka"] . "</div>";
                            echo "<div class='list__element'>" . $row["birthday"] . "</div>";
                            echo "<div class='list__element'>" . $row["name_hozyaina"] . " " . $row["familiya"]  . "</div>";
                            echo "<a class='btn btn__color' href='patients_add_new.php?id_patient=" . $row["id_patient"] . "'>Узнать подробнее</a>";
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
<!-- скрипт для поиска по странице -->


</html>