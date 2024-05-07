<!-- добавление пациента -->
<?php
        $pdo = new mysqli('localhost','root','mysql','proekt');

        // Проверка соединения
        if ($pdo->connect_error) {
            die("Connection failed: " . $pdo->connect_error);
        }

        session_start();
        
        if (isset($_POST["submit1"])) {
            // Получаем данные из формы
            $familia = $_POST["familiya"];
            $name_hozyaina = $_POST["name_hozyaina"];
            $otchestvo = $_POST["otchestvo"];
            $number = $_POST["number"];
            $vid_animal = $_POST["vid_animal"];
            $poroda = $_POST["poroda"]; 
            $klichka = $_POST["klichka"]; 
            $birthday = $_POST["birthday"]; 

            // Вставляем данные в базу данных
            $sql = "INSERT INTO patients (familiya, name_hozyaina, otchestvo, number, vid_animal, poroda, klichka, birthday) VALUES ('$familia', '$name_hozyaina', '$otchestvo', '$number', '$vid_animal', '$poroda', '$klichka', '$birthday')";

            if ($pdo->query($sql) === TRUE) {
                // Получаем последний вставленный id_patient
                $lastPatientId = $pdo->insert_id;
        
                // Вставляем запись в таблицу lechenie с полученным id_patient и начальным диагнозом
                $sql_lechenie = "INSERT INTO lechenie (id_patient, diagnoz) VALUES ('$lastPatientId', 'Нет диагноза')";
                if ($pdo->query($sql_lechenie) === TRUE) {
                    echo "<script> alert('Пациент успешно добавлен'); window.location.href = window.location.href;</script>";
                    exit;
                } else {
                    echo "<script>alert('Ошибка при добавлении записи в таблицу lechenie: " . $pdo->error . "'); window.location.href = window.location.href;</script>";
                    exit;
                }
            } else {
                echo "<script>alert('Ошибка: " . $sql . "\\n" . $pdo->error . "'); window.location.href = window.location.href;</script>";
                exit;
            }
        }    
        
        // Получаем информацию о пациентах из базы данных
        $sql = "SELECT * FROM patients";
        $result = $pdo->query($sql);
        $pdo->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="adminka/styles_admin.css">
    <title>Админка</title>
    <link rel="stylesheet" href="adminka/font.css"> <!-- Подключение файла со шрифтом -->
</head>

