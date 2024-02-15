<?php

namespace Sonata\PropelAdminBundle\Tests\Functional;

use Propel\Runtime\Propel;
use Sonata\TestBundle\Model\Base\BlogPostQuery;
use Sonata\TestBundle\Model\BlogPost;

class ExportTest extends WebTestCase
{
    protected $expected_formats = array('JSON', 'XML', 'CSV', 'XLS');

    public function testExportLinksAreShownOnDashboard(): void
    {
        $client = static::createClient();

        $con = Propel::getConnection();
        $con->beginTransaction();
        try
        {
            BlogPostQuery::create()->deleteAll();
            (new BlogPost)
                ->setTitle('Post 1')
                ->save();

            self::assertSame(1, BlogPostQuery::create()->count());

            $crawler = $client->request('GET', '/admin/sonata/test/blogpost/list');
            $link_selector = $this->getExportLinksSelector();

            $this->assertTrue($client->getResponse()->isSuccessful());
            $this->assertSame(count($this->expected_formats), $crawler->filter($link_selector)->count(), 'There are 4 possible export formats');
            foreach ($this->expected_formats as $format) {
                $this->assertCount(1, $crawler->filter(sprintf('%s:contains("%s")', $link_selector, $format), sprintf('The format %s is proposed', $format)));
            }
        }
        finally
        {
            $con->rollBack();
        }

    }

    public function testExportLinksWork(): void
    {
        $client = static::createClient();

        $con = Propel::getConnection();
        $con->beginTransaction();
        try
        {
            BlogPostQuery::create()->deleteAll();
            (new BlogPost)
                ->setTitle('Post 1')
                ->save();
            $crawler = $client->request('GET', '/admin/sonata/test/blogpost/list');

            $this->assertTrue($client->getResponse()->isSuccessful());
            foreach ($this->expected_formats as $format) {
                $link = $crawler->selectLink($format)->link();

                $this->markTestIncomplete('sonata-project/exporter is having issues with autoloading PropelCollectionSourceIterator class');

                // as Sonata\AdminBundle\Export\Exporter writes directly to php://output
                // the exported data is displayed in the console
                ob_start();
                $client->click($link);
                ob_end_clean();

                $this->assertTrue($client->getResponse()->isSuccessful(), sprintf('BlogPosts can be exported to %s', $format));
            }
        }
        finally
        {
            $con->rollBack();
        }
    }

    protected function getExportLinksSelector(): string
    {
        return '.box-footer .form-inline .pull-right a';
    }
}
