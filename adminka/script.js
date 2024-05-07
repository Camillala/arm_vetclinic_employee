function toggleContent(id) {
    var content = document.getElementById(id);
    content.classList.toggle('hidden');
}

function toggleContent(id) {
    var allContents = document.querySelectorAll('.tab-content');
    
    // Скрыть все блоки контента
    allContents.forEach(function(content) {
        content.classList.add('hidden');
    });

    // Показать выбранный блок контента
    var selectedContent = document.getElementById(id);
    selectedContent.classList.remove('hidden');
}