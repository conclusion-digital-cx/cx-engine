<?php

require_once "Medoo.php";

// Use as closure
return function ($entity = '') {
	$db = require_once(__DIR__ . "/../db.php");
	return new Service($entity, $db);
};

/** Json server based service */
class Service
{
	function __construct($entity = "", $db)
	{
		if(!$db) { throw new Error("db is required"); }

		$this->entity = $entity;
		$this->db = $db;
	}

	// TODO
	function getOne($params = [])
	{
		$resp = $this->get($params);
		return $resp[0];
	}

	function getAll($params = [])
	{
		return $this->get($params);
	}

	function get($params)
	{
		$entity = $this->entity;
		$fields = isset($params['fields']) ? $params['fields'] : '*';
		$where = $params;

		// Move to https://github.com/typicode/json-server
		// $functionFields = [
		//     'page', 'itemsPerPage', 'sortBy', 'sortDesc',
		//     'groupBy', 'groupDesc', 'mustSort', 'multiSort'
		// ];
		$functionFields = [
			'_start', '_limit'
		];

		// Helper function
		$array_except = function($array, $keys)
		{
			foreach ($keys as $key) {
				unset($array[$key]);
			}
			return $array;
		};

		// Where = GET - functionFields
		$where = $array_except($where, $functionFields);

		// Process where
		foreach ($where as &$value) {
			// Convert NULL
			$value = $value === 'NULL' ? null : $value;
		}

		// Process special modifiers
		$specials = array_merge([
			'_start' => 0,
			'_limit' => 1000
		], $_GET);
		$where['LIMIT'] = [$specials['_start'], $specials['_limit']];

		// TODO
		// Set skips, limits, sorts
		// page=1&itemsPerPage=10&sortBy=&sortDesc=&groupBy=&groupDesc=&mustSort=false&multiSort=false
		// $where['LIMIT'] = [$_GET['page'] * $_GET['itemsPerPage'], $_GET['itemsPerPage']];

		$db = $this->db;
		// print_r($db);

		try {
			$rows = $db->select($entity, $fields, $where);
			if (!is_array($rows)) {
				// return true;    // Table not exist...exit this route handler
				throw new Exception("No rows");
			}
			getCx()->debug($rows);
			return $rows;

		} catch (Throwable $t) {
            var_dump( $db->error() );
        }
	}
}
