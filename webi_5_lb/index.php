<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Панин Данила 241-351 ЛР А-5 Вариант 20</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>

<header>
    <div class="header-inner">
        <img src="img/logo.png" alt="Логотип университета">
        <div class="header-text">
            <div class="line1">Панин Д О · 241-351 · Лабораторная работа № А-5 · Вариант 20</div>
            <div class="line2">Динамическое формирование контента и меню. Таблица умножения.</div>
        </div>
    </div>

    <!-- Главное меню: выбор типа верстки -->
    <nav id="main_menu">
        <?php
        // Формируем базовую часть ссылки с content, если он есть
        $contentParam = '';
        if (isset($_GET['content'])) {
            $contentParam = '&content=' . $_GET['content'];
        }

        // Ссылка "Табличная верстка"
        echo '<a href="?html_type=TABLE' . $contentParam . '"';
        if (isset($_GET['html_type']) && $_GET['html_type'] == 'TABLE') {
            echo ' class="selected"';
        }
        echo '>Табличная верстка</a>';

        // Ссылка "Блочная верстка"
        echo '<a href="?html_type=DIV' . $contentParam . '"';
        if (isset($_GET['html_type']) && $_GET['html_type'] == 'DIV') {
            echo ' class="selected"';
        }
        echo '>Блочная верстка</a>';
        ?>
    </nav>
</header>

<main class="main-container">
    <!-- Основное меню: выбор столбца таблицы умножения -->
    <aside id="product_menu">
        <?php
        // Формируем базовую часть ссылки с html_type, если он есть
        $baseLink = '?';
        if (isset($_GET['html_type'])) {
            $baseLink .= 'html_type=' . $_GET['html_type'] . '&';
        }

        // Ссылка "Всё" (выделена по умолчанию при первой загрузке)
        $allLink = isset($_GET['html_type']) ? '?html_type=' . $_GET['html_type'] : '/';
        echo '<a href="' . $allLink . '"';
        if (!isset($_GET['content'])) {
            echo ' class="selected"';
        }
        echo '>Всё</a>';

        // Ссылки 2-9
        for ($i = 2; $i <= 9; $i++) {
            echo '<a href="' . $baseLink . 'content=' . $i . '"';
            if (isset($_GET['content']) && $_GET['content'] == $i) {
                echo ' class="selected"';
            }
            echo '>' . $i . '</a>';
        }
        ?>
    </aside>

    <!-- Основная область с таблицей -->
    <section class="table-area">
        <h1>Таблица умножения</h1>

        <?php
        /**
         * Функция: преобразует число в ссылку, если это одна цифра (0-9)
         * Возвращает строку (не выводит сразу!)
         * Ссылки НЕ передают html_type — "сбрасывают" тип верстки
         */
        function outNumAsLink($x) {
            if (is_numeric($x) && $x >= 0 && $x <= 9) {
                return '<a href="?content=' . $x . '">' . $x . '</a>';
            }
            return (string)$x;
        }

        /**
         * Функция: выводит ОДНУ СТРОКУ таблицы умножения для табличной верстки
         * $row — текущий множитель (2-9)
         */
        function outTableRow($row) {
            echo '<tr>';
            for ($col = 2; $col <= 9; $col++) {
                echo '<td>' . outNumAsLink($col) . '×' . outNumAsLink($row) . '=' . outNumAsLink($col * $row) . '</td>';
            }
            echo '</tr>';
        }

        /**
         * Функция: выводит столбец таблицы умножения для числа $n
         * Используется для блочной верстки и для одиночного столбца
         */
        function outRow($n) {
            for ($i = 2; $i <= 9; $i++) {
                echo outNumAsLink($n) . '×' . outNumAsLink($i) . '=' . outNumAsLink($i * $n) . '<br>';
            }
        }

        /**
         * Функция: вывод таблицы в ТАБЛИЧНОЙ форме (TABLE)
         * Правильная сетка с <tr> и <td>
         */
        function outTableForm() {
            echo '<table class="tableD">';

            if (!isset($_GET['content'])) {
                // === ВСЯ ТАБЛИЦА: 8 колонок × 8 строк ===
                // Заголовки
                echo '<thead ><tr>';
                for ($h = 2; $h <= 9; $h++) {
                    echo '<th class="thead_vse">' . outNumAsLink($h) . '</th>';
                }
                echo '</tr></thead><tbody>';

                // Тело таблицы: каждая строка — умножение на $row
                for ($row = 2; $row <= 9; $row++) {
                    outTableRow($row);
                }
                echo '</tbody>';

            } else {
                // === ОДИН СТОЛБЕЦ: каждая строка в отдельном <tr> ===
                echo '<tbody>';
                for ($i = 2; $i <= 9; $i++) {
                    echo '<tr><td class="single-cell">';
                    echo outNumAsLink($_GET['content']) . '×' . outNumAsLink($i) . '=' . outNumAsLink($i * $_GET['content']);
                    echo '</td></tr>';
                }
                echo '</tbody>';
            }

            echo '</table>';
        }

        /**
         * Функция: вывод таблицы в БЛОЧНОЙ форме (DIV)
         * Блоки с float:left
         */
        function outDivForm() {
            if (!isset($_GET['content'])) {
                // === ВСЯ ТАБЛИЦА: 8 блоков ===
                echo '<div class="blocksE">';
                for ($col = 2; $col <= 9; $col++) {
                    echo '<div class="blockE">';
                    outRow($col);
                    echo '</div>';
                }
                echo '</div>';
            } else {
                // === ОДИН СТОЛБЕЦ: крупный блок ===
                echo '<div class="blocksE"><div class="blockE single-column">';
                outRow($_GET['content']);
                echo '</div></div>';
            }
        }

        // Выбор типа вывода в зависимости от параметра html_type
        if (!isset($_GET['html_type']) || $_GET['html_type'] == 'TABLE') {
            outTableForm();
        } else {
            outDivForm();
        }
        ?>
    </section>
</main>

<footer>
    <div class="footer-inner">
        <div>Панин Данила 241-351</div>
        <div>
            <?php
            // Формируем информацию для футера
            if (!isset($_GET['html_type']) || $_GET['html_type'] == 'TABLE') {
                $s = 'Табличная верстка. ';
            } else {
                $s = 'Блочная верстка. ';
            }

            if (!isset($_GET['content'])) {
                $s .= 'Таблица умножения полностью. ';
            } else {
                $s .= 'Столбец таблицы умножения на ' . $_GET['content'] . '. ';
            }

            echo $s . date('d.M.Y H:i:s');
            ?>
        </div>
    </div>
</footer>

</body>
</html>