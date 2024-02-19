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

use Propel\Runtime\ActiveQuery\ModelCriteria;
use Sonata\Form\Type\BooleanType;
use Sonata\PropelAdminBundle\Filter\BooleanFilter;

/**
 * BooleanFilter tests.
 *
 * @author Kévin Gomez <contact@kevingomez.fr>
 */
class BooleanFilterTest extends AbstractFilterTestCase
{
    const FIELD_NAME = 'published';

    protected function getFilterClass(): string
    {
        return BooleanFilter::class;
    }

    public function validDataProvider(): array
    {
        $yes = array('value' => BooleanType::TYPE_YES);
        $no = array('value' => BooleanType::TYPE_NO);

        return array(
            // data, comparisonType, normalizedData, comparisonOperator, filterOptions
            array($yes, null,   true, ModelCriteria::EQUAL,     array()),
            array($no,  null,   true, ModelCriteria::NOT_EQUAL, array()),
        );
    }
}
