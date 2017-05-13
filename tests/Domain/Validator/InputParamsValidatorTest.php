<?php

namespace GYG\Domain\Validator;


use PHPUnit\Framework\TestCase;

class InputParamsValidatorTest extends TestCase
{

    public function testValidateWithAllParamsOk()
    {
        $params = [
            'solution.php',
            'http://www.mocky.io/v2/58ff37f2110000070cf5ff16',
            '2017-11-20T09:30',
            '2017-11-20T09:30',
            3,
        ];
        $response = InputParamsValidator::validate($params);
        $this->assertTrue($response);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testValidateWithMissingParams()
    {
        $params = [
            'solution.php',
            'http://www.mocky.io/v2/58ff37f2110000070cf5ff16',
            '2017-11-20T09:30',

        ];
        InputParamsValidator::validate($params);
    }

    /**
     * @expectedException \UnexpectedValueException
     */
    public function testValidateInvalidUrl()
    {
        $params = [
            'solution.php',
            'www.mocky.io/v2/58ff37f2110000070cf5ff16',
            '2017-11-20T09:30',
            '2017-11-20T09:30',
            3,
        ];
        InputParamsValidator::validate($params);
    }

    /**
     * @expectedException \UnexpectedValueException
     */
    public function testValidateInvalidTravelers()
    {
        $params = [
            'solution.php',
            'http://www.mocky.io/v2/58ff37f2110000070cf5ff16',
            '2017-11-20T09:30',
            '2017-11-20T09:30',
            'aa',
        ];
        $res = InputParamsValidator::validate($params);
        var_dump($res);
    }
}