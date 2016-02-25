<?php
namespace Riskio\HalGuzzleClientTest;

use Guzzle\Service\Command\CommandInterface;
use Guzzle\Service\Command\Factory\FactoryInterface as CommandFactoryInterface;
use Riskio\HalGuzzleClient\Client;
use Riskio\HalGuzzleClient\Iterator\HalResourceIterator;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ClientTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function magicMethodCall_GivenMethodNameWithIteratorSuffix_ShouldReturnHalResourceIteratorInstance()
    {
        $commandName = 'getItems';
        $commandOptions = ['foo' => 123];

        $client = new Client();

        $commandFactoryMock = $this->getMock(CommandFactoryInterface::class);
        $client->setCommandFactory($commandFactoryMock);

        $eventDispacherMock = $this->getMock(EventDispatcherInterface::class);
        $client->setEventDispatcher($eventDispacherMock);

        $commandMock = $this->getMock(CommandInterface::class);
        $commandFactoryMock
            ->method('factory')
            ->with($commandName, $commandOptions)
            ->will($this->returnValue($commandMock));

        $method = $commandName . 'Iterator';
        $result = $client->{$method}($commandOptions);

        $this->assertThat($result, $this->isInstanceOf(HalResourceIterator::class));
    }
}
