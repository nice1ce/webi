<?php

function getRandomValue() {
    return round(mt_rand(0, 10000) / 100, 2);
}

function normalizeNumber($val) {
    return floatval(str_replace(',', '.', trim($val)));
}

/**
 * Решение математической задачи
 * @return array ['result' => float|string, 'name' => string]
 */
function solveTask($task, $a, $b, $c) {
    $result = null;
    $name = '';

    switch ($task) {
        case 'mean':
            // Среднее арифметическое (Листинг А-6.3)
            $result = round(($a + $b + $c) / 3, 2);
            $name = 'Среднее арифметическое';
            break;
        case 'perimetr':
            // Периметр треугольника (Листинг А-6.3)
            $result = round($a + $b + $c, 2);
            $name = 'Периметр треугольника';
            break;
        case 'square':
            // Площадь треугольника по формуле Герона
            $p = ($a + $b + $c) / 2;
            $area = $p * ($p - $a) * ($p - $b) * ($p - $c);
            if ($area > 0 && !is_nan($area)) {
                $result = round(sqrt($area), 2);
            } else {
                $result = 'Невозможен';
            }
            $name = 'Площадь треугольника';
            break;
        case 'volume':
            // Объём параллелепипеда
            $result = round($a * $b * $c, 2);
            $name = 'Объём параллелепипеда';
            break;
        case 'max':
            // Максимальное из трёх (дополнительная задача)
            $result = round(max($a, $b, $c), 2);
            $name = 'Максимальное из трёх';
            break;
        case 'custom':
            // Пользовательская задача: (A × B) + C (дополнительная задача)
            $result = round(($a * $b) + $c, 2);
            $name = 'Пользовательская: (A×B)+C';
            break;
    }

    return ['result' => $result, 'name' => $name];
}


// Поля ФИО и группы берём из GET при повторном тесте,
$initFIO = isset($_GET['F']) ? htmlspecialchars($_GET['F']) : '';
$initGROUP = isset($_GET['G']) ? htmlspecialchars($_GET['G']) : '';

// задаем числам слуйчайные параметры
$initA = getRandomValue();
$initB = getRandomValue();
$initC = getRandomValue();

// Флаги отображения
$showForm = true;
$report = '';
$emailSent = false;
$viewMode = 'browser';

// Переменные для отчёта (инициализация)
$fio = '';
$group = '';
$userEmail = '';

// ============================================================================
// ОБРАБОТКА ДАННЫХ ФОРМЫ (если была отправлена)
// ============================================================================

