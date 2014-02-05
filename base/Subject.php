<?php
require_once 'Entity.php';

class Subject extends Entity {
    static $subject;

    static function login() {
        if (isset($_POST['name']))
            if (isset($_POST['password'])) {
                $st = self::$pdo->prepare('select * from subject where name=?');
                $st->execute([$_POST['name']]);
                if ($st->rowCount() > 0) {
                    $subject = $st->fetch(PDO::FETCH_ASSOC);
                    if (hash('md5', $_POST['password']) == $subject['password']) {
                        if ($subject['verified']) {
                            $expire = $subject['expire'];
                            if (!$expire)
                                $expire = $subject['alive'];
                            setcookie('auth', $subject['salt'], time() + $expire);
                        }
                    }
                }
            }
    }

    static function auth() {
        if (self::$subject)
            return self::$subject;
        elseif (isset($_COOKIE['auth'])) {
            $salt = $_COOKIE['auth'];
            if (self::$subject = self::$mem->get($salt))
                return self::$subject;
            $st = self::$pdo->prepare('select * from subject where salt=?');
            $st->execute($salt);
            self::$subject = $st->fetch(PDO::FETCH_ASSOC);
            self::$mem->add(self::$subject['salt'], self::$subject, self::$subject['alive']);
        }
    }

    function setName($name) {

    }
} 