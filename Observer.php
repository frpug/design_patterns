<?php

abstract class Observer implements SplObserver
{
    abstract public function update(SplSubject $subject);
}

abstract class Subject implements SplSubject
{
    /** @var SplObjectStorage */
    protected $observers;

    public function __construct()
    {
        $this->observers = new SplObjectStorage();
    }

    public function attach(SplObserver $observer)
    {
        $this->observers->attach($observer);
    }

    public function detach(SplObserver $observer)
    {
        $this->observers->detach($observer);
    }

    public function notify()
    {
        /** @var SplObserver $observer */
        foreach ($this->observers as $observer) {
            $observer->update($this);
        }
    }
}

class Logger extends Observer
{
    public function update(SplSubject $subject)
    {
        echo 'The object has changed: ' . $subject . "\n";
    }
}

class PasswordObserver extends Observer
{
    public function update(SplSubject $subject)
    {
        if ($subject instanceof ValueHolder) {
            if (isset($subject->password)) {
                echo "\nERROR: Tried to store password.\n\n";
            }
        }
    }
}

class ValueHolder extends Subject
{
    protected $values;

    public function __set($name, $value)
    {
        $this->values[$name] = $value;
        $this->notify();
    }

    public function __get($name)
    {
        return $this->values[$name];
    }

    public function __isset($name)
    {
        return isset($this->values[$name]);
    }

    public function __toString()
    {
        $return = [];
        foreach ($this->values as $key => $value) {
            $return[] = $key . ' - ' . $value;
        }

        return join(', ', $return);
    }
}

$values = new ValueHolder();

$values->attach(new PasswordObserver());
$values->attach(new Logger());

$values->php = 'Front Range PHP User Group';
$values->joomla = 'Joomla! Denver';
$values->java = 'Denver Java User Group';
$values->password = 'Super secret!';
