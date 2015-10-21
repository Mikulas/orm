<?php

/**
 * This file is part of the Nextras\Orm library.
 * @license    MIT
 * @link       https://github.com/nextras/orm
 */

namespace Nextras\Orm\Entity;

use Nextras\Orm\Entity\Reflection\PropertyMetadata;


interface IInstantiableProperty
{

	public function __construct(IEntity $entity, PropertyMetadata $propertyMetadata);

}
