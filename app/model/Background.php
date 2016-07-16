<?php

namespace App\Model;

/**
 * Persistent object Background.
 */
class Background extends \Nette\Database\Table\Selection {
    private $db;
    private $table = "background";

	public function __construct(\Nette\Database\Context $database) {
		parent::__construct($database, $database->getConventions(), $this->table);
		$this->db = $database;
	}
    
}
