<?php declare(strict_types=1);

namespace Coccoc\Validation\Tests;

use Coccoc\Validation\Validation;
use Coccoc\Validation\Factory;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * Class ValidationTest
 *
 * @package    Coccoc\Validation\Tests
 * @subpackage Coccoc\Validation\Tests\ValidationTest
 */
class ValidationTest extends TestCase
{
    /**
     * @param string $rules
     * @param array $expectedResult
     *
     * @dataProvider parseRuleProvider
     */
    public function testParseRule($rules, $expectedResult)
    {
        $class = new ReflectionClass(Validation::class);
        $method = $class->getMethod('parseRule');
        $method->setAccessible(true);

        $validation = new Validation(new Factory(), [], []);

        $result = $method->invokeArgs($validation, [$rules]);
        $this->assertSame($expectedResult, $result);
    }

    public function parseRuleProvider(): array
    {
        return [
            [
                'email',
                [
                    'email',
                    [],
                ],
            ],
            [
                'min:6',
                [
                    'min',
                    ['6'],
                ],
            ],
            [
                'uploaded_file:0,500K,png,jpeg',
                [
                    'uploaded_file',
                    ['0', '500K', 'png', 'jpeg'],
                ],
            ],
            [
                'same:password',
                [
                    'same',
                    ['password'],
                ],
            ],
            [
                'regex:/^([a-zA-Z\,]*)$/',
                [
                    'regex',
                    ['/^([a-zA-Z\,]*)$/'],
                ],
            ],
        ];
    }
}
