<?php

namespace Anse\Monolog\Gdpr\Processor;

use Monolog\Handler\TestHandler;
use Monolog\Logger;

class RedactIpProcessorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var TestHandler
     */
    private $handler;

    /**
     * @var Logger
     */
    private $logger;

    public function setUp()
    {
        $processor = new RedactIpProcessor();

        $this->handler = new TestHandler();
        $this->logger = new Logger('test', [$this->handler]);
        $this->logger->pushProcessor($processor);

        parent::setUp();
    }

    public function testIpIsRedacted()
    {
        $this->logger->log(Logger::DEBUG, 'This is a test for 127.0.0.1', ['foo' => ['bar' => '127.0.0.1']]);
        $records = $this->handler->getRecords();

        $this->assertEquals('This is a test for 4b84b15bff6ee5796152495a230e45e3d7e947d9', $records[0]['message']);
        $this->assertEquals(
            [
                'foo' => [
                    'bar' => '4b84b15bff6ee5796152495a230e45e3d7e947d9'
                ]
            ],
            $records[0]['context']
        );
    }
}
