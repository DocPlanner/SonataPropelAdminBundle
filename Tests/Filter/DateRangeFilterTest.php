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

use Sonata\AdminBundle\Form\Type\Filter\DateRangeType;

/**
 * DateRangeFilter tests.
 *
 * @author Kévin Gomez <contact@kevingomez.fr>
 */
class DateRangeFilterTest extends AbstractDateRangeFilterTestCase
{
    protected function getFilterClass()
    {
        return '\Sonata\PropelAdminBundle\Filter\DateRangeFilter';
    }

    public function testRenderSettingsHasRightName()
    {
        $settings = $this->filter->getRenderSettings();
        self::assertEquals(DateRangeType::class, $settings[0]);
    }
}
