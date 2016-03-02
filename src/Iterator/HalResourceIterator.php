<?php
namespace Riskio\HalGuzzleClient\Iterator;

use Guzzle\Service\Resource\ResourceIterator;

class HalResourceIterator extends ResourceIterator
{
    /**
     * @var int
     */
    protected $page = 1;

    /**
     * {@inheritDoc}
     */
    protected function sendRequest()
    {
        if ($this->nextToken) {
            $this->command['page'] = $this->page;
        }

        $result   = $this->command->execute();
        $embedded = $result['_embedded'];
        $data = array_shift($embedded);

        if (
            empty($result['page_count'])
            || empty($result['page_count'])
            || $result['page_count'] == $result['page']
        ) {
            $this->nextToken = false;
        } else {
            $this->nextToken = true;
            $this->page = $result['page'] + 1;
        }

        return $data;
    }
}
