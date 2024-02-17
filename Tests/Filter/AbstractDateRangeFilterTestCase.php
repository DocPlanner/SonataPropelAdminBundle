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
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Sonata\AdminBundle\Form\Type\Filter\DateRangeType;
use Sonata\PropelAdminBundle\Datagrid\ProxyQuery;
use Sonata\PropelAdminBundle\Model\ModelManager;

/**
 * DateRangeFilter base tests.
 *
 * @author Kévin Gomez <contact@kevingomez.fr>
 */
abstract class AbstractDateRangeFilterTestCase extends TestCase
{
    const FIELD_NAME = 'created_at';

    protected $filter;

    abstract protected function getFilterClass();

    public function setUp(): void
    {
        $this->filter = $this->getFilter(self::FIELD_NAME);
    }

    public function testApplyWithInvalidQuery(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('The given query is not supported by this filter.');

        $this->filter->apply('not a query', new \DateTime());
    }

    /**
     * @dataProvider invalidDataProvider
     */
    public function testApplyWithInvalidDataDoesNothing($value, $filterValid): void
    {
        $query = $this->getQueryMock();
        $query->expects($this->never())
               ->method('filterBy');

        $this->filter->apply($query, $value);

        if ($filterValid) {
            self::assertTrue($this->filter->isActive(), 'The filter is active but the query should not be altered');
        } else {
            self::assertFalse($this->filter->isActive(), 'The filter is not active.');
        }
    }

    /**
     * @dataProvider betweenDataProvider
     */
    public function testApplyBetweenWithValidData($data, $comparisonType, $startNormalizedData, $endNormalizedData, $startComparisonOperator, $endComparisonOperator, $filterOptions): void
    {
        $data = array_merge($data, array('type' => $comparisonType));
        $query = $this->getQueryMock();

        $query->expects(self::atLeastOnce())
               ->method('getModelName')
               ->willReturnOnConsecutiveCalls('not null');

        $query->expects(self::exactly(2))
            ->method('filterBy')
            ->withConsecutive(
                [self::FIELD_NAME, $startNormalizedData, $startComparisonOperator],
                [self::FIELD_NAME, $endNormalizedData, $endComparisonOperator]
            );

        foreach ($filterOptions as $name => $value) {
            $this->filter->setOption($name, $value);
        }

        $this->filter->apply($query, $data);
        self::assertTrue($this->filter->isActive());
    }

    /**
     * @dataProvider notBetweenDataProvider
     */
    public function testApplyNotBetweenWithValidData($data, $comparisonType, $startNormalizedData, $endNormalizedData, $startComparisonOperator, $endComparisonOperator, $filterOptions): void
    {
        $data = array_merge($data, array('type' => $comparisonType));
        $query = $this->getQueryMock();

        $query->expects(self::once())
               ->method('getModelName')
               ->willReturn('not null');

        $query->expects(self::exactly(2))
            ->method('filterBy')
            ->withCOnsecutive(
                [self::FIELD_NAME, $startNormalizedData, $startComparisonOperator],
                [self::anything(), self::anything(), self::anything()],
                [self::FIELD_NAME, $endNormalizedData, $endComparisonOperator]
            )
            ->willReturnOnConsecutiveCalls(
                $this->returnSelf(),
                $this->returnSelf(),
                $this->returnSelf(),
            );

        $query->expects(self::once())
               ->method('_or')
               ->willReturnSelf();

        foreach ($filterOptions as $name => $value) {
            $this->filter->setOption($name, $value);
        }

        $this->filter->apply($query, $data);
        self::assertTrue($this->filter->isActive());
    }

    public function invalidDataProvider(): array
    {
        return array(
            // data, filterValid
            array(null, false),
            array('string', false),
            array(42, false),
            array(array('foo'   => 'dummy value'), false),
            array(array('value' => array('foo'   => 'dummy value')), true),
            array(array('value' => array('start' => 'dummy value')), true),
            array(array('value' => array('end'   => 'dummy value')), true),
            array(array('value' => array('start' => null, 'end' => 'dummy value')), true),
            array(array('value' => array('end'   => null, 'start' => 'dummy value')), true),
        );
    }

    public function betweenDataProvider(): array
    {
        $start = new \DateTime();
        $end = clone $start;
        $end->modify('+1 week');

        $data = array('value' => array('start' => $start, 'end' => $end));

        return array(
            // data, comparisonType, startNormalizedData, endNormalizedData, startComparisonOperator, endComparisonOperator, filterOptions
            array($data, null,                        $start,                 $end,                 ModelCriteria::GREATER_EQUAL, ModelCriteria::LESS_EQUAL,   array()),
            array($data, null,                        $start->getTimestamp(), $end->getTimestamp(), ModelCriteria::GREATER_EQUAL, ModelCriteria::LESS_EQUAL,   array('input_type' => 'timestamp')),
            array($data, DateRangeType::TYPE_BETWEEN, $start,                 $end,                 ModelCriteria::GREATER_EQUAL, ModelCriteria::LESS_EQUAL,   array()),
            array($data, DateRangeType::TYPE_BETWEEN, $start->getTimestamp(), $end->getTimestamp(), ModelCriteria::GREATER_EQUAL, ModelCriteria::LESS_EQUAL,   array('input_type' => 'timestamp')),
        );
    }

    public function notBetweenDataProvider(): array
    {
        $start = new \DateTime();
        $end = clone $start;
        $end->modify('+1 week');

        $data = array('value' => array('start' => $start, 'end' => $end));

        return array(
            // data, comparisonType, startNormalizedData, endNormalizedData, startComparisonOperator, endComparisonOperator, filterOptions
            array($data, DateRangeType::TYPE_NOT_BETWEEN, $start,                 $end,                 ModelCriteria::LESS_THAN,     ModelCriteria::GREATER_THAN, array()),
            array($data, DateRangeType::TYPE_NOT_BETWEEN, $start->getTimestamp(), $end->getTimestamp(), ModelCriteria::LESS_THAN,     ModelCriteria::GREATER_THAN, array('input_type' => 'timestamp')),
        );
    }

    protected function getQueryMock()
    {
        $query = $this->getMockBuilder(ProxyQuery::class)
            ->disableOriginalConstructor()
            ->setMethods(array('filterBy', '_or', 'getModelName'))
            ->getMock();

        return $query;
    }

    protected function getFilter($fieldName)
    {
        $modelManager = $this->getModelManagerMock();

        $filterClass = $this->getFilterClass();
        $filter = new $filterClass($modelManager);

        $modelManager
               ->method('translateFieldName')
               ->with(self::anything(), $fieldName)
               ->willReturn($fieldName);

        $filter->initialize('filter', array(
            'field_name' => $fieldName,
        ));

        return $filter;
    }

    protected function getModelManagerMock()
    {
        $manager = $this->getMockBuilder(ModelManager::class)
            ->setMethods(array('translateFieldName'))
            ->getMock();

        return $manager;
    }
}
