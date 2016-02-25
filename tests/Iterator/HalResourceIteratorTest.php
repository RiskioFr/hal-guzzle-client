<?php
namespace Riskio\HalGuzzleClientTest\Iterator;

use Guzzle\Service\Command\CommandInterface;
use Riskio\HalGuzzleClient\Iterator\HalResourceIterator;

class HalResourceIteratorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function iterate_GivenResponseDataWithOneResource_ShouldReturnResourceData()
    {
        $responseData = [
            '_embedded' => [
                'items' => [
                    ['foo' => 123],
                ],
            ],
            'page_count' => 1,
            'page' => 1,
        ];

        $commandMock = $this->getMock(CommandInterface::class);
        $commandMock
            ->method('execute')
            ->willReturn($responseData);

        $iterator = new HalResourceIterator($commandMock);
        $iterator->next();

        $this->assertThat(
            $iterator->current(),
            $this->equalTo($responseData['_embedded']['items'][0])
        );
        $this->assertThat(
            $responseData['_embedded']['items'],
            $this->countOf($iterator->count())
        );
    }
}
