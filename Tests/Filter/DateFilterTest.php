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

use Sonata\PropelAdminBundle\Filter\DateFilter;

/**
 * DateFilter tests.
 *
 * @author Kévin Gomez <contact@kevingomez.fr>
 */
class DateFilterTest extends AbstractDateFilterTestCase
{
    protected function getFilterClass(): string
    {
        return DateFilter::class;
    }

    public function testRenderSettingsHasRightName()
    {
        $settings = $this->filter->getRenderSettings();
        $this->assertEquals('sonata_type_filter_date', $settings[0]);
    }
}
