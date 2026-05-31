<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Панин Данила 241-351 ЛР А-3 Вариант 20</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>

<header>
    <div class="header-inner">
        <img class="logo" src="img/logo.png" alt="Логотип университета">
        <div class="header-text">
            <div class="line1">Панин Д О · 241-351 · Лабораторная работа № А-3 · Вариант 20</div>
            <div class="line2">Использование GET-параметров в ссылках. Виртуальная клавиатура.</div>
        </div>
    </div>
</header>

<main>
    <h1>Виртуальная клавиатура</h1>

    <?php
    if (!isset($_GET['store'])) {
        $_GET['store'] = '';
    }
    if (!isset($_GET['clicks'])) {
        $_GET['clicks'] = 0;
    }

    $store = $_GET['store'];
    $clicks = $_GET['clicks'];
    ?>

    <div class="result"><?php echo $store; ?></div>

    <div class="keyboard">
        <div class="key-row"> // при нажатии на кнопку выводит клики + 1, т.к. нажатие увеличит клик
            <a href="/webi_3_lb/index.php?key=1&store=<?php echo $store . '1'; ?>&clicks=<?php echo $clicks + 1; ?>" class="key">1</a>
            <a href="/webi_3_lb/index.php?key=2&store=<?php echo $store . '2'; ?>&clicks=<?php echo $clicks + 1; ?>" class="key">2</a>
            <a href="/webi_3_lb/index.php?key=3&store=<?php echo $store . '3'; ?>&clicks=<?php echo $clicks + 1; ?>" class="key">3</a>
            <a href="/webi_3_lb/index.php?key=4&store=<?php echo $store . '4'; ?>&clicks=<?php echo $clicks + 1; ?>" class="key">4</a>
            <a href="/webi_3_lb/index.php?key=5&store=<?php echo $store . '5'; ?>&clicks=<?php echo $clicks + 1; ?>" class="key">5</a>
        </div>

        <div class="key-row">
            <a href="/webi_3_lb/index.php?key=6&store=<?php echo $store . '6'; ?>&clicks=<?php echo $clicks + 1; ?>" class="key">6</a>
            <a href="/webi_3_lb/index.php?key=7&store=<?php echo $store . '7'; ?>&clicks=<?php echo $clicks + 1; ?>" class="key">7</a>
            <a href="/webi_3_lb/index.php?key=8&store=<?php echo $store . '8'; ?>&clicks=<?php echo $clicks + 1; ?>" class="key">8</a>
            <a href="/webi_3_lb/index.php?key=9&store=<?php echo $store . '9'; ?>&clicks=<?php echo $clicks + 1; ?>" class="key">9</a>
            <a href="/webi_3_lb/index.php?key=0&store=<?php echo $store . '0'; ?>&clicks=<?php echo $clicks + 1; ?>" class="key">0</a>
        </div>

        <div class="key-row">
            <a href="/webi_3_lb/index.php?key=reset&store=&clicks=<?php echo $clicks + 1; ?>" class="key reset">СБРОС</a>
        </div>
    </div>
</main>

<footer>
    <div class="footer-inner">
        <div>Панин Данила 241-351</div>
        <div>
            <?php echo $clicks; ?>
        </div>
    </div>
</footer>

</body>
</html>