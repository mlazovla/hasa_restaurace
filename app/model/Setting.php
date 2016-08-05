<?php

namespace App\Model;

/**
 * Persistent object setting. (key => value)
 */
class Setting extends \Nette\Database\Table\Selection {
	private $db;
	private $table = "setting";

	public function __construct(\Nette\Database\Context $database) {
		parent::__construct($database, $database->getConventions(), $this->table);
		$this->db = $database;
	}

	/**
	 * Find in setting table
	 *
	 * @param $key
	 * @return null
	 */
	public function getByKey($key) {
		foreach ($this->createSelectionInstance()->where(['key' => $key]) as $row) {
			return $row->value;
		}
		return null;
	}
}
