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
    <title>Информация о специалисте</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/lk.css">
    <link rel="stylesheet" href="css/doctors_lk.css">
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
            <?php
                $pdo = new mysqli('localhost','root','mysql','proekt');

                // Проверка соединения
                if ($pdo->connect_error) {            
                    die("Connection failed: " . $pdo->connect_error);
                }

                // Получаем id пациента из параметра запроса
                $doctorId = $_GET['id'];

                // Запрос к базе данных для получения информации о пациенте по его ID

                $sql = "SELECT * FROM users WHERE id = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->bind_param("i", $doctorId);
                $stmt->execute();
                $result = $stmt->get_result();

                // Получение данных о пациенте
                $doctor = $result->fetch_assoc();

                $pdo->close();
            ?>  

            <section class="block__ava">
                <img class="avatar avatar__lk" src="<?php echo $doctor["photo"]; ?>" alt="">
                <!-- <input class="avatar__input" type="file">
                <button class="avatar__btn">Удалить</button> -->
            </section>

            <section class="block__inputs">
                <div class="info__block">
                    <div class="info__label info__element">Фамилия:</div>
                    <input class="info__input info__element" type="text" placeholder="" value="<?php echo $doctor["surname"] ?>"></input>
                </div>
                <div class="info__block">
                    <div class="info__label info__element">Имя:</div>
                    <input class="info__input info__element" type="text" value="<?php echo $doctor["name"] ?>"></input>
                </div>
                <div class="info__block">
                    <div class="info__label info__element">Отчество:</div>
                    <input class="info__input info__element" type="text" value="<?php echo $doctor["otchestvo"] ?>"></input>
                </div>
                <div class="info__block">
                    <div class="info__label info__element">Дата рождения:</div>
                    <input class="info__input info__element" type="text" value="<?php echo $doctor["birthday"] ?>"></input>
                </div>
                <div class="info__block">
                    <div class="info__label info__element">Образование:</div>
                    <input class="info__input info__element" type="text" value="<?php echo $doctor["obrazovanie"] ?>"></input>
                </div>
                <div class="info__block">
                    <div class="info__label info__element">Специальность:</div>
                    <input class="info__input info__element" type="text" value="<?php echo $doctor["specialnost"] ?>"></input>
                </div>
                <div class="info__block">
                    <div class="info__label info__element">Должность:</div>
                    <input class="info__input info__element" type="text" value="<?php echo $doctor["dolzhnost"] ?>"></input>
                </div>
                <div class="info__btns">
                    <a href="doctors.php" class="btn btn__color" >Назад</a>
                </div>

            </section>

            <section class="block__inputs" style="display: flex; flex-direction: column;">
                
                <div class="info__block" style="align-items: flex-start;">
                    <div class="info__label info__element">ЛПУ:</div>
                    <input class="info__input info__element" type="text" value="<?php echo $doctor["LPU"] ?>">
                </div>
                <div class="info__block" style="align-items: flex-start;">
                    <div class="info__label info__element">Отделение:</div>
                    <input class="info__input info__element" type="text" value="<?php echo $doctor["otdelenie"] ?>">
                </div>
                <div class="info__block" style="align-items: flex-end;">
                    <div class="info__label info__element">E-mail:</div>
                    <input class="info__input info__element" type="text" value="<?php echo $doctor["email"] ?>">
                </div>
                <div class="info__block" style="align-items: flex-end;">
                    <div class="info__label info__element">Телефон:</div>
                    <input class="info__input info__element" type="text" value="<?php echo $doctor["number"] ?>">
                </div>
                <div class="info__block" style="align-items: flex-end;">
                    <br>Связаться</br>
                    <div class="info__element">
                        <!-- WhatsApp -->
                        <a href="https://wa.me/<?php echo $doctor['number']; ?>" target="_blank">
                            <button class="btn btn__color svyaz">
                                <img src="images/whatsapp.png">
                            </button>
                        </a>
                        
                        <!-- Telegram -->
                        <a href="https://t.me/<?php echo $doctor['telegram']; ?>" target="_blank">
                            <button  class="btn btn__color svyaz">
                                <img src="images/telegram.png">
                            </button>
                        </a>
                    </div>
                </div>

            </section>

        </section>

        </main>

    </div>

</body>

</html>