<?php

namespace Sonata\PropelAdminBundle\Tests\Functional;

class DashboardTest extends WebTestCase
{
    public function testAccessible(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/admin/dashboard');

        self::assertTrue($client->getResponse()->isSuccessful());
        self::assertCount(1, $crawler->filter('.box .box-title:contains("Blog")'), 'There is a "Blog" section');
        self::assertCount(1, $crawler->filter('.box .sonata-ba-list-label:contains("Posts")'), 'There is a "Posts" sub-section');
    }
}
