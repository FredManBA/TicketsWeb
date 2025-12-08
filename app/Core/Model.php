<?php

namespace App\Core;

require_once __DIR__ . '/../../Config/database.php';

class Model
{
    protected $db;

    public function __construct()
    {
        $this->db = \Database::getConnection();
    }
}
