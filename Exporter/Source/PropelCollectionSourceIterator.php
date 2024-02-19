<?php

declare(strict_types=1);

namespace Sonata\PropelAdminBundle\Exporter\Source;

use Propel\Runtime\Collection\Collection;
use Sonata\Exporter\Source\AbstractPropertySourceIterator;

class PropelCollectionSourceIterator extends AbstractPropertySourceIterator
{
    private Collection $collection;

    /**
     * @param array<string> $fields Fields to export
     */
    public function __construct(Collection $collection, array $fields, string $dateTimeFormat = 'r')
    {
        $this->collection = clone $collection;

        parent::__construct($fields, $dateTimeFormat);
    }

    public function rewind(): void
    {
        if (null === $this->iterator) {
            $this->iterator = $this->collection->getIterator();
        }

        $this->iterator->rewind();
    }
}

