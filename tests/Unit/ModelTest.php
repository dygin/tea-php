<?php

namespace AlibabaCloud\Tea\Tests\Unit;

use AlibabaCloud\Tea\Model;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
class ModelTest extends TestCase
{
    public function testToMap()
    {
        $model = new ModelMock();
        $arr   = $model->toMap();

        self::assertEquals('a', $arr['A']);
        self::assertEquals('b', $arr['b']);
    }

    public function testValidate()
    {
        $model = new ModelMock();
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('c is required.');
        $model->validate();
    }

    public function testInit()
    {
        $config = new Config([
            'accessKeyId'     => 'fakeAccessKeyId',
            'accessKeySecret' => 'fakeAccessKeySecret',
        ]);
        $this->assertEquals('fakeAccessKeyId', $config->accessKeyId);
        $this->assertEquals('fakeAccessKeySecret', $config->accessKeySecret);
    }

    public function testToModel()
    {
        $model = Model::toModel([
            'A' => 1,
            'b' => 2,
            'c' => 3,
            'd' => 4,
        ], new ModelMock());
        $this->assertEquals(1, $model->a);
        $this->assertEquals(2, $model->b);
        $this->assertEquals(3, $model->c);
    }

    public function testValidateRequired()
    {
        Model::validateRequired('FieldName', null, false);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('FieldName is required');
        Model::validateRequired('FieldName', null, true);
    }

    public function testValidateMaxLength()
    {
        Model::validateMaxLength('FieldName', 'string', 10);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('FieldName is exceed max-length: 5');
        Model::validateMaxLength('FieldName', 'string', 5);
    }

    public function testValidateMinLength()
    {
        Model::validateMinLength('FieldName', 'string', 5);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('FieldName is less than min-length: 10');
        Model::validateMinLength('FieldName', 'string', 10);
    }

    public function testValidatePattern()
    {
        Model::validatePattern('FieldName', 'string123', '[a-z0-9A-Z]+');

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('FieldName is not match [a-z0-9A-Z]+');
        Model::validatePattern('FieldName', '@string', '[a-z0-9A-Z]+');
    }

    public function testValidatePatternWithEmptyValue()
    {
        Model::validatePattern('FieldName', null, '/^[a-zA-Z0-9_-]+$/');
        Model::validatePattern('FieldName', '', '/^[a-zA-Z0-9_-]+$/');
        // No throws is OK
        self::assertTrue(true);
    }

    public function testValidateMaximum()
    {
        Model::validateMaximum('FieldName', 100, 101);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('FieldName cannot be greater than 99');
        Model::validateMaximum('FieldName', 100, 99);
    }

    public function testValidateMinimum()
    {
        Model::validateMinimum('FieldName', 100, 99);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('FieldName cannot be less than 101');
        Model::validateMinimum('FieldName', 100, 101);
    }
}

class Config extends Model
{
    public $accessKeyId;
    public $accessKeySecret;
}

class ModelMock extends Model
{
    public $a = 'a';
    public $b = 'b';
    public $c = '';

    public function __construct()
    {
        $this->_name['a']     = 'A';
        $this->_required['c'] = true;
        parent::__construct();
    }
}
