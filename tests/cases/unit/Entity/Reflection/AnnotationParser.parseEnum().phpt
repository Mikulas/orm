<?php

/**
 * @testCase
 */

namespace NextrasTests\Orm\Entity\Reflection;

use Mockery;
use Nextras\Orm\Entity\Reflection\AnnotationParser;
use NextrasTests\Orm\TestCase;
use Tester\Assert;

$dic = require_once __DIR__ . '/../../../../bootstrap.php';


/**
 * @property int $test1 {enum EnumTestEntity::TYPE_ONE}
 * @property int $test2 {enum EnumTestEntity::TYPE_ONE EnumTestEntity::TYPES_THREE}
 * @property int $test3 {enum EnumTestEntity::TYPE_*}
 * @property int $test4 {enum EnumTestEntity::TYPES_* EnumTestEntity::TYPE_ONE}
 * @property int $test5 {enum EnumTestEntity::TYPES_* EnumTestEntity::TYPE_*}
 * @property int $test6 {enum self::TYPE_*}
 * @property int $test7 {enum static::TYPES_THREE}
 * @property int $test8 {enum \NextrasTests\Orm\Entity\Reflection\EnumTestEntity::TYPE_*}
 * @property string $test9 {enum Enum::A Enum::B}
 */
class EnumTestEntity
{
	const TYPE_ONE = 1;
	const TYPE_TWO = 2;

	const TYPES_THREE = 3;
	const TYPES_FOUR = 4;
}

class Enum
{
	const A = 'a';
	const B = 'b';
}

/**
 * @property int $test {enum EnumTestEntity::TYPE_UNKNOWN}
 */
class EnumUnknown1
{}

/**
 * @property int $test {enum EnumTestEntity::UNKNOWN_*}
 */
class EnumUnknown2
{}


class AnnotationParserParseEnumTest extends TestCase
{

	public function testBasics()
	{
		$dependencies = [];
		$parser = new AnnotationParser([]);
		$metadata = $parser->parseMetadata('NextrasTests\Orm\Entity\Reflection\EnumTestEntity', $dependencies);

		Assert::same([1], $metadata->getProperty('test1')->enum);
		Assert::same([1, 3], $metadata->getProperty('test2')->enum);
		Assert::same([1, 2], $metadata->getProperty('test3')->enum);
		Assert::same([3, 4, 1], $metadata->getProperty('test4')->enum);
		Assert::same([3, 4, 1, 2], $metadata->getProperty('test5')->enum);
		Assert::same([1, 2], $metadata->getProperty('test6')->enum);
		Assert::same([3], $metadata->getProperty('test7')->enum);
		Assert::same([1, 2], $metadata->getProperty('test8')->enum);
		Assert::same(['a', 'b'], $metadata->getProperty('test9')->enum);
	}


	public function testUnknown()
	{
		Assert::throws(function () {
			$dependencies = [];
			$parser = new AnnotationParser([]);
			$parser->parseMetadata('NextrasTests\Orm\Entity\Reflection\EnumUnknown1', $dependencies);
		}, 'Nextras\Orm\InvalidArgumentException', 'Constant NextrasTests\Orm\Entity\Reflection\EnumTestEntity::TYPE_UNKNOWN required by enum macro in NextrasTests\Orm\Entity\Reflection\EnumUnknown1::$test not found.');

		Assert::throws(function () {
			$dependencies = [];
			$parser = new AnnotationParser([]);
			$parser->parseMetadata('NextrasTests\Orm\Entity\Reflection\EnumUnknown2', $dependencies);
		}, 'Nextras\Orm\InvalidArgumentException', 'No constant matching NextrasTests\Orm\Entity\Reflection\EnumTestEntity::UNKNOWN_* pattern required by enum macro in NextrasTests\Orm\Entity\Reflection\EnumUnknown2::$test found.');
	}

}


$test = new AnnotationParserParseEnumTest($dic);
$test->run();
