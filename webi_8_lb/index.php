<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Панин Данила 241-351 ЛР А-8 Вариант 20</title>
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
    <h1>Введите текст для анализа</h1>
    <form method="POST" action="result.php" class="analysis-form">
        <textarea name="data" rows="8" cols="50" placeholder="Введите английский или русский текст..."></textarea>
        <div class="form-controls">
            <button type="submit" class="btn">Анализировать</button>
        </div>
    </form>
</main>

<footer>
    <div class="footer-inner">
        <div>Панин Данила 241-351</div>
    </div>
</footer>

</body>
</html>