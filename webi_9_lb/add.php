<?php
$msg = ''; $msgClass = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_add'])) {
    $mysqli = mysqli_connect('localhost', 'root', '', 'lab9_db'); //конект
    if ($mysqli) {
        $fields = ['surname','name','patronymic','gender','birth_date','phone','address','email','comment'];
        $vals = [];
        // Подготовка значений для SQL-запроса
        foreach ($fields as $f) $vals[] = "'" . mysqli_real_escape_string($mysqli, htmlspecialchars($_POST[$f] ?? '')) . "'";
        // скрипт для sql 
        $sql = "INSERT INTO contacts (" . implode(',', $fields) . ") VALUES (" . implode(',', $vals) . ")";
        if (mysqli_query($mysqli, $sql)) { //отправляем запрос
            $msg = 'Запись добавлена'; $msgClass = 'ok';
        } else {
            $msg = 'Ошибка: запись не добавлена'; $msgClass = 'error';
        }
        mysqli_close($mysqli);
    } else {
        $msg = 'Ошибка подключения к БД'; $msgClass = 'error';
    }
}
?>
<form method="POST" action="?p=add" class="contact-form">
    <input type="text" name="surname" placeholder="Фамилия" required><br>
    <input type="text" name="name" placeholder="Имя" required><br>
    <input type="text" name="patronymic" placeholder="Отчество"><br>
    <select name="gender"><option value="Мужской">Мужской</option><option value="Женский">Женский</option></select><br>
    <input type="date" name="birth_date"><br>
    <input type="tel" name="phone" placeholder="Телефон"><br>
    <input type="text" name="address" placeholder="Адрес"><br>
    <input type="email" name="email" placeholder="Email"><br>
    <textarea name="comment" placeholder="Комментарий"></textarea><br>
    <input type="submit" name="btn_add" value="Добавить запись">
</form>
<?php if ($msg) echo "<div class=\"msg $msgClass\">$msg</div>"; ?>
