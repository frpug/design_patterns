<?php
class Request {}

interface SomeInterface
{
    public function initialize();

    public function performAction(Request $request);
}

interface AnotherInterface
{
    public function doSomething(Request $request);
}

class Complicated implements SomeInterface
{
    protected $secretValue;

    public function initialize()
    {
        $this->secretValue = uniqid('SecretValue ', true);
    }

    public function performAction(Request $request)
    {
        // Not touching request, but we could
        echo $this->secretValue, "\n";
    }
}

class Adapter implements AnotherInterface
{
    protected $internal;

    public function __construct(SomeInterface $internal)
    {
        $this->internal = $internal;
    }

    public function doSomething(Request $request)
    {
        $this->internal->initialize();
        return $this->internal->performAction($request);
    }
}

$complicatedObject = new Complicated();
$adapter = new Adapter($complicatedObject);

$adapter->doSomething(new Request());
