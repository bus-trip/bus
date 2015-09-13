<?php
/**
 * Part of bus 2015
 * Created by: Александр on 12.07.2015:12:23
 *
 * @var $dataProvider CDataProvider
 */

$this->widget('zii.widgets.grid.CGridView', [
	'id'           => 'user-auth-data-grid',
	'dataProvider' => $dataProvider,
	'template'     => '{items}{pager}',
	'columns'      => [
		[
			'name' => 'id',
		],
		[
			'name' => 'idTrip',
		],
		[
			'name' => 'place',
		],
		[
			'name' => 'price',
		],
		[
			'name' => 'price',
		],
		[
			'name' => 'address_from',
		],
		[
			'name' => 'address_to',
		],
		[
			'name' => 'remark',
		],
		[
			'name' => 'status',
		]
	]
]);
