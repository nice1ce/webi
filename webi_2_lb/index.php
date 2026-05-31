<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Панин Данила 241-351 ЛР А-2  Вариант 20</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>

<header>
    <div class="header-inner">
        <img src="img/logo.png" alt="Логотип университета">
        <div class="header-text">
            <div class="line1">Панин Д О · 241-351 · Лабораторная работа № А-2 · Вариант 20</div>
            <div class="line2">Циклы и условия. Табулирование функции (функция варианта 10)</div>
        </div>
    </div>
</header>

<main>
    <h1>Табулирование функции f(x)</h1>
    <p class="code-note">
        Результаты вычислений формируются PHP-кодом. Округление: до 3 знаков после запятой.
    </p>

    <?php

    $x = -10;           // начальное значение аргумента
    $encounting = 40;   // количество вычисляемых значений
    $step = 1;          // шаг изменения аргумента

    $min_value = -50;
    $max_value =  50;

    $type = 'D'; // A/B/C/D/E для проверки

    $sum = 0.0;
    $count_numeric = 0;
    $minF = null;
    $maxF = null;

    if ($type == 'B')
        echo '<ul>';
    if ($type == 'C')
        echo '<ol>';
    if ($type == 'D') {
        echo '<table class="tableD">';
        echo '<tr> 
              <th>#</th>
              <th>x</th>
              <th>f(x)</th>
              </tr>';
    }
    if ($type == 'E')
        echo '<div class="blocksE">';


    for ($i = 0; $i < $encounting; $i++, $x += $step) {

        // f(x) =  3/x + x/3 - 5        при x <= 10
        //         (x - 7)*(x/8)        при x > 10 && x < 20
        //         3x + 2               при x >= 20
        $f = null;

        if ($x <= 10) {
            if ($x == 0) {
                $f = 'error';
            } else {
                $f = 3/$x + $x/3 - 5;
                $f = round($f, 3);
            }
        } else if ($x < 20) {
            $f = ($x - 7) * ($x / 8);
            $f = round($f, 3);
        } else {
            $f = 3*$x + 2;
            $f = round($f, 3);
        }

        // Проверка на минимальное и максимальное значения функции
        if ($f !== 'error') {
            if ($f >= $max_value || $f < $min_value) {
                break;
            }
        }

        // статистика
        if ($f !== 'error') {
            $sum += $f;
            $count_numeric++;

            if ($minF === null || $f < $minF)
                $minF = $f;
            if ($maxF === null || $f > $maxF)
                $maxF = $f;
        }

        $line = 'f(' . $x . ')='. $f;

        switch ($type) {
            case 'A':{
                // выводим <br> в начале строки, чтобы не проверять "последнюю итерацию"
                if ($i > 0) echo '<br>';
                echo $line;
                break;
            } case 'B':/*{
                echo '<li>'.$line.'</li>';
                break;
            }*/ case 'C':{
                echo '<li>'.$line.'</li>';
                break;
            } case 'D':{
                echo '<tr><td>' . ($i+1) . '</td><td>' . $x . '</td><td>' . $f . '</td></tr>';
                break;
            } case 'E':{
                echo '<div class="blockE">' . $line.'</div>';
                break;
            }default:{
                if ($i > 0) echo '<br>';
                echo $line;
                }
        }
    }
    /*
  $i = 0;
  while ($i < $encounting) {

    $f = null;

    if ($x <= 10) {
      if ($x == 0) {
        $f = 'error';
      } else {
        $f = round(3/$x + $x/3 - 5, 3);
      }
    } else if ($x < 20) {
      $f = round(($x - 7) * ($x / 8), 3);
    } else {
      $f = round(3*$x + 2, 3);
    }

    if ($f !== 'error') {
      if ($f >= $max_value || $f < $min_value) {
        break;
      }
    }

    // блок статистики и вывода

    $i++;
    $x += $step;
}*/ // Цикл while

    /*
     * $i = 0;

do {
  $f = null;

  if ($x <= 10) {
    if ($x == 0) {
      $f = 'error';
    } else {
      $f = round(3/$x + $x/3 - 5, 3);
    }
  } else if ($x < 20) {
    $f = round(($x - 7) * ($x / 8), 3);
  } else {
    $f = round(3*$x + 2, 3);
  }

  if ($f !== 'error') {
    if ($f >= $max_value || $f < $min_value) {
      break;
    }
  }

  // блок статистики и вывода

  $i++;
  $x += $step;

} while ($i < $encounting);
 */ // Цикл do while

    if ($type == 'B') echo '</ul>';
    if ($type == 'C') echo '</ol>';
    if ($type == 'D') echo '</table>';
    if ($type == 'E') echo '</div>';


    echo '<div class="stats">';
    echo '<strong>Статистика по числовым значениям функции</strong><br>';

    if ($count_numeric > 0) {
        $avg = round($sum / $count_numeric, 3);

        echo 'Сумма: ' . round($sum, 3).'<br>';
        echo 'Среднее арифметическое: ' . $avg.'<br>';
        echo 'Минимум: ' . $minF.'<br>';
        echo 'Максимум: ' . $maxF.'<br>';
        echo 'Количество числовых значений: ' . $count_numeric.'<br>';
    } else {
        echo 'Невозможно вычислить статистику: все значения функции равны "error" . <br>';
    }

    echo '</div>';


    $typeName = 'Неизвестный тип';
    switch ($type) {
        case 'A':{
            $typeName = 'A — простая текстовая верстка';
            break;
        } case 'B':{
        $typeName = 'B — маркированный список';
        break;
         } case 'C': {
             $typeName = 'C — нумерованный список';
             break;}
        case 'D': {
            $typeName = 'D — табличная верстка';
            break;}
        case 'E':{
            $typeName = 'E — блочная верстка';
            break;
        }
        default: {
            $typeName = 'Неизвестный тип верстки';
        }
    }


    ?>

</main>

<footer>
    <div class="footer-inner">
        <div>Панин Данила 241-351</div>
        <div>
            <?php echo $typeName; ?>
        </div>
    </div>
</footer>

</body>
</html>
