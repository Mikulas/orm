<?php

/**
 * This file is part of the Nextras\ORM library.
 *
 * @license    MIT
 * @link       https://github.com/nextras/orm
 * @author     Jan Skrasek
 */

namespace Nextras\Orm\Relationships;


class ManyHasOne extends HasOne
{

	protected function updateRelationship($oldEntity, $newEntity, $allowNull)
	{
		$this->updatingReverseRelationship = TRUE;
		$key = $this->propertyMeta->relationshipProperty;

		if ($oldEntity) {
			$oldEntity->getValue($key)->remove($this->parent, $allowNull);
		}

		if ($newEntity) {
			$newEntity->getValue($key)->add($this->parent);
		}
		$this->updatingReverseRelationship = FALSE;
	}

}
