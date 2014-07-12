<?php

namespace Nextras\Orm\Tests\Entity\Reflection;

use Mockery;
use Nette\Utils\ArrayHash;
use Nette\Utils\DateTime;
use Nextras\Orm\Entity\PropertyContainers\DateTimePropertyContainer;
use Nextras\Orm\Entity\Reflection\AnnotationParser;
use Nextras\Orm\Entity\Reflection\EntityMetadata;
use Nextras\Orm\Entity\Reflection\PropertyMetadata;
use Nextras\Orm\Tests\TestCase;
use Tester\Assert;


$dic = require_once __DIR__ . '/../../../bootstrap.php';


/**
 * @property int $test {enum self::TYPE_*}
 * @property string $string
 * @property int $int
 * @property bool $boolean
 * @property float $float
 * @property datetime $datetime
 * @property array $array1
 * @property int[] $array2
 * @property object $object
 * @property scalar $scalar
 * @property ArrayHash $type
 */
class ValidationTestEntity
{
	const TYPE_ZERO = 0;
	const TYPE_ONE = 1;
	const TYPE_TWO = 2;
}


/**
 * @testCase
 */
class PropertyMetadataIsValidTest extends TestCase
{
	/** @var EntityMetadata */
	private $metadata;


	protected function setUp()
	{
		parent::setUp();
		$parser = new AnnotationParser('Nextras\Orm\Tests\Entity\Reflection\ValidationTestEntity');
		$dp = [];
		$this->metadata = $parser->getMetadata($dp);
	}


	public function testDateTime()
	{
		$property = $this->metadata->getProperty('datetime');

		$val = new \DateTime();
		Assert::true($property->isValid($val));
		$val = new DateTime(); // Nette\Utils\DateTime
		Assert::true($property->isValid($val));
		$val = new \DateTimeImmutable();
		Assert::true($property->isValid($val));

		$val = '';
		Assert::false($property->isValid($val));

		$val = 'now';
		Assert::true($property->isValid($val));
		Assert::type('Nette\Utils\DateTime', $val);
		$val = time();
		Assert::true($property->isValid($val));
		Assert::type('Nette\Utils\DateTime', $val);
		$val = (float) time();
		Assert::true($property->isValid($val));
		Assert::type('Nette\Utils\DateTime', $val);
	}


	public function testEnum()
	{
		$test1 = $this->metadata->getProperty('test');

		$val = 0;
		Assert::true($test1->isValid($val));
		$val = 1;
		Assert::true($test1->isValid($val));
		$val = 2;
		Assert::true($test1->isValid($val));

		$val = 3;
		Assert::false($test1->isValid($val));
		$val = NULL;
		Assert::false($test1->isValid($val));
		$val = 'a';
		Assert::false($test1->isValid($val));
		$val = '1a';
		Assert::false($test1->isValid($val));
		$val = '0';
		Assert::false($test1->isValid($val));
	}

}


$test = new PropertyMetadataIsValidTest($dic);
$test->run();
