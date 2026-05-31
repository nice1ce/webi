<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Панин Данила 241-351 ЛР А-4 Вариант 20</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>

<header>
    <div class="header-inner">
        <img src="img/logo.png" alt="Логотип университета">
        <div class="header-text">
            <div class="line1">Панин Д О · 241-351 · Лабораторная работа № А-4 · Вариант 20</div>
            <div class="line2">Пользовательские функции. Вывод таблиц</div>
        </div>
    </div>
</header>

<main>
    <h1>Вывод таблиц с использованием пользовательских функций</h1>

    <?php
    $numColumns = 3; // Число колонок

    $structures = array(
            'Это*четвертая*лабораторная#работа*по*разработке#веб-приложений*.*',
            'Панин*Данила*241-351',
            'Тест*Данные#Результат*Значение',
            '***#***#***',
            'Финальная*Таблица*Проверка'
    );


    function getTR($data, $numCols) {
        $arr = explode('*', $data); // Разбиваем строку по разделителю колонок

        // Проверяем, есть ли в строке хоть какие-то данные
        $hasContent = false;
        foreach($arr as $cell) {
            if(trim($cell) !== '') {
                $hasContent = true;
                break;
            }
        }
        // Если строка полностью пустая - возвращаем пустую строку
        if(!$hasContent && count($arr) === 1 && trim($arr[0]) === '') {
            return '';
        }

        $ret = '<tr>'; // Начинаем тег строки таблицы

        // Цикл по требуемому числу колонок
        for($i = 0; $i < $numCols; $i++) {
            if(isset($arr[$i]) && $arr[$i] !== '') {
                $ret .= '<td>' . htmlspecialchars($arr[$i]) . '</td>';
            } else {
                $ret .= '<td></td>'; // Пустая ячейка для соблюдения числа колонок
            }
        }
        return $ret . '</tr>'; // Возвращаем готовую строку таблицы
    }

    function outTable($structure, $numCols, $tableNum) {
        // Проверка на корректное число колонок
        if($numCols <= 0) {
            echo '<h2>Таблица №' . $tableNum . '</h2>';
            echo '<div class="message error">Неправильное число колонок</div>';
            return;
        }

        // Разбиваем структуру на отдельные строки по разделителю "#"
        $strings = explode('#', $structure);
        $datas = ''; // Накопитель HTML-кода строк
        $hasRowsWithCells = false; // Флаг наличия строк с данными

        // Обрабатываем каждую строку структуры
        for($i = 0; $i < count($strings); $i++) {
            $rowHtml = getTR($strings[$i], $numCols);
            if($rowHtml !== '') {
                $datas .= $rowHtml;
                $hasRowsWithCells = true;
            }
        }

        // Выводим заголовок таблицы
        echo '<h2>Таблица №' . $tableNum . '</h2>';

        // Проверка: есть ли строки в структуре
        if(count($strings) === 0 || (count($strings) === 1 && $strings[0] === '')) {
            echo '<div class="message warning">В таблице нет строк</div>';
            return;
        }

        // Проверка: есть ли строки с ячейками
        if(!$hasRowsWithCells) {
            echo '<div class="message warning">В таблице нет строк с ячейками</div>';
            return;
        }

        // Если есть данные - выводим таблицу
        if($datas !== '') {
            echo '<table class="tableD">' . $datas . '</table>';
        }
    }

    for($i = 0; $i < count($structures); $i++) {
        // Проверка для пятой таблицы: устанавливаем 0 колонок
        if($i == count($structures) - 1) {
            static $numColumns;
            $numColumns = 0;
        }

        outTable($structures[$i], $numColumns, $i + 1);
    }
    ?>
</main>

<footer>
    <div class="footer-inner">
        <div>Панин Данила 241-351</div>
        <div>Лабораторная работа № А-4</div>
    </div>
</footer>

</body>
</html>