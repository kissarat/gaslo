<?php

class Entity {
    public static $pdo;
    public static $mem;
    protected $_name;
    protected $_fields;
    protected $_action;
    const CREATE = 0;
    const READ = 1;
    const UPDATE = 2;

    function Entity($action = self::READ) {
        $this->_action = $action;
    }

    function store() {
        $keys = array_keys($this->_fields);
        if (self::CREATE == $this->_action) {
            $keys = join(',', $keys);
            $values = array_fill(0, count($keys), '?');
            $values = join(',', $values);
            $pre = self::$pdo->prepare("insert into $this->_name ($keys) values ($values)");
            $pre->execute(array_values($this->_fields));
        } elseif (self::UPDATE == $this->_action) {

        }
    }
}

Entity::$pdo = new PDO('pgsql:dbname=gaslo;host=localhost', 'postgres', '1');
Entity::$mem = new Memcached();