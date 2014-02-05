<?php

function clean() {
    global $mem;
    if (isset($_COOKIE['auth'])) {
        $mem->delete($_COOKIE['auth']);
        unset($_COOKIE['auth']);
    }
}

switch($_GET['act']) {
    case 'login':
        clean();
        $subject = fetch_single('select * from subject where name=?', $_POST['name']);
        if ($subject && md5($_POST['password']) == $subject['password']) {
            $mem->add($subject['salt'], $subject);
            setcookie('auth', $subject['salt']);
            $page = 'main';
        }
        else
            array_push($errors, 'Неправильной логил или пароль');
        break;
    case 'logout':
        clean();
        break;
}

