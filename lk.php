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
    <title>Личный кабинет</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/lk.css">
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

            <section class="block__ava">
                <img class="avatar avatar__lk" src="<?php echo $photo; ?>" alt="">
                <!-- <input class="avatar__input" type="file">
                <button class="avatar__btn">Удалить</button> -->
                <div class="info__btns">
                    <a href="index.html" class="btn btn__border">Выйти</a>
                </div>
            </section>

            <section class="block__inputs">
                <div class="info__block">
                    <div class="info__label info__element">Фамилия:</div>
                    <input class="info__input info__element" type="text" placeholder="" value="<?php echo $familia; ?>"></input>
                </div>
                <div class="info__block">
                    <div class="info__label info__element">Имя:</div>
                    <input class="info__input info__element" type="text" value="<?php echo $name; ?>"></input>
                </div>
                <div class="info__block">
                    <div class="info__label info__element">Отчество:</div>
                    <input class="info__input info__element" type="text" value="<?php echo $otchestvo; ?>"></input>
                </div>
                <div class="info__block">
                    <div class="info__label info__element">Дата рождения:</div>
                    <input class="info__input info__element" type="text" value="<?php echo $birthday; ?>"></input>
                </div>
                <div class="info__block">
                    <div class="info__label info__element">Образование:</div>
                    <input class="info__input info__element" type="text" value="<?php echo $obrazovanie; ?>"></input>
                </div>
                <div class="info__block">
                    <div class="info__label info__element">Специальность:</div>
                    <input class="info__input info__element" type="text" value="<?php echo $specialnost; ?>"></input>
                </div>
                

            </section>

            <section class="block__inputs">
                <div class="info__block">
                    <div class="info__label info__element">Должность:</div>
                    <input class="info__input info__element" type="text" value="<?php echo $dolzhnost; ?>"></input>
                </div>
                <div class="info__block">
                    <div class="info__label info__element">ЛПУ:</div>
                    <input class="info__input info__element" type="text" value="<?php echo $LPU; ?>"></input>
                </div>
                <div class="info__block">
                    <div class="info__label info__element">Отделение:</div>
                    <input class="info__input info__element" type="text" value="<?php echo $otdelenie; ?>"></input>
                </div>
                <div class="info__block">
                    <div class="info__label info__element">E-mail:</div>
                    <input class="info__input info__element" type="text" value="<?php echo $email; ?>"></input>
                </div>
                <div class="info__block">
                    <div class="info__label info__element">Телефон:</div>
                    <input class="info__input info__element" type="text" value="<?php echo $number; ?>"></input>
                </div>
                <div class="info__block">
                    <div class="info__label info__element">Телеграм:</div>
                    <input class="info__input info__element" type="text" value="<?php echo $telegram; ?>"></input>
                </div>
                
            </section>

        </main>

    </div>

</body>

</html>