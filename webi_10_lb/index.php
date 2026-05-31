<?php
session_start();

// Инициализация сессии при первом визите
if (!isset($_SESSION['history'])) {
    $_SESSION['history'] = [];
    $_SESSION['iteration'] = 1; // Начинаем с 1, чтобы форма сразу получила валидное значение
}

$res = null;
$calc_done = false;

// Обработка формы (защита от F5)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['val'])) {
    // POST-итерация должна ТОЧНО совпадать с текущей итерацией сессии
    if ((int)($_POST['iteration'] ?? 0) === (int)($_SESSION['iteration'] ?? 0)) {
        $res = calculateSq($_POST['val']);
        $calc_done = true;
        $_SESSION['history'][] = htmlspecialchars($_POST['val']) . ' = ' . $res;
    } else {
        $res = 'Обновление страницы (F5) не добавляет повторную запись в историю.';
        $calc_done = false;
    }
    // Увеличиваем итерацию ПОСЛЕ обработки, для следующего запроса
    $_SESSION['iteration']++;
}

// --- ВСПОМОГАТЕЛЬНЫЕ ФУНКЦИИ ---

function isnum($x) { // Проверка на число
    $x = (string)$x; // Защита от Warning в PHP 8+ при передаче числа
    if ($x === '') return false;  // проверка на пустую строку
    if ($x[0] === '.' || ($x[0] === '0' && strlen($x) > 1)) return false;  // начинается . или 0(не только 0), то не подходит
    if (substr($x, -1) === '.') return false;  // число не заканчивается на точку
    
    $points = 0; //счетчик точек
    for ($i = 0; $i < strlen($x); $i++) {
        $c = $x[$i];
        if ($c === '.') { // считаем точки
            $points++;
            if ($points > 1) return false;  // если точек больше 2 – не число
        } elseif ($c < '0' || $c > '9') { // проверка на не вхождение символа в цифры от 0 до 9, точку обрабатываем в if
            return false;
        }
    }
    return true;
}


function calculate($val) {
    $val = str_replace(' ', '', $val);
    // строгая проверка на пустую строку, чтобы 0 не считался false
    if ($val === '') return 'Выражение не задано!';
    if (isnum($val)) return $val; // если число, верни значение

    // Сложение (самый низкий приоритет → проверяется первым)
    if (strpos($val, '+') !== false) {
        $parts = explode('+', $val); // разбивка по плюсу
        $sum = 0;
        foreach ($parts as $p) { //перебор всех слагаемых
            $r = calculate($p); // рекурсия
            if (!isnum($r)) return $r; // проверка на число, при ошибке возвращаем это не число
            $sum += (float)$r; // прибавка к сумме
        }
        return is_int($sum) ? (int)$sum : $sum; // убираем .0 из флоата
    }
    
    // Вычитание
    if (strpos($val, '-') !== false) {
        $parts = explode('-', $val);
        $diff = calculate($parts[0]); // получаем уменьшаемое
        if (!isnum($diff)) return $diff; // возварщаем ошибку, если не число
        for ($i = 1; $i < count($parts); $i++) { // счетчик с 1 т.к. первый жлемент записали в diff
            $r = calculate($parts[$i]); //считаем вычитаемое
            if (!isnum($r)) return $r; // возврат ошибки
            $diff -= (float)$r; // вычитаем
        }
        return is_int($diff) ? (int)$diff : $diff; // убираем .0 из флоата
    }
    
    // Умножение
    if (strpos($val, '*') !== false) {
        $parts = explode('*', $val);
        $prod = 1;
        foreach ($parts as $p) {
            $r = calculate($p); // рекурсия
            if (!isnum($r)) return $r; // ошибка
            $prod *= (float)$r; // умножаем
        }
        return is_int($prod) ? (int)$prod : $prod; // убираем .0 из флоата
    }
    
    // Деление (/ или :)
    if (strpos($val, '/') !== false || strpos($val, ':') !== false) {
        $val = str_replace(':', '/', $val);
        $parts = explode('/', $val);
        $div = calculate($parts[0]);
        if (!isnum($div)) return $div;
        for ($i = 1; $i < count($parts); $i++) {
            $r = calculate($parts[$i]);
            if (!isnum($r)) return $r;
            if ($r == 0) return 'Деление на ноль!';
            $div /= (float)$r;
        }
        return is_int($div) ? (int)$div : $div; // убираем .0 из флоата
    }
    
    return 'Недопустимые символы!';
}

