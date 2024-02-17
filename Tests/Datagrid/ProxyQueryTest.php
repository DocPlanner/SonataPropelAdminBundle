<?php

/*
 * This file is part of the Sonata project.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\PropelAdminBundle\Tests\Datagrid;

use Propel\Runtime\ActiveQuery\Criteria;
use Sonata\PropelAdminBundle\Datagrid\ProxyQuery;
use Sonata\PropelAdminBundle\Tests\Functional\WebTestCase;

/**
 * ProxyQuery tests.
 *
 * @author Kévin Gomez <contact@kevingomez.fr>
 */
class ProxyQueryTest extends WebTestCase
{
    public static function setUpBeforeClass(): void
    {
        self::markTestIncomplete('This test has not been implemented yet.');
    }

    public function testWithVirtualColumns(): void
    {
//        $query = $this->getMockBuilder('\Sonata\TestBundle\Model\BlogPostQuery', array('filterByTitle'))
//            ->disableOriginalConstructor()
//            ->getMock();

//        $query->expects(self::once())
//            ->method('filterByIsPublished')
//            ->with(
//                $this->equalTo(true),
//                $this->equalTo(Criteria::EQUAL)
//            );
//
//        $proxy = new ProxyQuery($query);
//        // @note no field named "isPublished" in the model
//        $proxy->filterBy('isPublished', true);
    }

//    public function testFilterByCallsQueryClassesIfMethodExists()
//    {
//        $query = $this->getMockBuilder('\Sonata\TestBundle\Model\BlogPostQuery', array('filterByTitle'))
//            ->disableOriginalConstructor()
//            ->getMock();
//        $query->expects(self::once())
//            ->method('filterByTitle')
//            ->with(
//                $this->equalTo('dummy title'),
//                $this->equalTo(Criteria::EQUAL)
//            );
//
//        $proxy = new ProxyQuery($query);
//        $proxy->filterBy('Title', 'dummy title');
//    }
//
//    public function testFilterByCallsModelCriteriaIfMethodDoesntExist()
//    {
//        $query = $this->getMockBuilder('\Sonata\TestBundle\Model\BlogPostQuery', array('filterBy'))
//            ->disableOriginalConstructor()
//            ->getMock();
//        $query->expects(self::once())
//            ->method('filterBy')
//            ->with(
//                $this->equalTo('Slug'),
//                $this->equalTo('slug'),
//                $this->equalTo(Criteria::EQUAL)
//            );
//
//        $proxy = new ProxyQuery($query);
//        $proxy->filterBy('Slug', 'slug');
//    }
//
//    public function testOrderByIsntCalledIfNotSet()
//    {
//        $query = $this->getMockBuilder('\Sonata\TestBundle\Model\BlogPostQuery', array('find'))
//            ->disableOriginalConstructor()
//            ->getMock();
//        $query
//            ->expects(self::once())
//            ->method('find');
//        $query
//            ->expects($this->never())
//            ->method('orderBy');
//
//        $proxy = new ProxyQuery($query);
//        $proxy->execute();
//    }
//
//    public function testOrderByIsCalledIfSet()
//    {
//        $query = $this->getMockBuilder('\Sonata\TestBundle\Model\BlogPostQuery', array('find', 'orderBy'))
//            ->disableOriginalConstructor()
//            ->getMock();
//        $query
//            ->expects(self::once())
//            ->method('find');
//        $query
//            ->expects(self::once())
//            ->method('orderBy')
//            ->with('Slug', 'ASC');
//
//        $proxy = new ProxyQuery($query);
//        $proxy->setSortBy(/* ignored */ null, array('fieldName' => 'Slug'));
//        $proxy->setSortOrder('ASC');
//
//        $proxy->execute();
//    }
//
//    public function testGetUniqueParameterId()
//    {
//        $query = $this->getMockBuilder('\Sonata\TestBundle\Model\BlogPostQuery', array('find', 'orderBy'))
//            ->disableOriginalConstructor()
//            ->getMock();
//
//        $proxy = new ProxyQuery($query);
//
//        self::assertSame(0, $proxy->getUniqueParameterId());
//        self::assertSame(1, $proxy->getUniqueParameterId());
//        self::assertSame(2, $proxy->getUniqueParameterId());
//    }
}
