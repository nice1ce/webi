<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Панин Данила 241-351 ЛР В-1 Вариант 20</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <div class="header-inner">
        <img src="img/logo.png" alt="Логотип университета">
        <div class="header-text">
            <div class="line1">Панин Д О · 241-351 · Лабораторная работа № В-1 · Вариант 20</div>
            <div class="line2">Основы баз данных и использования программных модулей. Записная книжка</div>
        </div>
    </div>
</header>

<main>
    <?php 
    // Подключаем модуль меню
    require 'menu.php'; 
    
    // РЕШЕНИЕ ОШИБКИ: Если 'p' нет в URL, ставим по умолчанию 'viewer'
    $current_page = $_GET['p'] ?? 'viewer';
    
    // Вызываем функцию отрисовки меню 
    echo renderMenu(); 
    ?>
    
    <div class="content-area">
        <?php
        // Подключаем нужный модуль в зависимости от значения $current_page
        switch ($current_page) {
            case 'viewer':
                include 'viewer.php';
                // Если viewer.php содержит функцию, вызываем её 
                if (function_exists('renderViewer')) {
                    $sort = $_GET['sort'] ?? 'byid'; // получаем сортировку
                    $page = $_GET['pg'] ?? 0;  /// получаем номер мтраницы
                    echo renderViewer($sort, $page);  //выводим таблицу
                }
                break;
                
            case 'add':
                include 'add.php';
                break;
                
            case 'edit':
                include 'edit.php';
                break;
                
            case 'delete':
                include 'delete.php';
                break;
                
            default:
                include 'viewer.php'; // На случай ошибки 
                break;
        }
        ?>
    </div>
</main>

<footer>
    <div class="footer-inner">
        <div>Панин Данила 241-351</div>
    </div>
</footer>

</body>
</html>