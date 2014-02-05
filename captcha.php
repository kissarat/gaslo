<?php
require 'Text/CAPTCHA.php';
require 'base/init.php';

$captcha_options = [
    'width'  => 200,
    'height' => 80,
    'output' => 'png',
    'imageOptions' => [
        'font_size'        => 24,
        'font_path'        => './font/',
        'font_file'        => 'captcha.ttf',
        'text_color'       => '#DDFF99',
        'lines_color'      => '#CCEEDD',
        'background_color' => '#555555'
    ]
];

$captcha = Text_CAPTCHA::factory('Image');
$captcha->init($captcha_options);

header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');
header('Content-Type: image/png');

echo $captcha->getCAPTCHA();
remember([
    'captcha' => $captcha->getPhrase()
]);
