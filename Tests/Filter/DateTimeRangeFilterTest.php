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

use Sonata\AdminBundle\Form\Type\Filter\DateTimeRangeType;

/**
 * DateTimeRangeFilter tests.
 *
 * @author Kévin Gomez <contact@kevingomez.fr>
 */
class DateTimeRangeFilterTest extends AbstractDateRangeFilterTestCase
{
    protected function getFilterClass()
    {
        return '\Sonata\PropelAdminBundle\Filter\DateTimeRangeFilter';
    }

    public function testRenderSettingsHasRightName()
    {
        $settings = $this->filter->getRenderSettings();
        self::assertEquals(DateTimeRangeType::class, $settings[0]);
    }
}