function SqValidator($val) { // валидация скобок
    $open = 0;
    for ($i = 0; $i < strlen($val); $i++) {
        if ($val[$i] === '(') $open++; // считаем открвающие скобки
        if ($val[$i] === ')') {
            $open--; // считаем закрвающие
            if ($open < 0) return false;  // значит, что вываржение неверно т.к. закрвающая не найдет пару
        }
    }
    return $open === 0;  // если 0 -> true, !==0 -> false
}

function calculateSq($val) {
    $val = str_replace(' ', '', $val);
    if ($val === '') return 'Выражение не задано!'; // пустая строка
    if (!SqValidator($val)) return 'Ошибка расстановки скобок!'; // ошибка скобок
    
    $start = strpos($val, '('); // ищем первую открвающую
    if ($start === false) return calculate($val); // если скобок нет обращаемся к обычной функции

    $open = 1;
    $end = $start + 1;
    while ($open > 0 && $end < strlen($val)) { // пока открвавающих больше 0 и мы не прошли всю строку
        if ($val[$end] === '(') $open++;  // увеличиваем на открвающей
        if ($val[$end] === ')') $open--;  // уменьшаем на закрвающей
        $end++; // пробег по всей строке !! end принмает позицию после скобки !!
    }
    
    if ($open !== 0) return 'Ошибка расстановки скобок!'; //ловим ошибки
    
    $inner = substr($val, $start + 1, $end - $start - 2); // выражение внутри скобок
    $calc_inner = calculateSq($inner); // рекурсия, если скобок нет вернем обычной функции и получим значение
    if (!isnum($calc_inner)) return $calc_inner;  // ловим ошибку
    
    $new_val = substr($val, 0, $start) . $calc_inner . substr($val, $end); // конкатенируем левее скобок + внутри + правее
    return calculateSq($new_val);
}
// Обработка кнопки очистки сессии
if (isset($_POST['clear_session'])) {
    $_SESSION['history'] = [];
    $_SESSION['iteration'] = 0;
    // Перенаправление предотвращает повторную отправку формы
    header('Location: ' . $_SERVER['REQUEST_URI']);
    exit;
}
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Панин Данила 241-351 ЛР В-2 Вариант 20</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <div class="header-inner">
        <img src="img/logo.png" alt="Логотип университета">
        <div class="header-text">
            <div class="line1">Панин Д О · 241-351 · Лабораторная работа № В-2 · Вариант 20</div>
            <div class="line2">Преобразование типов. Сессии. Калькулятор выражений</div>
        </div>
    </div>
</header>

<main>
    <h1>Калькулятор арифметических выражений</h1>

    <?php if ($calc_done && $res !== null): ?>
        <div class="result-box">
            <h2>Результат:</h2>
            <p class="res-text"><?php echo htmlspecialchars($res); ?></p> <!-- // вывод результата -->
        </div>
    <?php elseif ($res !== null && !$calc_done): ?>
        <div class="result-box error">
            <p><?php echo htmlspecialchars($res); ?></p> <!-- вывод ошибки -->
        </div>
    <?php endif; ?>

    <form method="POST" action="" class="calc-form">
        <div class="input-group">
            <label for="val">Введите выражение:</label>
            <input type="text" id="val" name="val" 
                   placeholder="Например: 2 + (3 * 4)" 
                   value="<?php echo isset($_POST['val']) ? htmlspecialchars($_POST['val']) : '';; ?>" 
                   required> <!-- передаем рузльтата, потом добавим его в историю -->
            <!-- Скрытое поле для защиты от F5 -->
            <input type="hidden" name="iteration" value="<?php echo (int)($_SESSION['iteration']); ?>"> <!--  если задан элемент итерации -->
        </div>
        <button type="submit">Вычислить</button>
    </form>
    <form method="POST" action="" style="margin-top: 15px;">
        <button type="submit" name="clear_session" style="background:#c62828; padding:10px 16px;">
             Очистить историю и сбросить сессию
        </button>
    </form>
</main>

<footer>
    <div class="footer-inner">
        <div style="width: 100%;">
            <strong>История вычислений:</strong>
            <?php 
            if (!empty($_SESSION['history'])) { // вывод истории
                echo '<ul class="history-list">';
                foreach ($_SESSION['history'] as $item) {
                    echo '<li>' . $item . '</li>';
                }
                echo '</ul>';
            } else {
                echo '<p>История пуста</p>';
            }
            ?>
        </div>
        <div style="text-align: right; min-width: 200px;">
            <div>Панин Данила 241-351</div>
        </div>
    </div>
</footer>

</body>
</html>