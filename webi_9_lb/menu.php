<?php
function renderMenu() {
    // Определяем текущий пункт меню
    $p = isset($_GET['p']) ? $_GET['p'] : 'viewer';
    if (!in_array($p, ['viewer', 'add', 'edit', 'delete'])) {
        $p = 'viewer';
    }
    $_GET['p'] = $p;
    
    // Сохраняем текущую страницу пагинации, для стилей
    $currentPage = isset($_GET['pg']) ? (int)$_GET['pg'] : 0;
    if ($currentPage < 0) $currentPage = 0;

    $html = '<div id="menu">';
    
    // Основные пункты меню
    $menuItems = [
        'viewer' => 'Просмотр',
        'add' => 'Добавление записи',
        'edit' => 'Редактирование записи',
        'delete' => 'Удаление записи'
    ];
    // вывод с выьранным пунктом
    foreach ($menuItems as $key => $label) {
        $class = ($p === $key) ? ' class="selected"' : '';
        $html .= "<a href=\"?p={$key}\"{$class}>{$label}</a>";
    }
    
    // Подменю сортировки (только для Просмотра)
    if ($p === 'viewer') {
        $sort = isset($_GET['sort']) ? $_GET['sort'] : 'byid';
        if (!in_array($sort, ['byid', 'fam', 'birth'])) { 
            $sort = 'byid'; // установка сортировки по-дефолту
        }
        $_GET['sort'] = $sort;
        
        $html .= '<div id="submenu">';
        
        $sortItems = [
            'byid' => 'По умолчанию',
            'fam' => 'По фамилии',
            'birth' => 'По дате рождения'
        ];
        
        foreach ($sortItems as $key => $label) { 
            $class = ($sort === $key) ? ' class="selected"' : ''; //вывод для стилей
            // передаем текущую страницу pg
            $html .= "<a href=\"?p=viewer&sort={$key}&pg={$currentPage}\"{$class}>{$label}</a>";
        }
        
        $html .= '</div>';
    }
    
    $html .= '</div>';
    return $html; //возвращаем меню и под-меню
}
?>