<?php
// Функция проверки: возвращает true, если аргумент НЕ число
function arg_is_not_Num($arg) {
    if($arg === '') return true;
    // Разрешаем отрицательные числа и десятичные
    if(is_numeric($arg)) return false;
    return true;
}

// === АЛГОРИТМЫ СОРТИРОВКИ С ПОДСЧЁТОМ ИТЕРАЦИЙ ===

// Сортировка выбором
function sorting_by_choice(&$arr, &$iterations) {
    $n = count($arr);
    for($i = 0; $i < $n - 1; $i++) {
        $min = $i;
        for($j = $i + 1; $j < $n; $j++) {
            $iterations++;
            echo "<div class='iteration'>Итерация #$iterations: [" . implode(', ', $arr) . "]</div>\n";
            if($arr[$j] < $arr[$min]) {
                $min = $j;
            }
        }
        if($min != $i) {
            $temp = $arr[$i];
            $arr[$i] = $arr[$min];
            $arr[$min] = $temp;
        }
    }
    return $arr;
}

// Пузырьковая сортировка
function bubble_sort(&$arr, &$iterations) {
    $n = count($arr);
    for($i = 0; $i < $n - 1; $i++) {
        for($j = 0; $j < $n - 1 - $i; $j++) {
            $iterations++;
            echo "<div class='iteration'>Итерация #$iterations: [" . implode(', ', $arr) . "]</div>\n";
            if($arr[$j] > $arr[$j + 1]) {
                $temp = $arr[$j];
                $arr[$j] = $arr[$j + 1];
                $arr[$j + 1] = $temp;
            }
        }
    }
    return $arr;
}

// Сортировка Шелла
function shell_sort(&$arr, &$iterations) {
    $n = count($arr);
    for($gap = floor($n / 2); $gap >= 1; $gap = floor($gap / 2)) {
        for($i = $gap; $i < $n; $i++) {
            $temp = $arr[$i];
            $j = $i;
            while($j >= $gap && $arr[$j - $gap] > $temp) {
                $iterations++;
                echo "<div class='iteration'>Итерация #$iterations: [" . implode(', ', $arr) . "]</div>\n";
                $arr[$j] = $arr[$j - $gap];
                $j -= $gap;
            }
            $arr[$j] = $temp;
        }
    }
    return $arr;
}

// Сортировка садового гнома
function gnome_sort(&$arr, &$iterations) {
    $i = 1;
    $n = count($arr);
    while($i < $n) {
        $iterations++;
        echo "<div class='iteration'>Итерация #$iterations: [" . implode(', ', $arr) . "]</div>\n";
        if($i == 0 || $arr[$i - 1] <= $arr[$i]) {
            $i++;
        } else {
            $temp = $arr[$i];
            $arr[$i] = $arr[$i - 1];
            $arr[$i - 1] = $temp;
            $i--;
        }
    }
    return $arr;
}

// Быстрая сортировка (вспомогательная)
function quick_sort_helper(&$arr, $left, $right, &$iterations) {
    if($left >= $right) return;
    
    $pivot = $arr[floor(($left + $right) / 2)];
    $i = $left;
    $j = $right;
    
    while($i <= $j) {
        while($arr[$i] < $pivot) $i++;
        while($arr[$j] > $pivot) $j--;
        if($i <= $j) {
            $iterations++;
            echo "<div class='iteration'>Итерация #$iterations: [" . implode(', ', $arr) . "]</div>\n";
            $temp = $arr[$i];
            $arr[$i] = $arr[$j];
            $arr[$j] = $temp;
            $i++;
            $j--;
        }
    }
    
    if($left < $j) quick_sort_helper($arr, $left, $j, $iterations);
    if($i < $right) quick_sort_helper($arr, $i, $right, $iterations);
}

// Быстрая сортировка (основная)
function quick_sort(&$arr, &$iterations) {
    quick_sort_helper($arr, 0, count($arr) - 1, $iterations);
    return $arr;
}

// === ОСНОВНАЯ ЛОГИКА ОБРАБОТКИ ===

// Проверка наличия данных
if(!isset($_POST['element0']) || empty($_POST['element0'])) {
    echo '<!doctype html><html lang="ru"><head><meta charset="utf-8"><title>Ошибка</title><link rel="stylesheet" href="/css/styles.css"></head><body>';
    echo '<main><h1> Ошибка</h1><p>Массив не задан, сортировка невозможна</p><a href="/index.php">← Вернуться</a></main></body></html>';
    exit();
}

