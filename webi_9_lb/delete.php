<?php
$mysqli = mysqli_connect('localhost', 'root', '', 'lab9_db');
if (!$mysqli) { echo '<div class="msg error">Ошибка подключения</div>'; exit; }

$msg = '';
if (isset($_GET['del_id'])) {
    $id = (int)$_GET['del_id'];
    $res = mysqli_query($mysqli, "SELECT surname FROM contacts WHERE id=$id"); //находим и сохраняем запись с id 
    if ($row = mysqli_fetch_assoc($res)) {
        mysqli_query($mysqli, "DELETE FROM contacts WHERE id=$id"); // удаляем
        $msg = "Запись с фамилией " . htmlspecialchars($row['surname']) . " удалена"; // сообщение оь ошибке
    }
}

echo '<div id="delete-links">';
$list = mysqli_query($mysqli, "SELECT id, surname, name, patronymic FROM contacts ORDER BY surname, name");
while ($r = mysqli_fetch_assoc($list)) {  // вывод ссылок
    $init = (mb_strlen($r['name'])>0 ? mb_substr($r['name'],0,1).'.' : '') .  //получаем инициалы
            (mb_strlen($r['patronymic'])>0 ? mb_substr($r['patronymic'],0,1).'.' : '');
    echo '<a href="?p=delete&del_id=' . $r['id'] . '">' . htmlspecialchars($r['surname']) . ' ' . $init . '</a><br>'; // ссылка
}
echo '</div>';

if ($msg) echo "<div class=\"msg ok\">$msg</div>"; // если удаление было выводим сообщение
mysqli_close($mysqli);
?>
