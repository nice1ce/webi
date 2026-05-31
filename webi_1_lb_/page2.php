<?php
date_default_timezone_set('Europe/Moscow');
$title = "Лабораторная работа № А-1";
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" href="css/style.css"/>
</head>
<body>

<header>
    <div class="header-inner">
        <div class="brand">
            <div class="title">ЛР № А-1 — Уязвимости веб-приложений</div>
            <div class="sub">Панин Данила 241-351</div>
        </div>

        <nav class="menu">
            <a class="<?php
            $href = "index.php";
            $text = "Карта рисков";
            $cls = "";
            echo $cls; ?>" <?php echo 'href="' . $href. '">' . $text; ?></a>

            <a class="<?php
            $href = "page2.php";
            $text = "Инъекции & XSS";
            $cls = "active";
            echo $cls; ?>" <?php echo 'href="' . $href. '">' . $text; ?></a>

            <a class="<?php
            $href = "page3.php";
            $text = "Сессии & доступ";
            $cls = "";
            echo $cls; ?>" <?php echo 'href="' . $href. '">' . $text; ?></a>
        </nav>
    </div>
</header>

<main>
    <section class="hero">
        <h1>Инъекции и XSS: когда данные начинают “говорить” вместо тебя</h1>
        <p class="lead">
            Главная ошибка — смешать данные и команды. В одном случае командный интерпретатор — база,
            в другом — браузер.
        </p>
    </section>

    <section class="grid">
        <article class="card">
            <h2>Идея одна, контекст разный</h2>
            <p>
                Инъекции относятся к классу уязвимостей, при которых входные данные ошибочно включаются в структуру команд или запросов и начинают влиять на исполняемую логику. Типовой первопричиной является формирование инструкций путём конкатенации строк без строгого разделения данных и управляющих элементов. Для снижения риска применяются параметризованные запросы, типизация и безопасные интерфейсы доступа к данным. XSS представляет аналогичную проблему в клиентском контексте: при некорректном кодировании данных при выводе браузер может интерпретировать их как HTML/скрипт. Эффективная защита основывается на контекстно-зависимом кодировании вывода, минимизации опасных вставок и использовании ограничительных политик, уменьшающих последствия ошибочного отображения данных.
            </p>

            <div style="height:14px"></div>

            <div class="table-wrap">
                <table>
                    <?php
                    echo "<tr>
                    <th>Класс уязвимости</th>
                    <th>Где возникает</th>
                    <th>Базовая защита</th>
                  </tr>";
                    ?>
                    <tr>
                        <td>Инъекции</td>
                        <td><?php echo "Склейка команд и внешних данных"; ?></td>
                        <td><?php echo "Параметры, типы, безопасные API"; ?></td>
                    </tr>
                    <tr>
                        <td>XSS</td>
                        <td>Вывод ввода в HTML/JS без контекстного кодирования</td>
                        <td>Кодирование вывода + CSP + безопасные шаблоны</td>
                    </tr>
                </table>
            </div>
        </article>

        <aside class="card figure">
            <h2>Иллюстрация (мигает по секундам)</h2>
            <?php echo "<img src=\"fotos/inject". (date('s') % 2 + 1) .".jpg\"" . "alt=\"Переключаемое изображение (risk)\"/>" ?>
            <div class="caption">
                Картинка меняется по четности секунды: <?php echo date('s'); ?>.
            </div>
        </aside>
    </section>
</main>

<footer>
    <div class="footer-inner">
        <div>Панин Данила 241-351</div>
        <div class="badge">
            <?php echo "Сформировано " . date("d.m.Y") . " в " . date("H:i:s"); ?>
        </div>
    </div>
</footer>

</body>
</html>