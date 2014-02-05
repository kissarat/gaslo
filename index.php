<?php
require_once 'base/init.php';

$page = isset($_GET['page']) ? $_GET['page'] : 'main';

$dir = isset($_GET['act']) ? 'action' : 'view';
if (!ctype_alpha($page))
    die("invalid page $page");
if ('action' == $dir)
    require "action/$page.php";
if (isset($_GET['return']))
    header('Location: ' . $_GET['return']);

$back = scandir('back');
$back = array_filter($back, function($v) {
    return '.' != $v[0];
});
$back = $back[array_rand($back)];

function item($label, $url, $page_name = null) {
    global $page;
    $class = '';
    if ($page == $page_name)
        $class = 'current';
    echo "<li class='$class'><a href='$url'>$label</a></li>";
}

function param($name) {
    if (isset($_POST['name']))
        return 'value="' . $_POST['name'] . '"';
    return '';
}

function entity_post($keys) {
    $entity = [];
    foreach($keys as $key)
        $entity[$key] = $_POST[$key];
    return $entity;
}

function report($messages, $style) {
    $count = count($messages);
    if ($count > 1) {
        echo "<ul class='$style'>";
        foreach($messages as $key => $msg)
            if ('number' == gettype($key))
                echo "<li>$msg</li>";
        echo "</ul>";
    }
    elseif (1 == $count)
        echo "<div class='$style'>$messages[0]</div>";
}

function attributes($assoc) {
    $str = [];
    foreach($assoc as $key => $value)
        array_push($str, "$key=\"$value\"");
    return join(' ', $str);
}

function input($attributes) {
    if (!isset($attributes['name']))
        $attributes['name'] = $attributes['type'];
    $name = $attributes['name'];
    if (isset($_POST[$name]))
        $attributes['value'] = $_POST[$name];
    $str = attributes($attributes);
    $str = "<input $str />";
    if (isset($errors[$name]))
        $str .= "<div class='error'>$errors[$name]</div>";
    return $str;
}

function submit($text) {
    return "<button type=\"submit\">$text</button>";
}

function form() {
    global $page;
    $fields = func_get_args();
    $attributes = array_shift($fields);
    if (!isset($attributes['page']))
        $attributes['page'] = $page;
    $act = '?page=' . $attributes['page'] . '&act=' . $attributes['act'];
    unset($attributes['page'], $attributes['act']);
    if (!isset($attributes['method']))
        $attributes['method'] = 'post';
    $str = attributes($attributes);
    echo "<form $str>";
    foreach($fields as $field)
        echo $field;
    echo '</form>';
}

function error() {
    global $errors;
    $args = func_get_args();
    if (count($args) == 1)
        array_push($errors, $args[0]);
    elseif (count($args) == 2)
        $errors[$args[0]] = $args[1];
}

?>

<!DOCTYPE html>
<html>
<head>
	<title></title>
    <link rel="stylesheet" type="text/css" href="index.css" />
    <style type="text/css">
        body {
            background-image: url('back/<?=$back ?>');
        }
    </style>
</head>
<body>
<div id="main">
    <header>
        PRO
        <img src="image/logo.png">
        Games
    </header>
    <nav>
        <ul>
            <?php
            item('Главная', '/', 'main');
            if (is_guest()) {
                item('Вход', '?page=login', 'login');
                item('Присоединиться', '?page=register', 'register');
            } else {
                item('Кабинет', 'cabinet.php', 'cabinet');
                item('Выход', '?page=login&act=logout', 'login');
            }
            ?>
        </ul>
    </nav>
    <?php
    report($errors, 'error');
    report($warnings, 'warning');
    ?>
    <article>
        <?php require "view/$page.php" ?>
    </article>
</div>
</body>
</html>