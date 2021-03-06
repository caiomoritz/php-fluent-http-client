<?php

namespace OLX\FluentHttpClient\Response\Assertion;

use OLX\FluentHttpClient\Response\ResponseHolder;
use OLX\FluentHttpClient\Exception\UnexpectedValueException;
use PHPUnit_Framework_MockObject_MockObject;

class JsonBodyAssertionTest extends \PHPUnit_Framework_TestCase
{
    /** @var  PHPUnit_Framework_MockObject_MockObject */
    private $responseHolder;

    public function setUp()
    {
        $this->responseHolder = $this->getMockWithoutInvokingTheOriginalConstructor(ResponseHolder::class);
    }

    public function testJsonBody()
    {
        $bodyContents = json_encode(
            [
                'bar' => 'lalala',
                'foo' => 'lalala',
                'fooBar' => 'whatever',
                'baz' => [
                    'numberz' => 123,
                    'cha-ching' => 'lalala',
                    'bazinga' => 'lalala'
                ]
            ],
            JSON_PRETTY_PRINT
        );

        $this->responseHolder->method('getBody')->willReturn($bodyContents);

        $this->assertTrue((new JsonBodyAssertion())->assert($this->responseHolder));
    }

    public function testNotJsonBody()
    {
        $this->responseHolder->method('getBody')->willReturn('fooBar');

        $this->setExpectedException(UnexpectedValueException::class);
        (new JsonBodyAssertion())->assert($this->responseHolder);
    }
}
