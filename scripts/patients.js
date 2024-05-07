
document.addEventListener("DOMContentLoaded", function () {
    // Получаем все элементы ссылок меню
    var menuItems = document.querySelectorAll('.nav__item nav-link a');

    // Добавляем обработчик события клика для каждого пункта меню
    menuItems.forEach(function (item) {
        item.addEventListener('click', function () {
            // Удаляем класс 'active' у всех пунктов меню
            menuItems.forEach(function (item) {
                item.classList.remove('active');
            });

            // Добавляем класс 'active' к выбранному пункту меню
            this.classList.add('active');
        });
    });
});


