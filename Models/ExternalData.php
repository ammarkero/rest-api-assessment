<?php

namespace Models;

class ExternalData {
  private $table = 'external_data';
  private $db;

  public $id;
  public $name;
  public $email;
  public $password;

  

  public function __construct($db)
  {
    $this->db = $db;
  }

  public function getAllData()
  {
    $query = 'SELECT * FROM ' . $this->table; 

    $stmt = $this->db->fetchAll($query);
    return $stmt;
  }

  public function getExternalDataByUserId($userId)
  {
    $query = 'SELECT * FROM ' . $this->table . ' WHERE user_id = :userId';
    $params = ['userId' => $userId];

    $stmt = $this->db->fetch($query, $params);
    return $stmt;
  }


}