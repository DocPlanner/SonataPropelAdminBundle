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

use PHPUnit\Framework\TestCase;
use Sonata\PropelAdminBundle\Datagrid\ProxyQuery;
use Sonata\PropelAdminBundle\Model\ModelManager;

/**
 * Base class for filter tests.
 *
 * @author Kévin Gomez <contact@kevingomez.fr>
 */
abstract class AbstractFilterTestCase extends TestCase
{
    const FIELD_NAME = 'some_field';

    protected $filter;

    abstract protected function getFilterClass(): string;
    abstract public function validDataProvider(): array;

    public function setUp(): void
    {
        $this->filter = $this->getFilter(self::FIELD_NAME);
    }

    public function testApplyWithInvalidQuery(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('The given query is not supported by this filter.');

        $this->filter->apply('not a query', 'foo');
    }

    /**
     * @dataProvider invalidDataProvider
     */
    public function testApplyWithInvalidDataDoesNothing($value): void
    {
        $query = $this->getQueryMock();

        $this->filter->apply($query, $value);
        $this->assertFalse($this->filter->isActive());
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testApplyWithValidData($data, $comparisonType, $normalizedData, $comparisonOperator, $filterOptions): void
    {
        $data = array_merge($data, array('type' => $comparisonType));

        $query = $this->getQueryMock();
        $query->expects($this->once())
               ->method('filterBy')
               ->with(
                   $this->equalTo(self::FIELD_NAME),
                   $this->equalTo($normalizedData),
                   $this->equalTo($comparisonOperator)
               );

        foreach ($filterOptions as $name => $value) {
            $this->filter->setOption($name, $value);
        }

        $this->filter->apply($query, $data);
        $this->assertTrue($this->filter->isActive());
    }

    public function invalidDataProvider(): array
    {
        return array(
            array(null),
            array('string'),
            array(42),
            array(array('foo' => 'dummy value')),
        );
    }

    protected function getQueryMock()
    {
        $query = $this->getMockBuilder(ProxyQuery::class)
            ->disableOriginalConstructor()
            ->setMethods(array('filterBy', 'getModelName'))
            ->getMock();

        $query
            ->expects($this->any())
            ->method('getModelName')
            ->willReturn('\Foo\Model\Bar');

        return $query;
    }

    protected function getModelManagerMock()
    {
        $manager = $this->getMockBuilder(ModelManager::class)
            ->setMethods(array('translateFieldName'))
            ->getMock();

        return $manager;
    }

    protected function getFilter($fieldName)
    {
        $modelManager = $this->getModelManagerMock();

        $filterClass = $this->getFilterClass();
        $filter = new $filterClass($modelManager);

        $modelManager->expects($this->any())
               ->method('translateFieldName')
               ->with($this->anything(), $this->equalTo($fieldName))
               ->willReturn($fieldName);

        $filter->initialize('filter', array(
            'field_name' => $fieldName,
        ));

        return $filter;
    }
}
