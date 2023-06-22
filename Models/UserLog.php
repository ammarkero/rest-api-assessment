<?php

namespace Models;

class UserLog {
  private $table = 'user_logs';
  private $db;

  public $id;
  public $user_id;
  public $login_timestamp;
  public $logout_timestamp;
  public $token;

  public function __construct($db)
  {
    $this->db = $db;
  }

  public function create()
  {
    $query = 'INSERT INTO ' . $this->table . ' (user_id, login_timestamp, token) VALUES (:user_id, :login_timestamp, :token)';

    $this->user_id = htmlspecialchars(strip_tags($this->user_id));
    $this->login_timestamp = date('Y-m-d H:i:s');
    $this->token = $this->token;

    $params = [
      'user_id' => $this->user_id,
      'login_timestamp' => $this->login_timestamp,
      'token' => $this->token
    ];

    $stmt = $this->db->insert($query, $params);
    
    if ($stmt) {
      return true;
    } else {
      return false;
    }
  }

  public function update()
  {
    $query = 'UPDATE ' . $this->table . ' SET logout_timestamp = :logout_timestamp WHERE token = :token AND user_id = :user_id';

    $this->token = $this->token;
    $this->user_id = $this->user_id;
    $this->logout_timestamp = date('Y-m-d H:i:s');

    $params = [
      'logout_timestamp' => $this->logout_timestamp,
      'token' => $this->token,
      'user_id' => $this->user_id
    ];

    $stmt = $this->db->update($query, $params);
    
    if ($stmt) {
      return true;
    } else {
      return false;
    }
  }

}