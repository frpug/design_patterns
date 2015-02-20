<?php
interface FactoryInterface
{
    public function createService(ServiceManager $serviceManager);
}

class RandomServiceFactory implements FactoryInterface
{
    public function createService(ServiceManager $serviceManager)
    {
        $randomProvider = $serviceManager->get(RandomProvider::class);

        $randomService = new \My\RandomNamespace\RandomService($randomProvider);

        $config = $serviceManager->get('Config');
        $randomService->setIntervalLevel($config['randomSettings']['randomInterval']);
        $randomService->setSeedDevice($config['randomSettings']['seedDevice']);

        return $randomService;
    }
}
