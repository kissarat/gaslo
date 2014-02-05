<?php

function validate($params) {
    extract($params);
    if (!isset($name) || !$name)
        error('name', 'Логин не указан');
    elseif (count($name) < 4 || count($name) > 16)
        error('name', 'Логин должен иметь болше 4-х и меньше 16-ти символов');
    elseif (preg_match(REX_NAME, $name))
        error('name', 'Логин может содержать символи латинского алфавита, цифры или _');
}

switch ($_GET['act']) {
    case 'new':
        $params = entity_post(['name', 'password']);
        $subject = $mem->get($_COOKIE['auth']);
        if (isset($_POST['captcha']) && $_POST['captcha'] == $subject['captcha']) {
            $params['password'] = md5($params['password']);
            $params['salt'] = $_COOKIE['auth'];
            insert('subject', $params);
        }
        else
            error('Неправильной код');
        break;
}