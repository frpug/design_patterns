<?php
class Singleton
{
    private static $instance;

    protected $specialValue;

    public static function getInstance()
    {
        if (is_null(static::$instance)) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    private function __construct()
    {
        $this->specialValue = uniqid('SpecialVal', true);
    }

    public function __toString()
    {
        return $this->specialValue;
    }
}

$instance1 = Singleton::getInstance();
$instance2 = Singleton::getInstance();

echo $instance1 . "\n";
echo $instance2 . "\n";

var_dump($instance1 === $instance2); // prints "true"