<body>

    <div class="header">
        <img src="adminka//logo.svg" alt="Логотип">
        <span class="admin-panel">АДМИНИСТРАТИВНАЯ ПАНЕЛЬ</span>
        <div class="info__btns">
            <a href="index.html" class="btn btn__border">Выйти</a>
        </div>
    </div>
    

    <nav class="tabs">
        <ul>
            <li><a href="#" onclick="toggleContent('patients-tab')">ДОБАВИТЬ ПАЦИЕНТА</a></li>
            <li><a href="#" onclick="toggleContent('specialists-tab')">ДОБАВИТЬ СПЕЦИАЛИСТА</a></li>
            <li><a href="#" onclick="toggleContent('appointments-tab')">НАЗНАЧИТЬ ПРИЁМ</a></li>
        </ul>
    </nav>
    <!-- Контент для вкладки ДОБАВИТЬ ПАЦИЕНТА -->
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <div id="patients-tab" class="tab-content hidden container">
            
            <div class="info__column">
                <!-- Первая строка -->
                <div class="info__block">
                    <div class="info__label info__element">Фамилия хозяина:</div>
                    <input class="info__input__patients info__element" type="text" name="familiya">
                </div>
                <div class="info__block">
                    <div class="info__label info__element">Вид питомца:</div>
                    <input class="info__input__patients info__element" type="text" name="vid_animal">
                </div>
            </div>
            <div class="info__column">
                <!-- Вторая строка -->
                <div class="info__block">
                    <div class="info__label info__element">Имя хозяина:</div>
                    <input class="info__input__patients info__element" type="text" name="name_hozyaina">
                </div>
                <div class="info__block">
                    <div class="info__label info__element">Порода питомца:</div>
                    <input class="info__input__patients info__element" type="text" name="poroda">
                </div>
            </div>
            <div class="info__column">
                <!-- Третья строка -->
                <div class="info__block">
                    <div class="info__label info__element">Отчество хозяина:</div>
                    <input class="info__input__patients info__element" type="text" name="otchestvo">
                </div>
                <div class="info__block">
                    <div class="info__label info__element">Кличка:</div>
                    <input class="info__input__patients info__element" type="text" name="klichka">
                </div>
            </div>
            <div class="info__column">
                <!-- Четвёртая строка -->
                <div class="info__block">
                    <div class="info__label info__element">Телефон хозяина:</div>
                    <input class="info__input__patients info__element" type="text" name="number">
                </div>
                <div class="info__block">
                    <div class="info__label info__element">Дата рождения питомца(XXXX-XX-XX):</div>
                    <input class="info__input__patients info__element" type="text" name="birthday">
                </div>
            </div>
            <button class="info__button" type="submit" name="submit1">ЗАНЕСТИ В БАЗУ ДАННЫХ</button>
            <style>
                .list__patients {
                    margin: 0 auto; /* Центрирование таблицы по горизонтали */
                    width: 50%; /* Ширина таблицы */
                }
                table {
                    width: 100%; /* Ширина таблицы */
                }
                th, td {
                    text-align: left; /* Выравнивание текста в ячейках по левому краю */
                    padding: 8px; /* Отступы внутри ячеек */
                    border-bottom: 1px solid #ddd; /* Горизонтальные линии между строками */
                }
                th {
                    background-color: #f2f2f2; /* Цвет фона для заголовков */
                }
            </style>
            <div class="list__patients">
                <h2>Список пациентов</h2>
                <table>
                    <tr>
                        <th>Фамилия</th>
                        <th>Имя</th>
                        <th>Кличка животного</th>
                    </tr>
                    <?php
                    // Выводим информацию о пациентах на страницу
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<tr><td>" . $row["familiya"] . "</td><td>" . $row["name_hozyaina"] . "</td><td>" . $row["klichka"] . "</td></tr>";
                        }
                    } else {
                        echo "0 результатов";
                    }
                    ?>
                </table>
            </div>
        </div>
    
    </form>
    <!-- добавление специалиста -->
    <?php 
        $pdo = new mysqli('localhost','root','mysql','proekt');

        // Проверка соединения
        if ($pdo->connect_error) {
            die("Connection failed: " . $pdo->connect_error);
        }

        // Проверяем, была ли отправлена форма
        if (isset($_POST["submit2"])) {
                // Получаем данные из формы
                $login= $_POST["login"];
                $password = $_POST["password"];
                $email = $_POST["email"];
                $name = $_POST["name"];
                $surname = $_POST["surname"];
                $otchestvo = $_POST["otchestvo"];
                $birthday = $_POST["birthday"];
                $obrazovanie = $_POST["obrazovanie"]; 
                $specialnost = $_POST["specialnost"];
                $dolzhnost = $_POST["dolzhnost"];
                $LPU = $_POST["LPU"];
                $otdelenie = $_POST["otdelenie"];
                $number = $_POST["number"];
                $telegram = $_POST["telegram"];
                $photo = $_POST["photo"];
                $test = $_POST["test"];

                // Вставляем данные в базу данных
                $sql_doctors = "INSERT INTO users (login, password, email, name, surname, otchestvo, 
                birthday, obrazovanie, specialnost, dolzhnost, LPU, otdelenie, number, telegram, photo, test) VALUES ('$login', '$password', '$email', '$name', '$surname', '$otchestvo', 
                '$birthday', '$obrazovanie', '$specialnost', '$dolzhnost', '$LPU', '$otdelenie', '$number', '$telegram', '$photo',  '$test')";

                if ($pdo->query($sql_doctors) === TRUE) {
                    echo "<script> alert('Специалист успешно добавлен'); window.location.href = window.location.href;</script>";
                    exit;
                } else {
                    echo "<script>alert('Ошибка: " . $sql . "\\n" . $pdo->error . "'); window.location.href = window.location.href;</script>";
                    exit;
                }
            }    
        
        // Получаем информацию о специалистах из базы данных
        $sql_doctors = "SELECT * FROM users WHERE login != 'admin'";
        $result_doctors = $pdo->query($sql_doctors);
        
        $pdo->close();
    ?>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <div id="specialists-tab" class="tab-content hidden container">
            <div class="info__column">
                <!-- Левая колонка -->
                <div class="info__block">
                    <div class="info__label info__element">Фамилия:</div>
                    <input class="info__input__patients info__element" type="text" name="surname">
                </div>
                <div class="info__block">
                    <div class="info__label info__element">Имя:</div>
                    <input class="info__input__patients info__element" type="text" name="name">
                </div>
                <div class="info__block">
                    <div class="info__label info__element">Отчество:</div>
                    <input class="info__input__patients info__element" type="text" name="otchestvo">
                </div>
                <div class="info__block">
                    <div class="info__label info__element">Дата рождения:</div>
                    <input class="info__input__patients info__element" type="text" name="birthday">
                </div>
            </div>
            <div class="info__column">
                <!-- Средняя колонка -->
                <div class="info__block">
                    <div class="info__label info__element">Образование:</div>
                    <input class="info__input__patients info__element" type="text" name="obrazovanie">
                </div>
                <div class="info__block">
                    <div class="info__label info__element">Специальность:</div>
                    <input class="info__input__patients info__element" type="text" name="specialnost">
                </div>
                <div class="info__block">
                    <div class="info__label info__element">Должность:</div>
                    <input class="info__input__patients info__element" type="text" name="dolzhnost">
                </div>
                <div class="info__block">
                    <div class="info__label info__element">Отделение:</div>
                    <input class="info__input__patients info__element" type="text" name="otdelenie">
                </div>
            </div>
            <div class="info__column">
                <!-- Правая колонка -->
                <div class="info__block">
                    <div class="info__label info__element">email:</div>
                    <input class="info__input__patients info__element" type="text" name="email">
                </div>
                <div class="info__block">
                    <div class="info__label info__element">Телефон:</div>
                    <input class="info__input__patients info__element" type="number" name="number" default="0">
                </div>
                <div class="info__block">
                    <div class="info__label info__element">LPU:</div>
                    <input class="info__input__patients info__element" type="text" name="LPU">
                </div>
                <div class="info__block">
                    <div class="info__label info__element">Telegram:</div>
                    <input class="info__input__patients info__element" type="text" name="telegram">
                </div>
            </div>
            <div class="info__column">
                <!-- Правая колонка -->
                <div class="info__block">
                    <div class="info__label info__element">Логин:</div>
                    <input class="info__input__patients info__element" type="text" name="login">
                </div>
                <div class="info__block">
                    <div class="info__label info__element">Пароль:</div>
                    <input class="info__input__patients info__element" type="text" name="password">
                </div>
                <!-- <div class="info__block">
                    <div class="info__label info__element">test:</div>
                    <input class="info__input__patients info__element" type="text" name="test">
                </div> -->
                <div class="info__block">
                    <div class="info__label info__element">Фотография(ссылка):</div>
                    <input class="info__input__patients info__element" type="text" name="photo">
                </div>
            </div>
            <button class="info__button" type="submit" name="submit2">ЗАНЕСТИ В БАЗУ ДАННЫХ</button>
            <div class="list__doctors">
                <h2>Список специалистов</h2>
                <table>
                    <tr>
                        <th>Фамилия</th>
                        <th>Имя</th>
                        <th>Должность</th>
                        <th>Логин</th>
                        <th>Пароль</th>
                    </tr>
                    <?php
                    // Выводим информацию о пациентах на страницу
                    if ($result_doctors->num_rows > 0) {
                        while($row = $result_doctors->fetch_assoc()) {
                            echo "<tr><td>" . $row["surname"] . "</td><td>" . $row["name"] . "</td>
                            <td>" . $row["dolzhnost"] . "</td><td>" . $row["login"] . "</td><td>" . $row["password"] . "</td></tr>";
                        }
                    } else {
                        echo "0 результатов";
                    }
                    ?>
                </table>
            </div>
        </div>
    </form>

    <!-- Контент для вкладки НАЗНАЧИТЬ ПРИЁМ -->
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"> 

        <div id="appointments-tab" class="tab-content hidden container">
            
            <div class="info__block">
                <div class="info__label info__element">Дата приёма:</div>
                <input class="info__input__patients info__element" type="date" id="date" name="date">
            </div>
            <div class="info__block">
                <div class="info__label info__element">Время приёма:</div>
                <input class="info__input__patients info__element" type="time" id="time" name="time">
            </div>
            <!-- извлечение инфы для выпадающего списка пациентов и докторов-->
            <?php

                $pdo = new mysqli('localhost','root','mysql','proekt');

                // Проверка соединения
                if ($pdo->connect_error) {
                    die("Connection failed: " . $pdo->connect_error);
                }

                // Извлечение данных о пациентах из базы данных
                $sql = "SELECT id_patient, familiya, klichka FROM patients";
                $result_select1 = $pdo->query($sql);

                $patients = array();
                if ($result_select1->num_rows > 0) {
                    while ($row1 = $result_select1->fetch_assoc()) {
                        $patients[] = $row1;
                    }
                }
                
                // Извлечение данных о специалистах из базы данных
                $sql1 = "SELECT id, surname, dolzhnost FROM users  WHERE  login != 'admin'";
                $result_select2 = $pdo->query($sql1);

                $doctors = array();
                if ($result_select2->num_rows > 0) {
                    while ($row2 = $result_select2->fetch_assoc()) {
                        $doctors[] = $row2;
                    }
                }

                $pdo->close();

            ?>

            <div class="info__block">
                <div class="info__label info__element">Пациент:</div>
                <select name="patient">
                    <?php foreach ($patients as $patient): ?>
                        <option value="<?php echo $patient['id_patient']; ?>">
                            <?php echo $patient['familiya'] . ' ' . $patient['klichka']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="info__block">
                <div class="info__label info__element">Доктор:</div>
                <select name="doctor">
                    <?php foreach ($doctors as $doctor): ?>
                        <option value="<?php echo $doctor['id']; ?>">
                            <?php echo $doctor['surname'] . ' ' . $doctor['dolzhnost']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <?php

                $pdo = new mysqli('localhost','root','mysql','proekt');

                // Проверка соединения
                if ($pdo->connect_error) {
                    die("Connection failed: " . $pdo->connect_error);
                }

                $successMessage = "";
                $errorMessage = "";

                if (isset($_POST["submit3"])) {
                    // Получение данных из формы
                    $date = $_POST['date'];
                    $time = $_POST['time'];
                    $patientId = $_POST['patient'];
                    $doctorId = isset($_POST['doctor']) ? $_POST['doctor'] : null;

                    // Проверка всех данных на наличие
                    if (empty($date) || empty($time) || empty($patientId) || empty($doctorId)) {
                        $errorMessage = "Пожалуйста, заполните все поля";
                    } else {
                        // Подключение к базе данных
                        $pdo = new mysqli('localhost','root','mysql','proekt');

                        // Проверка соединения
                        if ($pdo->connect_error) {
                            die("Connection failed: " . $pdo->connect_error);
                        }

                        // Проверяем, существует ли уже запись о связи доктора и пациента в таблице doctors_patients
                        $sql_check = "SELECT COUNT(*) AS count FROM doctors_patients WHERE id_doctor = '$doctorId' AND id_patient = '$patientId'";
                        $result_check = $pdo->query($sql_check);
                        $row_check = $result_check->fetch_assoc();
                        $count = $row_check['count'];

                        if ($count == 0) {
                            // Если запись о связи доктора и пациента не существует, добавляем ее
                            $sql_insert = "INSERT INTO doctors_patients (id_doctor, id_patient) VALUES ('$doctorId', '$patientId')";
                            $pdo->query($sql_insert);
                        }

                        // SQL запрос для добавления записи о приеме в базу данных
                        $sql3 = "INSERT INTO naznachenie_priema (date, time, id_patient, id_doctor) VALUES ('$date', '$time', '$patientId','$doctorId')";

                        // Выполнение запроса
                        if ($pdo->query($sql3) === TRUE) {
                            $successMessage = "Запись о приеме успешно добавлена";
                        } else {
                            $errorMessage = "Ошибка при добавлении записи о приеме: " . $pdo->error;
                        }

                        // Закрытие соединения с базой данных
                        $pdo->close();
                    }
                }
               
            ?>
            <button class="info__button" type="submit" name="submit3">НАЗНАЧИТЬ ПРИЕМ</button>
            
        </div>
    </form>

    <script src="adminka/script.js"></script>
    <script>
        <?php if (!empty($successMessage)): ?>
            alert("<?php echo $successMessage; ?>");
        <?php elseif (!empty($errorMessage)): ?>
            alert("<?php echo $errorMessage; ?>");
        <?php endif; ?>
    </script>

</body>

</html>