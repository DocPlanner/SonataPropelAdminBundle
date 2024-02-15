<?php

/**
 * This file is part of the Sonata project.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sonata\PropelAdminBundle\Tests\Filter;

use Propel\Runtime\ActiveQuery\ModelCriteria;
use Sonata\AdminBundle\Form\Type\Filter\NumberType;
use Sonata\PropelAdminBundle\Filter\NumberFilter;

/**
 * Class NumberFilterTest.
 *
 * @author  Pavel Sidorovich
 *
 * @since   2014-02-27
 */
class NumberFilterTest extends AbstractFilterTestCase
{
    const FIELD_NAME = 'id';

    protected function getFilterClass(): string
    {
        return NumberFilter::class;
    }

    public function validDataProvider(): array
    {
        $data = array('value' => 42);

        return array(
            //    data,  comparisonType,                 normalizedData, comparisonOperator,           filterOptions
            array($data, null,                           42,             ModelCriteria::EQUAL,         array()),
            array($data, '',                             42,             ModelCriteria::EQUAL,         array()),
            array($data, NumberType::TYPE_EQUAL,         42,             ModelCriteria::EQUAL,         array()),
            array($data, NumberType::TYPE_GREATER_EQUAL, 42,             ModelCriteria::GREATER_EQUAL, array()),
            array($data, NumberType::TYPE_GREATER_THAN,  42,             ModelCriteria::GREATER_THAN,  array()),
            array($data, NumberType::TYPE_LESS_EQUAL,    42,             ModelCriteria::LESS_EQUAL,    array()),
            array($data, NumberType::TYPE_LESS_THAN,     42,             ModelCriteria::LESS_THAN,     array()),
        );
    }
}
