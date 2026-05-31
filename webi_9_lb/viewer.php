<?php
function renderViewer($sort, $page) {
    $mysqli = mysqli_connect('localhost', 'root', '', 'lab9_db'); // конектимся к sql
    if (!$mysqli) {
        return '<div class="msg error">Ошибка подключения к БД: ' . mysqli_connect_error() . '</div>';
    }

    // Получаем общее количество записей
    $res = mysqli_query($mysqli, "SELECT COUNT(*) as cnt FROM contacts");
    if (!$res) {
        mysqli_close($mysqli);
        return '<div class="msg error">Ошибка запроса: ' . mysqli_error($mysqli) . '</div>';
    }
    
    $row = mysqli_fetch_assoc($res); // преобразуем запрос в масив с ключами
    $total = (int)$row['cnt']; // расчет записей
    
    if ($total == 0) { // пустая таблица
        mysqli_close($mysqli);
        return '<p>В таблице нет данных.</p>';
    }

    // Вычисляем количество страниц
    $pages = ceil($total / 10);
    $page = (int)$page; // 
    if ($page >= $pages) $page = max(0, $pages - 1); // заглушка несущ страниц, показываем последнюю
    if ($page < 0) $page = 0;

    // Определяем сортировку
    $orderBy = match($sort) {
        'fam' => 'surname, name',
        'birth' => 'birth_date',
        default => 'id'
    };

    // Получаем данные для вывода страниц по нумерации
    $limit = $page * 10;
    $sql = "SELECT * FROM contacts ORDER BY {$orderBy} LIMIT {$limit}, 10"; //сортируем данные
    $res = mysqli_query($mysqli, $sql);
    
    if (!$res) { // ловим ошибку
        mysqli_close($mysqli);
        return '<div class="msg error">Ошибка выборки данных: ' . mysqli_error($mysqli) . '</div>';
    }

    // Формируем таблицу
    $html = '<div class="table-wrapper">'; // Оборачиваем таблицу
    $html .= '<table>';
    $html .= '<tr>';
    $html .= '<th>Фамилия</th>';
    $html .= '<th>Имя</th>';
    $html .= '<th>Отчество</th>';
    $html .= '<th>Пол</th>';
    $html .= '<th>Дата рождения</th>';
    $html .= '<th>Телефон</th>';
    $html .= '<th>Адрес</th>';
    $html .= '<th>Email</th>';
    $html .= '<th>Комментарий</th>';
    $html .= '</tr>';
    
    while ($r = mysqli_fetch_assoc($res)) {
        $html .= '<tr>';
        $html .= '<td>' . htmlspecialchars($r['surname'] ?? '') . '</td>';
        $html .= '<td>' . htmlspecialchars($r['name'] ?? '') . '</td>';
        $html .= '<td>' . htmlspecialchars($r['patronymic'] ?? '') . '</td>';
        $html .= '<td>' . htmlspecialchars($r['gender'] ?? '') . '</td>';
        $html .= '<td>' . htmlspecialchars($r['birth_date'] ?? '') . '</td>';
        $html .= '<td>' . htmlspecialchars($r['phone'] ?? '') . '</td>';
        $html .= '<td>' . htmlspecialchars($r['address'] ?? '') . '</td>';
        $html .= '<td>' . htmlspecialchars($r['email'] ?? '') . '</td>';
        $html .= '<td>' . htmlspecialchars($r['comment'] ?? '') . '</td>';
        $html .= '</tr>';
    }
    $html .= '<div class="table-wrapper">'; 
    $html .= '</table>';
    $html .= '</div>'; // Закрываем обертку таблицы
    $html .= '</div>'; 
    // разделение на страницы
    if ($pages > 1) {
        $html .= '<div id="pagination">';
        for ($i = 0; $i < $pages; $i++) {
            if ($i === $page) {
                $html .= '<span class="current">' . ($i + 1) . '</span>';
            } else {
                //  сохраняем параметр сортировки
                $html .= "<a href=\"?p=viewer&sort={$sort}&pg={$i}\">" . ($i + 1) . "</a>";
            }
        }
        $html .= '</div>';
    }
    
    mysqli_close($mysqli);
    return $html;
}
?>