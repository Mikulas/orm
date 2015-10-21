<?php

/**
 * This file is part of the Nextras\Orm library.
 * @license    MIT
 * @link       https://github.com/nextras/orm
 */

namespace Nextras\Orm\Entity;


interface IProperty
{

	/**
	 * Sets raw value.
	 * @param  mixed $value
	 */
	public function setRawValue($value);


	/**
	 * Returns raw value.
	 * Raw value is normalized value which is suitable unique identification and storing.
	 * @return mixed
	 */
	public function getRawValue();

}