// Листинг А-6.1: проверка наличия данных из формы
if (isset($_POST['A'])) {
    $showForm = false;

    // Получение и очистка данных
    $fio = htmlspecialchars(trim($_POST['FIO'] ?? ''));
    $group = htmlspecialchars(trim($_POST['GROUP'] ?? ''));
    $a = normalizeNumber($_POST['A'] ?? 0);
    $b = normalizeNumber($_POST['B'] ?? 0);
    $c = normalizeNumber($_POST['C'] ?? 0);
    $userAnswer = trim($_POST['result'] ?? '');
    $userEmail = htmlspecialchars(trim($_POST['MAIL'] ?? ''));
    $about = htmlspecialchars(trim($_POST['ABOUT'] ?? ''));
    $task = $_POST['TASK'] ?? '';
    $viewMode = $_POST['VIEW'] ?? 'browser';
    $sendMail = isset($_POST['send_mail']);


    $solution = solveTask($task, $a, $b, $c);
    $correctResult = $solution['result'];
    $taskName = $solution['name'];


    // пустоая строка-> надпись: "Задача самостоятельно решена не была"
    $userAnswerClean = normalizeNumber($userAnswer);

    if ($userAnswer === '' || $userAnswer === null) {
        // Пустой ответ - задача не решена самостоятельно
        $isCorrect = false;
        $userAnswerDisplay = '<em class="not-solved">Задача самостоятельно решена не была</em>';
        $resultMessage = '<p class="result-warning"><strong>Вывод:</strong> Задача самостоятельно решена не была</p>';
    } elseif ($correctResult === 'Невозможен') {
        $isCorrect = false;
        $userAnswerDisplay = htmlspecialchars($userAnswer);
        $resultMessage = '<p class="result-warning"><strong>Вывод:</strong> Треугольник с такими сторонами не существует</p>';
    } else {
        // Листинг А-6.5: используем === для точного сравнения
        $isCorrect = ($userAnswerClean === floatval($correctResult));
        $userAnswerDisplay = htmlspecialchars($userAnswer);

        if ($isCorrect) {
            $resultMessage = '<p class="result-success"><strong>✓ Тест пройден!</strong></p>';
        } else {
            $resultMessage = '<p class="result-error"><strong>✗ Ошибка: тест не пройден!</strong></p>';
        }
    }

    // Листинг А-6.6: формирование отчёта в переменной $out_text
    $out_text = '';
    $out_text .= '<div class="report-block">';
    $out_text .= '<p><strong>ФИО:</strong> ' . $fio . '</p>';
    $out_text .= '<p><strong>Группа:</strong> ' . $group . '</p>';

    if ($about !== '') {
        $out_text .= '<p><strong>О себе:</strong><br>' . nl2br($about) . '</p>';
    }

    $out_text .= '<p><strong>Тип задачи:</strong> ' . $taskName . '</p>';
    $out_text .= '<p><strong>Входные данные:</strong> A = ' . $a . ', B = ' . $b . ', C = ' . $c . '</p>';
    $out_text .= '<p><strong>Ваш ответ:</strong> ' . $userAnswerDisplay . '</p>';

    if ($correctResult === 'Невозможен') {
        $out_text .= '<p><strong>Правильный ответ:</strong> <span class="warning">Треугольник не существует</span></p>';
    } else {
        $out_text .= '<p><strong>Правильный ответ:</strong> ' . $correctResult . '</p>';
    }

    $out_text .= $resultMessage;
    $out_text .= '</div>';

    $report = $out_text;

    // Листинг А-6.6: отправка письма, если установлен флажок
    // Проверка: array_key_exists или isset для флажка
    if ($sendMail && $userEmail !== '' && filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {
        $mailSubject = 'Результат тестирования - ЛР А-6';
        // Замена <br> на \r\n для текстового письма (Листинг А-6.6)
        $mailBody = str_replace('<br>', "\r\n", strip_tags($out_text));
        $mailBody .= "\r\n\r\nПользователь: $fio ($group)\r\nEmail: $userEmail";

        $headers = "From: noreply@university.ru\r\n";
        $headers .= "Content-Type: text/plain; charset=utf-8\r\n";
        $headers .= "MIME-Version: 1.0\r\n";

        // mail($userEmail, $mailSubject, $mailBody, $headers); // Активировать на сервере

        $emailSent = true;
    }
}

?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Панин Данила 241-351 ЛР А-6 Вариант 20</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>

<header>
    <div class="header-inner">
        <img src="img/logo.png" alt="Логотип университета">
        <div class="header-text">
            <div class="line1">Панин Д О · 241-351 · Лабораторная работа № А-6 · Вариант 20</div>
            <div class="line2">Использование форм для передачи данных в программу PHP. Тест математических знаний</div>
        </div>
    </div>
</header>

<main>
    <h1>Тест математических знаний</h1>

    <?php if ($showForm): ?>
        <!-- ==================== ФОРМА ВВОДА ==================== -->
        <form name="mathTest" method="post" action="" class="test-form" id="testForm">

            <!-- ФИО (Листинг А-6.7: параметр F) -->
            <div class="form-row">
                <label for="fio">ФИО *</label>
                <input type="text" id="fio" name="FIO" required
                       value="<?php echo $initFIO; ?>"
                       placeholder="Иванов Иван Иванович">
            </div>

            <!-- Группа (Листинг А-6.7: параметр G) -->
            <div class="form-row">
                <label for="group">Номер группы *</label>
                <input type="text" id="group" name="GROUP" required
                       value="<?php echo $initGROUP; ?>"
                       placeholder="241-351">
            </div>

            <!-- Числа A, B, C (всегда новые при загрузке) -->
            <div class="form-row triple">
                <div>
                    <label for="a">Значение A *</label>
                    <input type="text" id="a" name="A" required
                           value="<?php echo $initA; ?>"
                           pattern="[0-9.,]+" title="Число от 0 до 100">
                </div>
                <div>
                    <label for="b">Значение B *</label>
                    <input type="text" id="b" name="B" required
                           value="<?php echo $initB; ?>"
                           pattern="[0-9.,]+" title="Число от 0 до 100">
                </div>
                <div>
                    <label for="c">Значение C *</label>
                    <input type="text" id="c" name="C" required
                           value="<?php echo $initC; ?>"
                           pattern="[0-9.,]+" title="Число от 0 до 100">
                </div>
            </div>

            <!-- Выбор задачи (6+ опций по требованию ЛР) -->
            <div class="form-row">
                <label for="task">Выберите задачу *</label>
                <select id="task" name="TASK" required>
                    <option value="">-- Выберите задачу --</option>
                    <option value="square">Площадь треугольника</option>
                    <option value="perimetr">Периметр треугольника</option>
                    <option value="volume">Объём параллелепипеда</option>
                    <option value="mean" selected>Среднее арифметическое</option>
                    <option value="max">Максимальное из трёх чисел</option>
                    <option value="custom">Пользовательская: (A×B)+C</option>
                </select>
            </div>

            <!-- Ответ пользователя (НЕОБЯЗАТЕЛЬНОЕ ПОЛЕ по требованию ЛР) -->
            <div class="form-row">
                <label for="userResult">Ваш ответ</label>
                <input type="text" id="userResult" name="result"
                       placeholder="Введите ваш расчёт (необязательно)"
                       pattern="[0-9.,]+" title="Используйте точку или запятую для дробей">
            </div>

            <!-- О себе (многострочное поле) -->
            <div class="form-row">
                <label for="about">Немного о себе</label>
                <textarea id="about" name="ABOUT" rows="3"
                          placeholder="Расскажите о себе..."></textarea>
            </div>

            <!-- Чекбокс отправки на email (Листинг А-6.6) -->
            <div class="form-row checkbox-row">
                <input type="checkbox" id="sendMail" name="send_mail" value="1"
                       onclick="toggleEmailField()">
                <label for="sendMail">Отправить результат теста по e-mail</label>
            </div>

            <!-- Поле email (скрыто по умолчанию, JavaScript из ЛР6.pdf) -->
            <div class="form-row email-row" id="emailRow" style="display:none;">
                <label for="email">Ваш e-mail *</label>
                <input type="email" id="email" name="MAIL"
                       placeholder="example@domain.com">
            </div>

            <!-- Режим отображения (браузер/печать) -->
            <div class="form-row">
                <label for="view">Версия отображения</label>
                <select id="view" name="VIEW">
                    <option value="browser" selected>Версия для просмотра в браузере</option>
                    <option value="print">Версия для печати</option>
                </select>
            </div>

            <!-- Кнопка "Проверить" в виде ссылки <a> -->
            <div class="form-actions">
                <a href="#" class="btn-submit" id="checkBtn" onclick="submitForm(event)">Проверить</a>
            </div>
        </form>

        <!-- JavaScript для клиентской валидации (ЛР6.pdf: справочная информация) -->
        <script>
            // Скрыть/показать поле email в зависимости от флажка
            function toggleEmailField() {
                const checkbox = document.getElementById('sendMail');
                const emailRow = document.getElementById('emailRow');
                const emailInput = document.getElementById('email');

                if (checkbox.checked) {
                    emailRow.style.display = 'flex';
                    emailInput.required = true;
                } else {
                    emailRow.style.display = 'none';
                    emailInput.required = false;
                }
            }

            // Отправка формы через кнопку-ссылку
            function submitForm(e) {
                e.preventDefault();
                const form = document.getElementById('testForm');

                if (form.checkValidity()) {
                    form.submit();
                } else {
                    form.reportValidity();
                }
            }
        </script>

    <?php else: ?>
        <!-- ==================== ОТЧЁТ О РЕЗУЛЬТАТАХ ==================== -->
        <div class="report-container <?php echo $viewMode === 'print' ? 'print-mode' : ''; ?>">
            <?php echo $report; ?>

            <!-- Сообщение об отправке email (Листинг А-6.6) -->
            <?php if ($emailSent): ?>
                <p class="email-notice">
                    ✓ Результаты теста были автоматически отправлены на e-mail:
                    <strong><?php echo htmlspecialchars($userEmail); ?></strong>
                </p>
            <?php endif; ?>

            <!-- Кнопка "Повторить тест" (только для браузерной версии) -->
            <?php if ($viewMode === 'browser'): ?>
                <div class="repeat-section">
                    <a href="?F=<?php echo urlencode($fio); ?>&G=<?php echo urlencode($group); ?>"
                       class="btn-repeat" id="back_button">Повторить тест</a>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</main>

<footer>
    <div class="footer-inner">
        <div>Панин Данила 241-351</div>
    </div>
</footer>

</body>
</html>