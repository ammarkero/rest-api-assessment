<?php

namespace Models;

class Image {
  private $table = 'images';
  private $db;

  public $id;
  public $image_path;
  public $imageable_type;
  public $imageable_id;

  public function __construct($db)
  {
    $this->db = $db;
  }

  public function findById()
  {
    $query = 'SELECT * FROM ' . $this->table . ' WHERE id = :id';
    $params = ['id' => $this->id];

    $stmt = $this->db->fetch($query, $params);
    return $stmt;
  }

  public function findByImageable()
  {
    $query = 'SELECT * FROM ' . $this->table . ' WHERE imageable_type = :imageable_type AND imageable_id = :imageable_id';
    $params = [
      'imageable_type' => $this->imageable_type,
      'imageable_id' => $this->imageable_id
    ];

    $stmt = $this->db->fetch($query, $params);
    return $stmt;
  }

  public function create()
  {
    $query = 'INSERT INTO ' . $this->table . ' (image_path, imageable_type, imageable_id) VALUES (:image_path, :imageable_type, :imageable_id)';
    $params = [
      'image_path' => $this->image_path,
      'imageable_type' => $this->imageable_type,
      'imageable_id' => $this->imageable_id
    ];

    $stmt = $this->db->insert($query, $params);
    return $stmt;
  }

}