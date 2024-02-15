<?php

/*
 * This file is part of the Sonata project.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\PropelAdminBundle\Tests\Filter;

use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Collection\ObjectCollection;
use Sonata\AdminBundle\Form\Type\Filter\ChoiceType;
use Sonata\PropelAdminBundle\Filter\ModelFilter;

/**
 * ModelFilter tests.
 *
 * @author Kévin Gomez <contact@kevingomez.fr>
 */
class ModelFilterTest extends AbstractFilterTestCase
{
    protected function getFilterClass(): string
    {
        return ModelFilter::class;
    }

    public function validDataProvider(): array
    {
        $user = new User();
        $user->id = 42;

        $collection = new ObjectCollection();
        $collection->append($user);

        $data = array('value' => $user);
        $dataCollection = array('value' => $collection);

        return array(
            //    data,                 comparisonType,                 normalizedData, comparisonOperator, filterOptions
            array($data,                ChoiceType::TYPE_CONTAINS,      $user,          Criteria::IN,       array()),
            array($data,                ChoiceType::TYPE_NOT_CONTAINS,  $user,          Criteria::NOT_IN,   array()),
            array($data,                ChoiceType::TYPE_EQUAL,         $user,          Criteria::EQUAL,    array()),
            array($data,                '',                             $user,          Criteria::IN,    array()),
            array($data,                null,                           $user,          Criteria::IN,       array()),
            array(array('value' => 42), null,                           42,             Criteria::IN,       array()),
            array($dataCollection,      null,                           $collection,    Criteria::IN,       array()),
        );
    }
}

/**
 * @codeCoverageIgnore
 */
class User
{
    public $id;

    public function getId()
    {
        return $this->id;
    }

    public function hashCode()
    {

    }
}
