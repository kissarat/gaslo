<?php
form(['act' => 'new'],
    input(['type' => 'text', 'name' => 'name', 'placeholder' => 'Логин']),
    input(['type' => 'password', 'placeholder' => 'Пароль']),
    input(['type' => 'email', 'placeholder' => 'Email']),
    "<img src='captcha.php' />",
    input(['type' => 'text', 'name' => 'captcha', 'placeholder' => 'Введите символы из картинки']),
    submit('Присоедениться')
);