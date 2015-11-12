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
}
