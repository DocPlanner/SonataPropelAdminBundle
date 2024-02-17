<?php

/*
 * This file is part of the Sonata project.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\PropelAdminBundle\Tests\Builder;

use PHPUnit\Framework\TestCase;
use Sonata\PropelAdminBundle\Builder\DatagridBuilder;
use Symfony\Component\Form\FormFactory;
use Sonata\AdminBundle\Filter\FilterFactoryInterface;
use Sonata\AdminBundle\Guesser\TypeGuesserInterface;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Admin\FieldDescriptionInterface;

/**
 * DatagridBuilder tests.
 *
 * @author Kévin Gomez <contact@kevingomez.fr>
 */
class DatagridBuilderTest extends TestCase
{
    public function testTextFieldsAreMadeSearchable(): void
    {
        $formFactory = $this->getMockBuilder(FormFactory::class)->disableOriginalConstructor()->getMock();
        $filterFactory = $this->createMock(FilterFactoryInterface::class);
        $typeGuesser = $this->createMock(TypeGuesserInterface::class);
        $admin = $this->createMock(AdminInterface::class);
        $fieldDescription = $this->createMock(FieldDescriptionInterface::class);

        $fieldDescription
            ->expects(self::once())
            ->method('getType')
            ->willReturn('text');

        $fieldDescription
            ->expects(self::once())
            ->method('getOption')
            ->with('global_search', true) // we still look the given options
            ->willReturn(true);

        $fieldDescription
            ->expects(self::once())
            ->method('setOption')
            ->with('global_search', true);

        // and test!
        $builder = new DatagridBuilder($formFactory, $filterFactory, $typeGuesser);
        $builder->fixFieldDescription($admin, $fieldDescription);
    }

    public function testNonTextFieldsAreNotMadeSearchable(): void
    {
        $formFactory = $this->getMockBuilder(FormFactory::class)->disableOriginalConstructor()->getMock();
        $filterFactory = $this->createMock(FilterFactoryInterface::class);
        $typeGuesser = $this->createMock(TypeGuesserInterface::class);
        $admin = $this->createMock(AdminInterface::class);
        $fieldDescription = $this->createMock(FieldDescriptionInterface::class);

        $fieldDescription
            ->expects(self::once())
            ->method('getType')
            ->willReturn('integer');

        $fieldDescription
            ->expects($this->never())
            ->method('getOption');

        $fieldDescription
            ->expects($this->never())
            ->method('setOption');

        // and test!
        $builder = new DatagridBuilder($formFactory, $filterFactory, $typeGuesser);
        $builder->fixFieldDescription($admin, $fieldDescription);
    }
}
