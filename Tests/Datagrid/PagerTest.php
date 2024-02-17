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

use PHPUnit\Framework\TestCase;
use Sonata\PropelAdminBundle\Datagrid\Pager;
use Sonata\PropelAdminBundle\Datagrid\ProxyQuery;

/**
 * Pager tests.
 *
 * @author Kévin Gomez <contact@kevingomez.fr>
 */
class PagerTest extends TestCase
{
    public function testGetResults(): void
    {
        $query = $this->getMockBuilder(ProxyQuery::class, array('execute'))
            ->disableOriginalConstructor()
            ->getMock();
        $query->expects(self::once())
            ->method('execute')
            ->willReturn(42);

        $pager = new Pager();
        $pager->setQuery($query);

        self::assertSame(42, $pager->getResults());
    }

    /**
     * @dataProvider invalidParametersProvider
     */
    public function testInitWithInvalidParameters($page, $maxPerPage, $nbResults): void
    {
        // configure the query
        $query = $this->getProxyMock();

        $query->expects(self::once())
            ->method('setFirstResult')
            ->withConsecutive([null]);

        $query->expects(self::once())
            ->method('setMaxResults')
            ->withConsecutive([null]);

        // configure the pager
        $pager = $this->getMockBuilder(Pager::class)->onlyMethods(array('computeNbResults'))->getMock();

        $pager->expects(self::once())
            ->method('computeNbResults')
            ->willReturn($nbResults);

        $pager->setQuery($query);
        $pager->setPage($page);
        $pager->setMaxPerPage($maxPerPage);

        // and test!
        $pager->init();
        self::assertSame(0, $pager->getLastPage());
    }

    /**
     * @dataProvider validParametersProvider
     */
    public function testInitWithValidParameters($page, $lastPage, $maxPerPage, $nbResults, $firstResult): void
    {
        // configure the query
        $query = $this->getProxyMock();

        $query->expects(self::exactly(2))
            ->method('setFirstResult')
            ->withConsecutive(
                [null],
                [$firstResult]
            );

        $query->expects(self::exactly(2))
            ->method('setMaxResults')
            ->withConsecutive(
                [null],
                [$maxPerPage]
            );

        // configure the pager
        $pager = $this->getMockBuilder(Pager::class)->onlyMethods(array('computeNbResults'))->getMock();

        $pager->expects(self::once())
            ->method('computeNbResults')
            ->willReturn($nbResults);

        $pager->setQuery($query);
        $pager->setPage($page);
        $pager->setMaxPerPage($maxPerPage);

        // and test!
        $pager->init();
        self::assertSame($lastPage, $pager->getLastPage());
    }

    public function invalidParametersProvider(): array
    {
        return array(
            // page, maxPerPage, nbResults
            array(0, 0, 42),
            array(2, 0, 42),
            array(2, 10, 0),
        );
    }

    public function validParametersProvider(): array
    {
        return array(
            //    page, lastPage, maxPerPage, nbResults, firstResult
            array(1,    1,        10,         5,         0),
            array(2,    2,        5,          10,        5),
            array(2,    3,        5,          12,        5),
            array(3,    6,        3,          16,        6),
        );
    }

    protected function getProxyMock()
    {
        // configure the query
        $query = $this->getMockBuilder(ProxyQuery::class)
            ->disableOriginalConstructor()
            ->getMock();

        return $query;
    }
}
