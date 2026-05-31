<?php
$mysqli = mysqli_connect('localhost', 'root', '', 'lab9_db');
if (!$mysqli) { echo '<div class="msg error">Ошибка подключения</div>'; exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_edit'])) {
    $id = (int)$_POST['id'];
    $fields = ['surname','name','patronymic','gender','birth_date','phone','address','email','comment'];
    $set = [];
    foreach ($fields as $f) $set[] = "$f='" . mysqli_real_escape_string($mysqli, htmlspecialchars($_POST[$f] ?? '')) . "'";
    mysqli_query($mysqli, "UPDATE contacts SET " . implode(', ', $set) . " WHERE id=$id"); // ОБНОВЛЯЕМ ЗАПИСЬ
    $_GET['id'] = $id;  // Сохранение ссылки
}
// определение текущей записи
$currentId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($currentId == 0) {
    $first = mysqli_fetch_assoc(mysqli_query($mysqli, "SELECT id FROM contacts ORDER BY surname, name LIMIT 1"));
    if ($first) $currentId = $first['id'];
}
$_GET['id'] = $currentId;
// загрузка данных записи
$currentRecord = $currentId ? mysqli_fetch_assoc(mysqli_query($mysqli, "SELECT * FROM contacts WHERE id=$currentId")) : null;

// ВЫВОД ССЫЛОК
echo '<div id="edit-links">'; 
$list = mysqli_query($mysqli, "SELECT id, surname, name FROM contacts ORDER BY surname, name");
while ($r = mysqli_fetch_assoc($list)) {
    if ($r['id'] == $currentId) {
        echo '<div class="current-item">' . htmlspecialchars($r['surname']) . ' ' . htmlspecialchars($r['name']) . '</div>';
    } else {
        echo '<a href="?p=edit&id=' . $r['id'] . '">' . htmlspecialchars($r['surname']) . ' ' . htmlspecialchars($r['name']) . '</a>';
    }
}
echo '</div>';

if ($currentRecord) { // ФОРМА РЕДАКТИРОВАНИЯ
    echo '<form method="POST" action="?p=edit" class="contact-form">
        <input type="hidden" name="id" value="' . $currentId . '">
        <input type="text" name="surname" value="' . htmlspecialchars($currentRecord['surname']) . '" required><br>
        <input type="text" name="name" value="' . htmlspecialchars($currentRecord['name']) . '" required><br>
        <input type="text" name="patronymic" value="' . htmlspecialchars($currentRecord['patronymic']) . '"><br>
        <select name="gender">
            <option value="Мужской" ' . ($currentRecord['gender']=='Мужской'?'selected':'') . '>Мужской</option>
            <option value="Женский" ' . ($currentRecord['gender']=='Женский'?'selected':'') . '>Женский</option>
        </select><br>
        <input type="date" name="birth_date" value="' . htmlspecialchars($currentRecord['birth_date']) . '"><br>
        <input type="tel" name="phone" value="' . htmlspecialchars($currentRecord['phone']) . '"><br>
        <input type="text" name="address" value="' . htmlspecialchars($currentRecord['address']) . '"><br>
        <input type="email" name="email" value="' . htmlspecialchars($currentRecord['email']) . '"><br>
        <textarea name="comment">' . htmlspecialchars($currentRecord['comment']) . '</textarea><br>
        <input type="submit" name="btn_edit" value="Сохранить изменения">
    </form>';
} else {
    echo '<p>Записей пока нет.</p>';
}
mysqli_close($mysqli);
?>
