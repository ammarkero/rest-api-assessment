<?php

namespace Models;

class Role {
  private $table = 'posts';
  private $db;

  public $id;
  public $title;

  public function __construct($db)
  {
    $this->db = $db;
  }

  public function findById($id)
  {
    $query = 'SELECT * FROM ' . $this->table . ' WHERE id = :id';
    $params = ['id' => $id];

    $stmt = $this->db->fetch($query, $params);
    return $stmt;
  }

  public function findByTitle($title)
  {
    $query = 'SELECT * FROM ' . $this->table . ' WHERE title = :title';
    $params = ['title' => $title];

    $stmt = $this->db->fetch($query, $params);
    return $stmt;
  }

  public function findAll()
  {
    $query = 'SELECT * FROM ' . $this->table;

    $stmt = $this->db->fetchAll($query);
    return $stmt;
  }

  public function create()
  {
    $query = 'INSERT INTO ' . $this->table . ' (title) VALUES (:title)';

    $this->title = htmlspecialchars(strip_tags($this->title));

    $params = [
      'title' => $this->title
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
    $query = 'UPDATE ' . $this->table . ' SET title = :title WHERE id = :id';

    $this->id = $this->id;
    $this->title = htmlspecialchars(strip_tags($this->title));

    $params = [
      'id' => $this->id,
      'title' => $this->title
    ];

    $stmt = $this->db->update($query, $params);
    
    if ($stmt) {
      return true;
    } else {
      return false;
    }
  }
  
}