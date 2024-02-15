<?php

namespace Sonata\TestBundle\Model;

use Sonata\TestBundle\Model\Base\BlogPostQuery as BaseBlogPostQuery;

class BlogPostQuery extends BaseBlogPostQuery
{
    public function filterByIsPublished()
    {
        return $this->filterByPublished(true);
    }
}
