<?php

namespace Models;

class UserRole {
  private $table = 'user_roles';
  private $db;

  public $user_id;
  public $role_id;

  public function __construct($db)
  {
    $this->db = $db;
  }

  public function findByUserId()
  {
    $query = 'SELECT * FROM ' . $this->table . ' WHERE user_id = :user_id';
    $params = ['user_id' => $this->user_id];

    $stmt = $this->db->fetch($query, $params);
    return $stmt;
  }
}