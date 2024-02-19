<?php

/*
 * This file is part of the Sonata package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\PropelAdminBundle\Filter;

use Propel\Runtime\ActiveQuery\ModelCriteria;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Sonata\AdminBundle\Form\Type\Filter\DefaultType;
use Sonata\Form\Type\BooleanType;

/**
 * @author Kévin Gomez <contact@kevingomez.fr>
 */
class BooleanFilter extends AbstractFilter
{
    /**
     * Apply the filter to the ModelCriteria instance.
     *
     * @param ProxyQueryInterface $query
     * @param string              $alias
     * @param string              $field
     * @param string              $value
     */
    public function filter(ProxyQueryInterface $query, $alias, $field, $value)
    {
        $map = $this->getCriteriaMap();
        if (!array_key_exists('value', $value) || !array_key_exists($value['value'], $map)) {
            return;
        }

        /* @var $query ModelCriteria */
        $query->filterBy($field, true, $map[$value['value']]);
    }

    /**
     * {@inheritdoc}
     */
    public function getRenderSettings()
    {
        return array(DefaultType::class, array(
            'field_type'        => $this->getFieldType(),
            'field_options'     => $this->getFieldOptions(),
            'label'             => $this->getLabel(),
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getFieldType()
    {
        return $this->getOption('field_type', BooleanType::class);
    }

    protected function getCriteriaMap()
    {
        return array(
            BooleanType::TYPE_YES  => ModelCriteria::EQUAL,
            BooleanType::TYPE_NO   => ModelCriteria::NOT_EQUAL,
        );
    }
}
