<?php

class Request
{
    protected $number;
    protected $found = false;

    public function __construct($number)
    {
        $this->number = $number;
    }

    public function getNumber()
    {
        return $this->number;
    }

    public function getFound()
    {
        return $this->found;
    }

    public function setFound($found)
    {
        $this->found = $found;
    }
}

interface ChainOfResponsibility
{
    public function addNext(ChainOfResponsibility $chainOfResponsibility);

    public function handle(Request $request);
}

abstract class AbstractRequestHandler implements ChainOfResponsibility
{
    /** @var ChainOfResponsibility */
    protected $successor;

    public function addNext(ChainOfResponsibility $chainOfResponsibility)
    {
        $this->successor = $chainOfResponsibility;
    }

    public function handle(Request $request)
    {
        $this->_handle($request);

        if (!is_null($this->successor)) {
            $this->successor->handle($request);
        }
    }

    abstract protected function _handle(Request $request);
}

class FizzBuzzHandler extends AbstractRequestHandler
{
    protected $number;
    protected $word;

    public function __construct($number, $word)
    {
        $this->number = $number;
        $this->word = $word;
    }

    protected function _handle(Request $request)
    {
        $number = $request->getNumber();
        if ($number % $this->number == 0) {
            echo $this->word;
            $request->setFound(true);
        }
    }
}

class UnfoundNumberHandler extends AbstractRequestHandler
{
    public function _handle(Request $request)
    {
        if (!$request->getFound()) {
            echo $request->getNumber();
        }
        echo "\n";
    }
}

$threeHandler = new FizzBuzzHandler(3, 'Fizz');
$fiveHandler = new FizzBuzzHandler(5, 'Buzz');
$sevenHandler = new FizzBuzzHandler(7, 'Bazz');
$unfoundHandler = new UnfoundNumberHandler();

$threeHandler->addNext($fiveHandler);
$fiveHandler->addNext($sevenHandler);
$sevenHandler->addNext($unfoundHandler);

for ($i = 1; $i <= 16; $i++) {
    $request = new Request($i);
    $threeHandler->handle($request);
}
