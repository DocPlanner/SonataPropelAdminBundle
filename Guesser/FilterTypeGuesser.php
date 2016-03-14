<?php

/*
 * This file is part of the Sonata package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\PropelAdminBundle\Guesser;

use Propel\Bundle\PropelBundle\Form\Type\ModelType;
use Propel\Runtime\Map\RelationMap;
use Propel\Generator\Model\PropelTypes;
use Sonata\AdminBundle\Model\ModelManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormTypeGuesserInterface;
use Symfony\Component\Form\Guess\Guess;
use Symfony\Component\Form\Guess\TypeGuess;
use Symfony\Component\Form\Guess\ValueGuess;
/**
 * Filter type guesser.
 */
class FilterTypeGuesser extends AbstractTypeGuesser
{
	private $cache = array();

	/**
	 * {@inheritDoc}
	 */
	public function guessType($class, $property, ModelManagerInterface $modelManager)
	{
		if (!$table = $this->getTable($class)) {
			return new TypeGuess('string', array(), Guess::LOW_CONFIDENCE);
		}

		foreach ($table->getRelations() as $relation) {
			if ($relation->getType() === RelationMap::MANY_TO_ONE) {
				if (strtolower($property) === strtolower($relation->getName())) {
					return new TypeGuess('model', array(
						'class'    => $relation->getForeignTable()->getClassName(),
						'multiple' => false,
					), Guess::HIGH_CONFIDENCE);
				}
			} elseif ($relation->getType() === RelationMap::ONE_TO_MANY) {
				if (strtolower($property) === strtolower($relation->getPluralName())) {
					return new TypeGuess('model', array(
						'class'    => $relation->getForeignTable()->getClassName(),
						'multiple' => true,
					), Guess::HIGH_CONFIDENCE);
				}
			} elseif ($relation->getType() === RelationMap::MANY_TO_MANY) {
				if (strtolower($property) == strtolower($relation->getPluralName())) {
					return new TypeGuess('model', array(
						'class'     => $relation->getLocalTable()->getClassName(),
						'multiple'  => true,
					), Guess::HIGH_CONFIDENCE);
				}
			}
		}

		if (!$column = $this->getColumn($class, $property)) {
			return new TypeGuess('string', array(), Guess::LOW_CONFIDENCE);
		}

		switch ($column->getType()) {
			case PropelTypes::BOOLEAN:
			case PropelTypes::BOOLEAN_EMU:
				return new TypeGuess('checkbox', array(), Guess::HIGH_CONFIDENCE);
			case PropelTypes::TIMESTAMP:
			case PropelTypes::BU_TIMESTAMP:
				return new TypeGuess('datetime', array(), Guess::HIGH_CONFIDENCE);
			case PropelTypes::DATE:
			case PropelTypes::BU_DATE:
				return new TypeGuess('datetime', array(), Guess::HIGH_CONFIDENCE);
			case PropelTypes::TIME:
				return new TypeGuess('time', array(), Guess::HIGH_CONFIDENCE);
			case PropelTypes::FLOAT:
			case PropelTypes::REAL:
			case PropelTypes::DOUBLE:
			case PropelTypes::DECIMAL:
				return new TypeGuess('number', array(), Guess::MEDIUM_CONFIDENCE);
			case PropelTypes::TINYINT:
			case PropelTypes::SMALLINT:
			case PropelTypes::INTEGER:
			case PropelTypes::BIGINT:
			case PropelTypes::NUMERIC:
				return new TypeGuess('integer', array(), Guess::MEDIUM_CONFIDENCE);
			case PropelTypes::ENUM:
			case PropelTypes::CHAR:
				if ($column->getValueSet()) {
					//check if this is mysql enum
					$choices = $column->getValueSet();
					$labels = array_map('ucfirst', $choices);

					return new TypeGuess(ChoiceType::class, array('choices' => array_combine($choices, $labels)), Guess::MEDIUM_CONFIDENCE);
				}
			case PropelTypes::VARCHAR:
				return new TypeGuess('string', array(), Guess::MEDIUM_CONFIDENCE);
			case PropelTypes::LONGVARCHAR:
			case PropelTypes::BLOB:
			case PropelTypes::CLOB:
			case PropelTypes::CLOB_EMU:
				return new TypeGuess('string', array(), Guess::MEDIUM_CONFIDENCE);
			default:
				return new TypeGuess('string', array(), Guess::LOW_CONFIDENCE);
		}
	}

	/**
	 * {@inheritDoc}
	 */
	public function guessRequired($class, $property)
	{
		if ($column = $this->getColumn($class, $property)) {
			return new ValueGuess($column->isNotNull(), Guess::HIGH_CONFIDENCE);
		}
	}

	/**
	 * {@inheritDoc}
	 */
	public function guessMaxLength($class, $property)
	{
		if ($column = $this->getColumn($class, $property)) {
			if ($column->isText()) {
				return new ValueGuess($column->getSize(), Guess::HIGH_CONFIDENCE);
			}
			switch ($column->getType()) {
				case PropelTypes::FLOAT:
				case PropelTypes::REAL:
				case PropelTypes::DOUBLE:
				case PropelTypes::DECIMAL:
					return new ValueGuess(null, Guess::MEDIUM_CONFIDENCE);
			}
		}
	}

	/**
	 * {@inheritDoc}
	 */
	public function guessPattern($class, $property)
	{
		if ($column = $this->getColumn($class, $property)) {
			switch ($column->getType()) {
				case PropelTypes::FLOAT:
				case PropelTypes::REAL:
				case PropelTypes::DOUBLE:
				case PropelTypes::DECIMAL:
					return new ValueGuess(null, Guess::MEDIUM_CONFIDENCE);
			}
		}
	}

	protected function getTable($class)
	{
		if (isset($this->cache[$class])) {
			return $this->cache[$class];
		}

		if (class_exists($queryClass = $class.'Query')) {
			$query = new $queryClass();

			return $this->cache[$class] = $query->getTableMap();
		}
	}

	protected function getColumn($class, $property)
	{
		if (isset($this->cache[$class.'::'.$property])) {
			return $this->cache[$class.'::'.$property];
		}

		$table = $this->getTable($class);

		if ($table && $table->hasColumn($property)) {
			return $this->cache[$class.'::'.$property] = $table->getColumn($property);
		}
	}
}
