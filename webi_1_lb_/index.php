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
            $cls = "active";
            echo $cls; ?>" <?php echo 'href="' . $href. '">' . $text; ?></a>

            <a class="<?php
            $href = "page2.php";
            $text = "Инъекции & XSS";
            $cls = "";
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
        <h1>Карта рисков веб-приложения</h1>
        <p class="lead">
            Веб уязвимости — это сбои границ доверия: где данные внезапно становятся командами, где пользователь “становится”
            кем-то другим, и где система забывает спросить: “а можно ли?”.
        </p>
    </section>

    <section class="grid">
        <article class="card">
            <h2>Почему уязвимость — это всегда про риск</h2>
            <p>
                В веб-приложениях одна и та же ошибка может иметь различную критичность в зависимости от ценности обрабатываемых данных, доступности воспроизведения сценария и обнаруживаемости инцидента. Поэтому корректнее рассматривать не факт наличия дефекта, а ожидаемый ущерб, вероятность эксплуатации и последствия для конфиденциальности, целостности и доступности. Практически значимые дефекты часто формируются не в одной строке кода, а вдоль цепочки обработки: ввод → преобразование → хранение → вывод. Если на каком-либо этапе внешние данные интерпретируются как управляющие конструкции (разметка, запросы, пути), возрастает поверхность атаки. Использование типизированных интерфейсов и безопасных API фиксирует роль данных и снижает риск некорректной интерпретации.
            </p>

            <div style="height:14px"></div>

            <div class="table-wrap">
                <table>
                    <?php
                    echo "<tr>
                    <th>Категория риска</th>
                    <th>Пример</th>
                    <th>Как снижать</th>
                  </tr>";
                    ?>
                    <tr>
                        <td><?php echo "Границы доверия"; ?></td>
                        <td><?php echo "Параметр из формы используется “как есть”"; ?></td>
                        <td><?php echo "Валидация + безопасные API + контроль доступа"; ?></td>
                    </tr>
                    <tr>
                        <td>Конфигурация</td>
                        <td>Слабые настройки cookie / лишние права</td>
                        <td>Минимальные привилегии + безопасные заголовки</td>
                    </tr>
                </table>
            </div>
        </article>

        <aside class="card figure">
            <h2>Иллюстрация (мигает по секундам)</h2>
            <?php echo "<img src=\"fotos/risk". (date('s') % 2 + 1) .".jpg\"" . "alt=\"Переключаемое изображение (risk)\"/>" ?>
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