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
                $email = $row["email"];
                $photo = $row["photo"];
                $name = $row["name"];
                $specialnost = $row["specialnost"];
                $dolzhnost = $row["dolzhnost"];
            }
        }
        
        $pdo->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Расписание</title>
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
            <!-- Изображение будет с БД, просто не стала загружать белый круг, пока поставила logo -->
            <img class="avatar" src="<?php echo $photo; ?>" alt="">
            <div class="user__name">
                <strong><?php echo $name; ?> </strong> <br><?php echo $specialnost; ?></br>
            </div>
        </a>
    </header>

    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5/main.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5/main.js"></script>
    
    <!-- Контейнер для календаря -->
    <div id="calendarContainer">
        <div id="calendar"></div>
    </div>
    <!-- Модальное окно для отображения информации о клиентах -->
    <div id="clientInfoModal" style="display: none;">
        <div id="clientInfo"></div>
        <button onclick="closeClientInfoModal()">Закрыть</button>
    </div>

    <?php
        $pdo = new mysqli('localhost','root','mysql','proekt');

        // Проверка соединения
        if ($pdo->connect_error) {
            die("Connection failed: " . $pdo->connect_error);
        }

        // SQL запрос для выборки данных о дате и времени из таблицы
        $sql = "SELECT np.date, np.time, p.name_hozyaina
            FROM naznachenie_priema AS np
            JOIN patients AS p ON np.id_patient = p.id_patient WHERE np.id_doctor = $currentUserId";

        // Выполнение запроса
        $result = $pdo->query($sql);

        // Массив для хранения событий
        $events = [];

        while ($row = $result->fetch_assoc()) {
        
            // Форматирование даты для использования в календаре
            $formattedDate = date('Y-m-d', strtotime($row['date']));
            
            // Использование времени из базы данных без преобразования
            $formattedTime = $row['time'];
            
            // Создание события и добавление его в массив
            $event = [
                'title' => $row['name_hozyaina'], // Название события
                'start' => $formattedDate . 'T' . $formattedTime, // Дата и время начала события
            ];
            $events[] = $event;
        }

        // Закрытие соединения с базой данных
        $pdo->close();

        // Преобразование массива событий в JSON формат
        $eventsJSON = json_encode($events);
    ?>

<script>
        document.addEventListener('DOMContentLoaded', function () {
            var calendarEl = document.getElementById('calendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: <?php echo $eventsJSON; ?>,
                dateClick: function (info) {
                    showClientInfo(info.dateStr);
                }
            });

            calendar.render();

        });
</script>

</body>

</html>