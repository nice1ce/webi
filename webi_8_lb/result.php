<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Панин Данила 241-351 ЛР А-8 Вариант 20 - Результат</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>

<header>
    <div class="header-inner">
        <img src="img/logo.png" alt="Логотип университета">
        <div class="header-text">
            <div class="line1">Панин Д О · 241-351 · Лабораторная работа № А-8 · Вариант 20</div>
            <div class="line2">Основы работы со строковыми данными в PHP. Кодировка. Анализ текста.</div>
        </div>
    </div>
</header>

<main>
    <h1>Результат анализа текста</h1>

    <?php
    $text_raw = isset($_POST['data']) ? $_POST['data'] : '';

    if (trim($text_raw) !== '') {
        // Вывод исходного текста (цвет + курсив по заданию)
        echo '<div class="src_text">' . htmlspecialchars($text_raw) . '</div>';

        // Перекодировка согласно методичке (UTF-8 -> CP1251 для побайтовой обработки)
        $text = iconv("UTF-8", "CP1251//IGNORE", $text_raw);
        $len = strlen($text);

        // Инициализация счетчиков
        $letters = 0; $upper = 0; $lower = 0; $digits = 0; $punct = 0;
        $symbs = [];
        $words = [];
        $current_word = '';
        // Набор знаков препинания
        $punct_chars = '.,!?;:()[]{}\'"\"-—–';

        // Основной цикл анализа
        for ($i = 0; $i < $len; $i++) {
            $char = $text[$i];
            $char_lower = strtolower($char);

            // 1. Подсчет категорий символов
            if (ctype_alpha($char)) {
                $letters++;
                if (ctype_upper($char)) $upper++;
                else $lower++;
            } elseif (ctype_digit($char)) {
                $digits++;
            } elseif (strpos($punct_chars, $char) !== false) {
                $punct++;
            }

            // 2. Частота символов (без учета регистра, пробелы не считаем)
            if (!ctype_space($char)) {
                $symbs[$char_lower] = ($symbs[$char_lower] ?? 0) + 1;
            }

            // 3. Разбиение на слова (пробел или знак препинания = разделитель)
            $is_delim = ctype_space($char) || strpos($punct_chars, $char) !== false;
            if ($is_delim) {
                if ($current_word !== '') {
                    $words[$current_word] = ($words[$current_word] ?? 0) + 1;
                    $current_word = '';
                }
            } else {
                $current_word .= $char;
            }
        }
        // Обработка последнего слова, если текст не заканчивается разделителем
        if ($current_word !== '') {
            $words[$current_word] = ($words[$current_word] ?? 0) + 1;
        }

        // Сортировка массивов по ключам (алфавитный порядок)
        ksort($words);
        ksort($symbs);

        // --- Вывод таблиц ---
        echo '<h2>Информация о тексте</h2>';
        echo '<table class="tableD">';
        echo '<tr><th>Параметр</th><th>Значение</th></tr>';
        echo "<tr><td>Количество символов (включая пробелы)</td><td>{$len}</td></tr>";
        echo "<tr><td>Количество букв</td><td>{$letters}</td></tr>";
        echo "<tr><td>Заглавные буквы</td><td>{$upper}</td></tr>";
        echo "<tr><td>Строчные буквы</td><td>{$lower}</td></tr>";
        echo "<tr><td>Знаки препинания</td><td>{$punct}</td></tr>";
        echo "<tr><td>Цифры</td><td>{$digits}</td></tr>";
        echo "<tr><td>Количество слов</td><td>" . count($words) . "</td></tr>";
        echo '</table>';

        echo '<h2>Вхождения каждого символа</h2>';
        echo '<table class="tableD"><tr><th>Символ</th><th>Вхождений</th></tr>';
        foreach ($symbs as $char => $count) {
            $char_utf = iconv("CP1251", "UTF-8//IGNORE", $char);
            echo "<tr><td>{$char_utf}</td><td>{$count}</td></tr>";
        }
        echo '</table>';

        echo '<h2>Список слов и количество вхождений</h2>';
        echo '<table class="tableD"><tr><th>Слово</th><th>Вхождений</th></tr>';
        foreach ($words as $word => $count) {
            $word_utf = iconv("CP1251", "UTF-8//IGNORE", $word);
            echo "<tr><td>{$word_utf}</td><td>{$count}</td></tr>";
        }
        echo '</table>';

    } else {
        echo '<div class="src_error">Нет текста для анализа</div>';
    }
    ?>

    <br>
    <a href="index.html" class="btn-back">Другой анализ</a>
</main>

<footer>
    <div class="footer-inner">
        <div>Панин Данила 241-351</div>
    </div>
</footer>

</body>
</html>