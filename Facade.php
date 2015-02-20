<?php
class Request
{
    public function getUri()
    {
        return '/api/foo';
    }

    public function getHowManyTimes()
    {
        return rand(1, 1000);
    }
}
class Response {
    protected $response;

    public function setResponse($string)
    {
        $this->response = $string;
    }

    public function getResponse()
    {
        return $this->response;
    }
}

class Handler
{
    public function doFoo(Request $request)
    {
        $times = $request->getHowManyTimes();
        $response = new Response();
        $response->setResponse('Foo was here ' . $times . ' times.');
        return $response;
    }
}

interface FacadeInterface
{
    /**
     * @param Request $request
     *
     * @return Response
     */
    public function handle(Request $request);
}

class Facade implements FacadeInterface
{
    /**
     * @var Handler
     */
    private $handler;

    public function __construct(Handler $handler)
    {
        $this->handler = $handler;
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function handle(Request $request)
    {
        if ($request->getUri() == '/api/foo') {
            $response = $this->handler->doFoo($request);
            return $response;
        }
    }
}

$facade = new Facade(new Handler());

$response = $facade->handle(new Request());
echo $response->getResponse(), "\n";
