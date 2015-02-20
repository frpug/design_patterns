<?php

class Request
{
    protected $number;

    public function __construct($number)
    {
        $this->number = $number;
    }

    public function getNumber()
    {
        return $this->number;
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

    abstract public function _handle(Request $request);
}

class EvenHandler extends AbstractRequestHandler
{
    public function _handle(Request $request)
    {
        $number = $request->getNumber();
        if ($number % 2 == 0) {
            echo "The number " . $number . " is even.\n";
        }
    }
}

class OddHandler extends AbstractRequestHandler
{
    public function _handle(Request $request)
    {
        $number = $request->getNumber();
        if ($number % 2 == 1) {
            echo "The number " . $number . " is odd.\n";
        }
    }
}

$chain = new EvenHandler();
$chain->addNext(new OddHandler());

for ($i = 0; $i < 10; $i++) {
    $request = new Request($i);
    $chain->handle($request);
}
