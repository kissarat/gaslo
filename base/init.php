<?php

$host = 'localhost';
$user = 'postgres';
$password = '1';
$database = 'gaslo';
$mem_host = $host;
$mem_port = 11211;

$pdo = new PDO("pgsql:dbname=$database;host=$host", $user, $password);
$mem = new Memcached();
$mem->addServer($mem_host, $mem_port);
define('ROOT',  dirname(__DIR__));

// DEBUG
define('DEBUG', true);
define('REX_NAME', '/^[\w\d_]{4,16}$/i');


function cookie_dump($assoc, $prefix = null) {
    foreach($assoc as $key => $value) {
        switch (gettype($value)) {
            case 'string': $value = trim($value); break;
            case 'boolean': $value = $value ? 'true' : 'false'; break;
        }
        if ($prefix)
            $key = "$prefix.$key";
        setcookie($key, $value, 0);
    }
}

if (DEBUG) {
    register_shutdown_function(function() {
        cookie_dump(auth(), 'subject');
    });
}

function fetch_single() {
    global $pdo;
    $args = func_get_args();
    $st = $pdo->prepare(array_shift($args));
    $st->execute($args);
    if ($st->rowCount() == 0)
        return null;
    $assoc = $st->fetch(PDO::FETCH_ASSOC);
    foreach($assoc as $key => $value)
        if ('string' == gettype($value))
            $assoc[$key] = trim($value);
    return $assoc;
}

function sql_exec() {
    global $pdo;
    $args = func_get_args();
    $st = $pdo->prepare(array_shift($args));
    $st->execute($args);
}
function repeat($s, $n) {
    $result = [];
    for ($i=0; $i<$n; $i++)
        array_push($result, $s);
    return $result;
}

function insert($table, $assoc) {
    global $pdo;
    $keys = array_keys($assoc);
    $keys = join(',', $keys);
    $placeholders = repeat('?', count($keys));
    $placeholders = join(',', $placeholders);
    $st = $pdo->prepare("insert into $table($keys) values ($placeholders)");
    $st->execute(array_values($assoc));
}

function remember($assoc = null) {
    global $subject, $mem;
    if (!$subject)
        $subject = auth();
    if ($assoc) foreach($assoc as $key => $value)
        $subject[$key] = $value;
    $mem->set($subject['salt'], $subject, $subject['alive']);
}

function auth() {
    global $subject, $mem;
    if ($subject)
        return $subject;
    elseif (isset($_COOKIE['auth'])) {
        $salt = $_COOKIE['auth'];
        if ($subject = $mem->get($salt))
            return $subject;
        $subject = fetch_single('select * from subject where salt=?', $salt);
    }
    if (!$subject) {
        $subject = fetch_single('select * from subject where name=?', 'guest');
        $subject['salt'] = md5(rand());
        setcookie('auth', $subject['salt']);
    }
    remember();
    return $subject;
}

$errors = [];
$warnings = [];

set_error_handler(function($errno, $errstr, $errfile, $errline) {
    global $errors, $warnings;
    $errfile = str_replace(ROOT, '', $errfile);
    $message = "<i>$errfile on $errline:</i> $errstr";
    switch($errno) {
        case E_USER_WARNING:
        case E_USER_NOTICE:
            array_push($warnings, $message);
            break;
        //case E_USER_ERROR:
        default:
            array_push($errors, $message);
            break;
    }
    return true;
});

function is_guest() {
    $subject = auth();
    return 'guest' == $subject['name'];
}