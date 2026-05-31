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
            $cls = "";
            echo $cls; ?>" <?php echo 'href="' . $href. '">' . $text; ?></a>

            <a class="<?php
            $href = "page3.php";
            $text = "Сессии & доступ";
            $cls = "active";
            echo $cls; ?>" <?php echo 'href="' . $href. '">' . $text; ?></a>
        </nav>
    </div>
</header>

<main>
    <section class="hero">
        <h1>Сессии, авторизация и CSRF</h1>
        <p class="lead">
            Проверка прав — это не “один раз при входе”, а правило для каждого чувствительного действия.
            А CSRF напоминает: браузер сам отправит cookie, если его не остановить.
        </p>
    </section>

    <section class="grid">
        <article class="card">
            <h2>Кто ты и что тебе можно</h2>
            <p>
                Корректная обработка идентичности и прав доступа является критическим фактором безопасности веб-приложений. Сессионный механизм связывает последовательность HTTP-запросов с состоянием пользователя; компрометация идентификатора сессии приводит к выполнению операций в чужом контексте. Помимо защиты сессии, требуется строгая серверная авторизация на каждое значимое действие и на каждый объект данных, так как параметры клиента не могут считаться доверенными. Отдельный класс рисков связан с CSRF: браузер автоматически прикладывает учетные данные к запросам, что позволяет инициировать нежелательные операции при отсутствии маркеров намерения. Снижение риска обеспечивается CSRF-токенами, проверками источника запроса и корректной конфигурацией cookie, ограничивающей несанкционированную отправку.
            </p>

            <div style="height:14px"></div>

            <div class="table-wrap">
                <table>
                    <?php
                    echo "<tr>
                    <th>Механизм</th>
                    <th>Что может пойти не так</th>
                    <th>Контрмера</th>
                  </tr>";
                    ?>
                    <tr>
                        <td>Сессия</td>
                        <td><?php echo "Перехват/утечка идентификатора, слишком долгие токены"; ?></td>
                        <td><?php echo "Безопасные cookie, ограничение жизни, ротация"; ?></td>
                    </tr>
                    <tr>
                        <td>CSRF</td>
                        <td>Запрос выполняется “с чужой страницы” с твоими cookie</td>
                        <td>CSRF-токен, проверка источника, атрибуты cookie</td>
                    </tr>
                </table>
            </div>
        </article>

        <aside class="card figure">
            <h2>Иллюстрация (мигает по секундам)</h2>
            <?php echo "<img src=\"fotos/session". (date('s') % 2 + 1) .".jpg\"" . "alt=\"Переключаемое изображение (risk)\"/>" ?>
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