<?php

namespace Sonata\PropelAdminBundle\Tests\Model;

class TestModel
{
	public $id;

	public function getId()
	{
		return $this->id;
	}

	public function getPrimaryKey()
	{
		return $this->getId();
	}

	public function hashCode()
	{
		$validPk = null !== $this->getId();

		$validPrimaryKeyFKs = 0;
		$primaryKeyFKs = [];

		if ($validPk) {
			return crc32(json_encode($this->getPrimaryKey(), JSON_UNESCAPED_UNICODE));
		} elseif ($validPrimaryKeyFKs) {
			return crc32(json_encode($primaryKeyFKs, JSON_UNESCAPED_UNICODE));
		}

		return spl_object_hash($this);
	}
}
