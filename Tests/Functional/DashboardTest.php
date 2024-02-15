<?php

namespace Sonata\PropelAdminBundle\Tests\Functional;

class DashboardTest extends WebTestCase
{
    public function testAccessible(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/admin/dashboard');

        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertCount(1, $crawler->filter('.box .box-title:contains("Blog")'), 'There is a "Blog" section');
        $this->assertCount(1, $crawler->filter('.box .sonata-ba-list-label:contains("Posts")'), 'There is a "Posts" sub-section');
    }
}
