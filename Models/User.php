<?php

namespace Models;

use Firebase\JWT\JWT;

class User {
  private $table = 'users';
  private $db;

  public $id;
  public $name;
  public $email;
  public $password;

  public function __construct($db)
  {
    $this->db = $db;
  }

  public function getAllUsers()
  {
    $query = 'SELECT * FROM ' . $this->table; 

    $stmt = $this->db->fetchAll($query);
    return $stmt;
  }

  public function findById($userId)
  {
    $query = 'SELECT * FROM ' . $this->table . ' WHERE id = :userId';
    $params = ['userId' => $userId];

    $stmt = $this->db->fetch($query, $params);
    return $stmt;
  }

  public function findByEmail($email)
  {
    $query = 'SELECT * FROM ' . $this->table . ' WHERE email = :email';
    $params = ['email' => $email];

    $stmt = $this->db->fetch($query, $params);
    return $stmt;
  }

  public function create()
  {
    $query = 'INSERT INTO ' . $this->table . ' (name, email, password) VALUES (:name, :email, :password)';

    $this->name = htmlspecialchars(strip_tags($this->name));
    $this->email = htmlspecialchars(strip_tags($this->email));
    $this->password = password_hash($this->password, PASSWORD_DEFAULT); 

    $params = [
      'name' => $this->name,
      'email' => $this->email,
      'password' => $this->password
    ];

    $stmt = $this->db->insert($query, $params);
    
    if ($stmt) {
      return $this->findById($stmt);
    }
  }

  public function update()
  {
    $query = 'UPDATE ' . $this->table . '
                        SET name = :name, email = :email, password = :password 
                        WHERE id = :id';

    $this->id = htmlspecialchars(strip_tags($this->id));
    $this->name = htmlspecialchars(strip_tags($this->name));
    $this->email = htmlspecialchars(strip_tags($this->email));
    $this->password = password_hash($this->password, PASSWORD_DEFAULT); 

    $params = [
      'id' => $this->id,
      'name' => $this->name,
      'email' => $this->email,
      'password' => $this->password
    ];

    $rowCount = $this->db->update($query, $params);

    if ($rowCount === 0) {
      sendResponse(404, 'User not found');
      return;
    } else {
      return $this->findById($this->id);
    }

  }

  public function delete()
  {
    $query = 'DELETE FROM user_roles WHERE user_id = :id';

    $params = ['id' => $this->id];
    
    $this->db->delete($query, $params);

    $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';

    $this->id = htmlspecialchars(strip_tags($this->id));

    $params = ['id' => $this->id];

    $rowCount = $this->db->delete($query, $params);

    if ($rowCount === 0) {
      sendResponse(404, 'User not found');
      return;
    } else {
      return true;
    }
  }

  public function image()
  {
    $query = 'SELECT image_path FROM images WHERE imageable_type = :imageable_type AND imageable_id = :imageable_id';
    $params = [
      'imageable_type' => 'User',
      'imageable_id' => $this->id
    ];

    $stmt = $this->db->fetch($query, $params);
    return $stmt;
  }

  public function getRoleName()
  {
    $query = 'SELECT r.title
              FROM users u
              JOIN user_roles ur ON u.id = ur.user_id
              JOIN roles r ON ur.role_id = r.id
              WHERE u.id = :id';
    $params = ['id' => $this->id];

    $stmt = $this->db->fetchAll($query, $params);
    return $stmt;
  }

  public function storeUserRole($roleId)
  {
    $query = 'INSERT INTO user_roles (user_id, role_id) VALUES (:user_id, :role_id)';
    $params = [
      'user_id' => $this->id,
      'role_id' => $roleId
    ];

    $stmt = $this->db->insert($query, $params);
    return $stmt;
  }


}