// Валидация: проверка всех элементов на числа
$arrLength = (int)$_POST['arrLength'];
for($i = 0; $i < $arrLength; $i++) {
    if(isset($_POST['element'.$i]) && arg_is_not_Num($_POST['element'.$i])) {
        echo '<!doctype html><html lang="ru"><head><meta charset="utf-8"><title>Ошибка</title><link rel="stylesheet" href="/css/styles.css"></head><body>';
        echo '<main><h1> Ошибка валидации</h1><p>Элемент массива "'.$_POST['element'.$i].'" – не является числом</p><a href="/index.php">← Вернуться</a></main></body></html>';
        exit();
    }
}

// Получение названия алгоритма
$algorithms = [
    'selection' => 'Сортировка выбором',
    'bubble' => 'Пузырьковый алгоритм',
    'shell' => 'Алгоритм Шелла',
    'gnome' => 'Алгоритм садового гнома',
    'quick' => 'Быстрая сортировка',
    'php_builtin' => 'Встроенная функция PHP'
];
$algo_key = $_POST['algorithm'] ?? 'selection';
$algo_name = $algorithms[$algo_key] ?? 'Неизвестный алгоритм';

// Подготовка массива для сортировки
$original_arr = [];
for($i = 0; $i < $arrLength; $i++) {
    if(isset($_POST['element'.$i])) {
        $original_arr[] = floatval($_POST['element'.$i]);
    }
}
$arr = $original_arr; // копия для сортировки

// Начало вывода страницы
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Результат сортировки - Панин Д.О. 241-351</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>

<header>
    <div class="header-inner">
        <img src="/img/logo.png" alt="Логотип">
        <div class="header-text">
            <div class="line1">Панин Д О · 241-351 · Лабораторная работа № А-7 · Вариант 20</div>
            <div class="line2">Отчёт о сортировке массива</div>
        </div>
    </div>
</header>

<main>
    <h1>Отчёт о сортировке</h1>
    
    <div class="stats">
        <p><strong>Тип задачи:</strong> Сортировка одномерного массива</p>
        <p><strong>Алгоритм: </strong> <?= htmlspecialchars($algo_name) ?></p>
    </div>

    <h2> Входные данные</h2>
    <div class="blocksE">
        <?php foreach($original_arr as $idx => $val): ?>
            <div class="blockE"><strong>[<?= $idx ?>]</strong> <?= htmlspecialchars($val) ?></div>
        <?php endforeach; ?>
    </div>

    <h2> Валидация</h2>
    <p class="code-note">Все элементы массива являются числами — валидация пройдена.</p>

    <h2> Алгоритм: <?= htmlspecialchars($algo_name) ?></h2>
    <div class="iteration-log">
        <h3>Ход выполнения:</h3>
        <?php
        $iterations = 0;
        $start_time = microtime(true);
        
        // Запуск выбранного алгоритма
        switch($algo_key) {
            case 'selection':
                $arr = sorting_by_choice($arr, $iterations);
                break;
            case 'bubble':
                $arr = bubble_sort($arr, $iterations);
                break;
            case 'shell':
                $arr = shell_sort($arr, $iterations);
                break;
            case 'gnome':
                $arr = gnome_sort($arr, $iterations);
                break;
            case 'quick':
                $arr = quick_sort($arr, $iterations);
                break;
            case 'php_builtin':
                // Встроенная сортировка без подсчёта итераций
                sort($arr, SORT_NUMERIC);
                $iterations = count($arr) * log(count($arr), 2); // приблизительная оценка
                echo "<div class='iteration'>Итерации не отслеживаются для встроенной функции PHP</div>\n";
                break;
        }
        
        $end_time = microtime(true);
        $duration = $end_time - $start_time;
        ?>
    </div>

    <h2> Результат сортировки</h2>
    <div class="blocksE">
        <?php foreach($arr as $idx => $val): ?>
            <div class="blockE sorted"><strong>[<?= $idx ?>]</strong> <?= htmlspecialchars($val) ?></div>
        <?php endforeach; ?>
    </div>

    <div class="stats">
        <h3> Статистика выполнения</h3>
        <p><strong>Проведено итераций:</strong> <?= $iterations ?></p>
        <p><strong>Время выполнения:</strong> <?= number_format($duration * 1000, 3) ?> мс</p>
    </div>

    <h2> Проверка результата</h2>
    <?php
    $is_sorted = true;
    for($i = 0; $i < count($arr) - 1; $i++) {
        if($arr[$i] > $arr[$i + 1]) {
            $is_sorted = false;
            break;
        }
    }
    ?>
    <p class="code-note <?= $is_sorted ? 'success' : 'error' ?>">
        <?= $is_sorted ? ' Тест пройден: массив отсортирован корректно' : ' Ошибка: тест не пройден' ?>
    </p>

    <p style="margin-top: 20px;">
        <a href="/index.php" target="_parent">← Вернуться к форме</a>
    </p>
</main>

<footer>
    <div class="footer-inner">
        <div>Панин Данила 241-351</div>
    </div>
</footer>

</body>
</html>