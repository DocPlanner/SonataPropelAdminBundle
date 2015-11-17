<?php

/*
 * This file is part of the Sonata project.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\PropelAdminBundle\Datagrid;

use Sonata\AdminBundle\Datagrid\Datagrid as BaseDatagrid;
use Sonata\AdminBundle\Admin\FieldDescriptionInterface;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Exception\UnexpectedTypeException;

/**
 * @author Toni Uebernickel <tuebernickel@gmail.com>
 */
class Datagrid extends BaseDatagrid
{
	/**
	 * @param ProxyQuery $query
	 */
	public function setQuery(ProxyQuery $query)
	{
		$this->query = $query;
	}

	public function buildPager()
	{
		if ($this->bound) {
			return;
		}

		foreach ($this->getFilters() as $name => $filter) {
			list($type, $options) = $filter->getRenderSettings();

			$this->formBuilder->add($filter->getFormName(), $type, $options);
		}

		$this->formBuilder->add('_sort_by', 'hidden');
		$this->formBuilder->get('_sort_by')->addViewTransformer(new CallbackTransformer(
			function ($value) { return $value; },
			function ($value) { return $value instanceof FieldDescriptionInterface ? $value->getName() : $value; }
		));

		$this->formBuilder->add('_sort_order', 'hidden');
		$this->formBuilder->add('_page', 'hidden');
		$this->formBuilder->add('_per_page', 'hidden');

		$this->form = $this->formBuilder->getForm();
		$this->form->submit($this->values);

		$data = $this->form->getData();

		foreach ($this->getFilters() as $name => $filter) {
			$this->values[$name] = isset($this->values[$name]) ? $this->values[$name] : null;
			$filter->apply($this->query, $data[$filter->getFormName()]);
		}

		if (isset($this->values['_sort_by'])) {
			if (!$this->values['_sort_by'] instanceof FieldDescriptionInterface) {
				throw new UnexpectedTypeException($this->values['_sort_by'], 'FieldDescriptionInterface');
			}

			if ($this->values['_sort_by']->isSortable()) {
				$this->query->setSortBy($this->values['_sort_by']->getSortParentAssociationMapping(), $this->values['_sort_by']);
				$this->query->setSortOrder(isset($this->values['_sort_order']) ? $this->values['_sort_order'] : null);
			}
		}

		$maxPerPage = 25;
		if (isset($this->values['_per_page'])) {
			// check for `is_array` can be safely removed if php 5.3 support will be dropped
			if (is_array($this->values['_per_page'])) {
				if (isset($this->values['_per_page']['value'])) {
					$maxPerPage = $this->values['_per_page']['value'];
				}
			} else {
				$maxPerPage = $this->values['_per_page'];
			}
		}
		$this->pager->setMaxPerPage($maxPerPage);

		$page = 1;
		if (isset($this->values['_page'])) {
			// check for `is_array` can be safely removed if php 5.3 support will be dropped
			if (is_array($this->values['_page'])) {
				if (isset($this->values['_page']['value'])) {
					$page = $this->values['_page']['value'];
				}
			} else {
				$page = $this->values['_page'];
			}
		}

		$this->pager->setPage($page);

		$this->pager->setQuery($this->query);
		$this->pager->init();

		$this->bound = true;
	}
}
