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
                $doctorId = $row["id"];
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
    <title>Информация о пациенте</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/patients.css">
    <link rel="shortcut icon" href="images/logo.svg" type="image/x-icon">

    <script>
       $(document).ready(function() {
            $(".btn.btn__color").click(function() {
                var patientId = $(this).data("id_patient");
                $.ajax({
                    //url: "get_patient_info.php",
                    type: "POST",
                    data: { id: patientId },
                    success: function(response) {
                        var data = JSON.parse(response);
                        $("#vid_animal").val(data.vid_animal);
                        $("#poroda").val(data.poroda);
                        $("#klichka").val(data.klichka);
                        $("#name_hozyaina").val(data.name_hozyaina);
                        $("#familiya").val(data.familiya);
                        $("#otchestvo").val(data.otchestvo);
                        $("#number").val(data.number);
                        $("#birthday").val(data.birthday);
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            });
        });
    </script>
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
            <!-- Изображение будет с БД, просто не стала загружать белый круг, пока поставила logo -->
            <img class="avatar" src="<?php echo $photo; ?>" alt="">
            <div class="user__name">
                <strong><?php echo $name; ?> </strong> <br><?php echo $specialnost; ?></br>
            </div>
        </a>
    </header>
    <div class="found_pages">

        <div class="content">

            <div class="tabs">
                <nav class="tabs__items">
                    <a href="#tab_01" class="tabs__item">ДАННЫЕ О ПАЦИЕНТЕ</a>
                    <a href="#tab_02" class="tabs__item">ЗАПИСИ ПАЦИЕНТА НА ПРИЁМ К ВРАЧУ</a>
                    <a href="#tab_03" class="tabs__item">ЛЕЧЕНИЕ ПАЦИЕНТА</a>
                </nav>
                <div class="tabs__body">

                    <div id="tab_01" class="tabs__block">
                        
                        <?php
                            $pdo = new mysqli('localhost','root','mysql','proekt');

                            // Проверка соединения
                            if ($pdo->connect_error) {
                                die("Connection failed: " . $pdo->connect_error);
                            }

                            // Получаем id пациента из параметра запроса
                            $patientId = $_GET['id_patient'];

                            // Запрос к базе данных для получения информации о пациенте по его ID

                            $sql = "SELECT * FROM patients WHERE id_patient = ?";
                            $stmt = $pdo->prepare($sql);
                            $stmt->bind_param("i", $patientId);
                            $stmt->execute();
                            $result = $stmt->get_result();

                            // Получение данных о пациенте
                            $patient = $result->fetch_assoc();

                            $pdo->close();
                        ?>
                        <div class="container_1">
                            <div class="block__inputs_patients">
                                <div class="info__block">
                                    <div class="info__label info__element">Вид:</div>
                                    <input id="vid_animal" class="info__input__patients info__element" type="text" value="<?php echo $patient["vid_animal"]; ?>">
                                </div>
                                <div class="info__block">
                                    <div class="info__label info__element">Кличка:</div>
                                    <input id="klichka" class="info__input__patients info__element" type="text" value="<?php echo $patient["klichka"]; ?>">
                                </div>
                                <div class="info__block">
                                    <div class="info__label info__element">Имя хозяина:</div>
                                    <input id="name_hozyaina" class="info__input__patients info__element" type="text" value="<?php echo $patient["name_hozyaina"]; ?>">
                                </div>
                                <div class="info__block">
                                    <div class="info__label info__element">Телефон:</div>
                                    <input id="number" class="info__input__patients info__element" type="text" value="<?php echo $patient["number"]; ?>">
                                </div>
                            </div>
                            <div class="block__inputs_patients">
                                <div class="info__block">
                                    <div class="info__label info__element">Порода:</div>
                                    <input id="poroda" class="info__input__patients info__element" type="text" value="<?php echo $patient["poroda"]; ?>">
                                </div>
                                <div class="info__block">
                                    <div class="info__label info__element">Фамилия хозяина:</div>
                                    <input id="familiya" class="info__input__patients info__element" type="text" value="<?php echo $patient["familiya"]; ?>">
                                </div>
                                <div class="info__block">
                                    <div class="info__label info__element">Отчество хозяина:</div>
                                    <input id="otchestvo" class="info__input__patients info__element" type="text" value="<?php echo $patient["otchestvo"]; ?>">
                                </div>
                                <div class="info__block">
                                    <div class="info__label info__element">Дата рождения питомца:</div>
                                    <input id="birthday" class="info__input__patients info__element" type="text" value="<?php echo $patient["birthday"]; ?>">
                                </div>
                            </div>
                        </div>

                    </div>

                    <div id="tab_02" class="tabs__block">   
                        <?php
                        $pdo = new mysqli('localhost','root','mysql','proekt');

                        // Проверка соединения
                        if ($pdo->connect_error) {
                            die("Connection failed: " . $pdo->connect_error);
                        }
                        ?>

                        <div class="container">
                        <div class="column">
                            <div class="rectangle">
                                ДАТА
                            </div>
                            <div class="date_input">
                                <?php
                                // Получаем id пациента из параметра запроса
                                $patientId = $_GET['id_patient'];

                                // SQL запрос для получения данных о дате
                                $sqlDate = "SELECT date FROM naznachenie_priema WHERE id_patient = $patientId";

                                // Выполнение запроса
                                $resultDate = $pdo->query($sqlDate);

                                // Проверка успешности выполнения запроса
                                if ($resultDate) {
                                    if ($resultDate->num_rows > 0) {
                                        // Получение результатов запроса
                                        while ($rowDate = $resultDate->fetch_assoc()) {
                                            echo "<div class='row'><div class='underline'>" . $rowDate['date'] . "</div></div>";
                                        }
                                    } else {
                                        echo "Пока приемов назначено не было";
                                    }
                                } else {
                                    echo "Ошибка выполнения запроса: " . $pdo->error;
                                }
                                ?>
                            </div>
                        </div>

                        <div class="divider"></div>

                        <div class="column">
                            <div class="rectangle">
                                ВРЕМЯ
                            </div>
                            <div class="date_input">
                                <?php
                                // Получаем id пациента из параметра запроса
                                $patientId = $_GET['id_patient'];

                                // SQL запрос для получения данных о времени
                                $sqlTime = "SELECT time FROM naznachenie_priema WHERE id_patient = $patientId";

                                // Выполнение запроса
                                $resultTime = $pdo->query($sqlTime);

                                // Проверка успешности выполнения запроса
                                if ($resultTime) {
                                    // Получение результатов запроса
                                    while ($rowTime = $resultTime->fetch_assoc()) {
                                        echo "<div class='row'><div class='underline'>" . $rowTime['time'] . "</div></div>";
                                    }
                                } else {
                                    echo "Ошибка выполнения запроса: " . $pdo->error;
                                }
                                ?>
                            </div>
                        </div>

                        <div class="divider"></div>

                        <div class="column">
                            <div class="rectangle">
                                СПЕЦИАЛИСТ
                            </div>
                            <div class="date_input">
                                <?php
                                    // Получаем id пациента из параметра запроса
                                    $patientId = $_GET['id_patient'];

                                    // SQL запрос для получения данных о докторе
                                    $sqlDoctor = "SELECT users.surname FROM naznachenie_priema
                                                JOIN users ON naznachenie_priema.id_doctor = users.id WHERE id_patient = $patientId";

                                    // Выполнение запроса
                                    $resultDoctor = $pdo->query($sqlDoctor);

                                    // Проверка успешности выполнения запроса
                                    if ($resultDoctor) {
                                        // Получение результатов запроса
                                        while ($rowDoctor = $resultDoctor->fetch_assoc()) {
                                            echo "<div class='row'><div class='underline'>" . $rowDoctor['surname'] . "</div></div>";
                                        }
                                    } else {
                                        echo "Ошибка выполнения запроса: " . $pdo->error;
                                    }
                                
                                ?>
                            </div>
                        </div>
                        </div>

                        <?php
                        // Закрытие соединения с базой данных
                        $pdo->close();
                        ?>

                    </div>
                    
                    <div id="tab_03" class="tabs__block">
                        <div class="container">
                            <div class="column">
                                <h2>ДИАГНОЗ</h2>                           
                                <!-- получение диагноза -->
                                <?php                                                        
                                    // Функция для получения диагноза из базы данных
                                    function getDiagnosisFromDatabase($patientId) {
                                        $pdo = new mysqli('localhost','root','mysql','proekt');

                                        // Проверка соединения
                                        if ($pdo->connect_error) {
                                            die("Connection failed: " . $pdo->connect_error);
                                        }

                                        // SQL запрос для получения диагноза
                                        $sql = "SELECT diagnoz FROM lechenie WHERE id_patient = ?";

                                        // Подготовленный запрос
                                        $stmt = $pdo->prepare($sql);
                                        $stmt->bind_param("i", $patientId);
                                        $stmt->execute();
                                        $result = $stmt->get_result();

                                        // Получение результата запроса
                                        $row = $result->fetch_assoc();

                                        // Закрытие соединения с базой данных
                                        $stmt->close();
                                        $pdo->close();

                                        // Возвращение диагноза (если он был найден)
                                        if ($row) {
                                            return $row['diagnoz'];
                                        } 
                                    }

                                    $pdo = new mysqli('localhost','root','mysql','proekt');

                                        // Проверка соединения
                                        if ($pdo->connect_error) {
                                            die("Connection failed: " . $pdo->connect_error);
                                        }

                                                                    
                                    // Получаем id пациента из параметра запроса                                  
                                    $patientId = $_GET['id_patient'];

                                    // Получение текущего диагноза из базы данных
                                    $currentDiagnosis = getDiagnosisFromDatabase($patientId);

                                    // Проверяем, была ли отправлена форма
                                    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
                                        // Получаем текущий диагноз пациента из формы
                                        $newDiagnosis = $_POST["diagnosis"];

                                        // Проверяем, существует ли запись о диагнозе для данного пациента
                                        $sql_check = "SELECT diagnoz FROM lechenie WHERE id_patient = ?";
                                        $stmt_check = $pdo->prepare($sql_check);
                                        $stmt_check->bind_param("i", $patientId);
                                        $stmt_check->execute();
                                        $result_check = $stmt_check->get_result();
                                        
                                        if ($result_check->num_rows > 0) {
                                            // получаем текущий диагноз
                                            $row_check = $result_check->fetch_assoc();
                                            $currentDiagnosis = $row_check['diagnoz'];
                                            
                                            // Проверяем, отличается ли новый диагноз от текущего
                                            if ($currentDiagnosis != $newDiagnosis) {
                                                // Если диагноз отличается, обновляем запись
                                                $sql_update = "UPDATE lechenie SET diagnoz = ? WHERE id_patient = ?";
                                                $stmt_update = $pdo->prepare($sql_update);
                                                $stmt_update->bind_param("si", $newDiagnosis, $patientId);
                                                $stmt_update->execute();
                                                $stmt_update->close();
                                                $currentDiagnosis = getDiagnosisFromDatabase($patientId);
                                                // Выводим сообщение об успешном обновлении
                                                echo "<script>alert('Диагноз успешно изменен!');window.location.href = 'http://localhost/proekt/patients_add_new.php?id_patient=$patientId#tab_03';</script>";
                                            } else {
                                                // Если диагноз не отличается, выводим сообщение об этом
                                                echo "<script>alert('Диагноз не был изменен!');window.location.href = 'http://localhost/proekt/patients_add_new.php?id_patient=$patientId#tab_03';</script>";
                                            }
                                        }
                                    }
                                ?>

                                <form id="diagnosisForm" method="POST" action="">
                                    <input type="hidden" name="id_patient" value="<?php echo $patientId; ?>">
                                    <textarea id="diagnosisInput" name="diagnosis" placeholder="Введите диагноз пациента"><?php echo $currentDiagnosis; ?></textarea>
                                    <p><button type="submit" name="submit">Изменить</button></p>
                                </form>

                            </div>
                            <div class="divider"></div>
                            <div class="column">   
                                 
                                <h2>МЕДИЦИНСКИЕ АНАЛИЗЫ</h2>
                                    <?php
                                        // Подключаемся к базе данных
                                        $pdo = new mysqli('localhost', 'root', 'mysql', 'proekt');

                                        // Проверяем соединение
                                        if ($pdo->connect_error) {
                                            die("Connection failed: " . $pdo->connect_error);
                                        }

                                        // Функция для получения списка файлов для конкретного пациента
                                        function getFilesForPatient($patientId, $pdo) {
                                            $sql = "SELECT * FROM med_analisy WHERE id_patient = '$patientId'";
                                            $result = $pdo->query($sql);
                                            $files = [];
                                            while ($row = $result->fetch_assoc()) {
                                                $files[] = $row;
                                            }
                                            return $files;
                                        }

                                        // Получаем id пациента из параметра запроса
                                        $id_patient = $_GET['id_patient'] ?? null;

                                        // Проверяем, был ли отправлен файл
                                        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['file']) && $id_patient) {
                                            $file = $_FILES['file'];

                                            // Получаем информацию о файле
                                            $fileName = $file['name'];
                                            $fileTmpName = $file['tmp_name'];
                                            $fileSize = $file['size'];
                                            $fileError = $file['error'];

                                            // Проверяем наличие ошибок при загрузке файла
                                            if ($fileError === UPLOAD_ERR_OK ) {
                                                // Перемещаем файл в папку назначения
                                                $destination = 'uploads/' . $fileName;
                                                if (move_uploaded_file($fileTmpName, $destination)) {
                                                    // Добавляем запись о файле в базу данных
                                                    $sql = "INSERT INTO med_analisy (id_patient, med_analisy) VALUES ('$id_patient', '$destination')";
                                                    if ($pdo->query($sql) === TRUE) {
                                                        // Показываем сообщение об успешной загрузке
                                                        echo "<script>alert('Файл успешно загружен');</script>";
                                                    } else {
                                                        echo "Ошибка при добавлении записи в базу данных: " . $pdo->error;
                                                    }
                                                } else {
                                                    echo "Ошибка при перемещении файла.";
                                                }
                                            } else {
                                                echo "Ошибка при загрузке файла.";
                                            }
                                        }
                                        
                                        // Получаем список файлов для данного пациента
                                        $files = getFilesForPatient($id_patient, $pdo);

                                        // Обрабатываем запрос на удаление файла
                                        if (isset($_GET['delete_id'])) {
                                            $deleteId = $_GET['delete_id'];
                                            
                                            $sql = "SELECT med_analisy FROM med_analisy WHERE id_med = $deleteId";
                                            $result = $pdo->query($sql);
                                            if ($result->num_rows > 0) {
                                                $row = $result->fetch_assoc();
                                                $filePath = $row['med_analisy'];
                                                // Удаляем файл с сервера
                                                if (unlink($filePath)) {
                                                    // Удаляем запись о файле из базы данных
                                                    $sql = "DELETE FROM med_analisy WHERE id_med = $deleteId";
                                                    if ($pdo->query($sql) === TRUE) {
                                                        // Показываем сообщение об успешном удалении и перенаправляем пользователя с помощью JavaScript
                                                        echo "<script>alert('Файл успешно удален'); window.location.href = 'http://localhost/proekt/patients_add_new.php?id_patient=$patientId#tab_03';</script>";
                                                        exit();
                                                    } else {
                                                        echo "Ошибка при удалении записи из базы данных: " . $pdo->error;
                                                    }
                                                } else {
                                                    echo "Ошибка при удалении файла с сервера.";
                                                }
                                            } else {
                                                echo "Файл не найден в базе данных.";
                                            }
                                        }

                                        // Получаем список файлов для данного пациента
                                        $files = $id_patient ? getFilesForPatient($id_patient, $pdo) : [];

                                        // Закрываем соединение с базой данных
                                        $pdo->close();
                                    ?>
                                <!-- Форма для загрузки файла -->
                                <form method="POST" enctype="multipart/form-data">
                                    <input type="file" name="file">
                                    <button id = "add_file_id" type="submit">Загрузить</button>
                                </form>

                                <div class="uploaded-files">
                                    <?php foreach ($files as $file): ?>
                                        <p>Файл загружен: <?php echo $file['med_analisy']; ?></p>
                                        <a href="<?php echo $file['med_analisy']; ?>" target="_blank">Открыть файл</a>
                                        <a href="?id_patient=<?php echo $id_patient; ?>&delete_id=<?php echo $file['id_med']; ?>">Удалить файл</a>
                                    <?php endforeach; ?>
                                </div>

                            </div>
                            <div class="divider"></div>
                            <div class="column">
                                <h2>НАЗНАЧЕНИЕ ЛЕЧЕНИЯ</h2>                             
                                <?php
                                    // Подключение к базе данных
                                    $pdo = new mysqli('localhost', 'root', 'mysql', 'proekt');

                                    // Проверяем соединение
                                    if ($pdo->connect_error) {
                                        die("Connection failed: " . $pdo->connect_error);
                                    }

                                    // Получаем данные из запроса
                                    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['doctorId']) && isset($_POST['notes'])) {
                                        // Получаем выбранного доктора и заметку из формы
                                        $doctorId = $_POST['doctorId'];
                                        $notes = $_POST['notes'];
                                        $patientId = $_GET['id_patient'];

                                        // Подготавливаем и выполняем SQL-запрос для вставки данных в таблицу
                                        $sql = "INSERT INTO naznachenie_lecheniya (id_patient, id_doctor, naz_lech) VALUES (?, ?, ?)";
                                        $stmt = $pdo->prepare($sql);
                                        $stmt->bind_param("iis", $patientId, $doctorId, $notes);
                                        if ($stmt->execute()) {
                                            echo "<script>alert('Заметка успешно добавлена'); window.location.href = 'http://localhost/proekt/patients_add_new.php?id_patient=$patientId#tab_03';</script>";
                                        } else {
                                            echo "Ошибка при добавлении данных в базу данных: " . $stmt->error;
                                        }

                                        // Закрываем соединение с базой данных
                                        $stmt->close();
                                    }

                                    // Обработка запроса на удаление заметки
                                    if (isset($_GET['delete_id_note'])) {
                                        $deleteId = $_GET['delete_id_note'];
                                        // Удаляем запись о заметке из базы данных
                                        $sql_delete = "DELETE FROM naznachenie_lecheniya WHERE id_naz = $deleteId";
                                        if ($pdo->query($sql_delete) === TRUE) {
                                            // Показываем сообщение об успешном удалении и перенаправляем пользователя обратно на страницу с помощью JavaScript
                                            echo "<script>alert('Заметка успешно удалена'); window.location.href = 'http://localhost/proekt/patients_add_new.php?id_patient=$patientId#tab_03';</script>";
                                            exit();
                                        } else {
                                            echo "Ошибка при удалении заметки из базы данных: " . $pdo->error;
                                        }
                                    }

                                    // SQL-запрос для получения списка заметок с их авторами
                                    $sql_notes = "SELECT naznachenie_lecheniya.*, users.name, users.surname, users.dolzhnost FROM naznachenie_lecheniya 
                                                    JOIN users ON naznachenie_lecheniya.id_doctor = users.id WHERE id_patient = $patientId";
                                    $result_notes = $pdo->query($sql_notes);
                                ?>

                                <!-- Форма для выбора врача и ввода заметки -->
                                <form id="appointmentForm" method="POST">
                                    <label for="doctorSelect">Выберите специалиста:</label><br>
                                    <select id="doctorSelect" name="doctorId">
                                        <?php
                                            // Заполнение выпадающего списка врачей
                                            $sql = "SELECT * FROM users WHERE login != 'admin'";
                                            $result = $pdo->query($sql);
                                            while ($row = $result->fetch_assoc()) {
                                                echo "<option value=\"{$row['id']}\">{$row['name']} {$row['surname']}, {$row['dolzhnost']}</option>";
                                            }
                                        ?>
                                    </select>
                                    <br>
                                    <label for="notes">Заметка:</label><br>
                                    <textarea id="notes" name="notes" placeholder="Введите заметку"></textarea><br>
                                    <button type="submit">Добавить</button>
                                </form>

                                <!-- Вывод списка заметок с их авторами -->
                                <div>
                                    <h2>Список заметок:</h2>
                                    <ul>
                                    <?php
                                        while ($row = $result_notes->fetch_assoc()) {
                                            echo "<li>{$row['naz_lech']} (Автор: {$row['name']} {$row['surname']}, {$row['dolzhnost']})";
                                            ?>
                                            <a href="?id_patient=<?php echo $patientId; ?>&delete_id_note=<?php echo $row['id_naz']; ?>">Удалить</a>
                                            <?php 
                                        }
                                    ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>                   
                </div>
            </div>
        </div>

</body>

</html>