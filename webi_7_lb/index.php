<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Панин Данила 241-351 ЛР А-7 Вариант 20</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>

<header>
    <div class="header-inner">
        <img src="img/logo.png" alt="Логотип университета">
        <div class="header-text">
            <div class="line1">Панин Д О · 241-351 · Лабораторная работа № А-7 · Вариант 20</div>
            <div class="line2">Основы использования массивов в программировании. Ввод данных и сортировка массивов</div>
        </div>
    </div>
</header>

<main>
    <h1>Сортировка массива</h1>
    <p class="code-note">Введите элементы массива и выберите алгоритм сортировки</p>

    <form method="POST" action="sort.php" target="_blank">
        <table id="elements" class="tableD">
            <tr>
                <th style="width: 60px;">№</th>
                <th>Значение элемента</th>
            </tr>
            <tr>
                <td class="element_index">0</td>
                <td class="element_row"><input type="text" name="element0" required></td>
            </tr>
        </table>
        
        <input type="hidden" id="arrLength" name="arrLength" value="1">
        
        <div class="blocksE">
            <div class="blockE">
                <label for="algorithm">Алгоритм сортировки:</label><br>
                <select name="algorithm" id="algorithm" required>
                    <option value="selection">Сортировка выбором</option>
                    <option value="bubble">Пузырьковый алгоритм</option>
                    <option value="shell">Алгоритм Шелла</option>
                    <option value="gnome">Алгоритм садового гнома</option>
                    <option value="quick">Быстрая сортировка</option>
                    <option value="php_builtin">Встроенная функция PHP</option>
                </select>
            </div>
            <div class="blockE">
                <input type="button" value="Добавить элемент" onClick="addElement('elements', 1);">
            </div>
            <div class="blockE">
                <input type="submit" value="Сортировать массив">
            </div>
        </div>
    </form>
</main>

<footer>
    <div class="footer-inner">
        <div>Панин Данила 241-351</div>
    </div>
</footer>

<script>
// Функция добавления нового элемента массива
function addElement(table_name, amount) {
    var t = document.getElementById(table_name);
    var startIndex = t.rows.length - 1; // -1 т.к. первая строка - заголовок
    
    for(var i = 0; i < amount; i++) {
        var index = t.rows.length;
        var row = t.insertRow(index);
        
        // Ячейка с номером элемента
        var celIndex = row.insertCell(0);
        celIndex.className = 'element_index';
        celIndex.textContent = index - 1;
        
        // Ячейка с полем ввода
        var cel = row.insertCell(1);
        cel.className = 'element_row';
        var celcontent = '<input type="text" name="element' + (index - 1) + '" required>';
        setHTML(cel, celcontent);
    }
    
    // Обновляем скрытое поле с длиной массива
    document.getElementById('arrLength').value = t.rows.length - 1;
}

// Кросс-браузерная функция установки innerHTML
function setHTML(element, txt) {
    if(element.innerHTML !== undefined) {
        element.innerHTML = txt;
    } else {
        var range = document.createRange();
        range.selectNodeContents(element);
        range.deleteContents();
        var fragment = range.createContextualFragment(txt);
        element.appendChild(fragment);
    }
}
</script>

</body>
</html>