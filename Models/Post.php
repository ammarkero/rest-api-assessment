<?php

namespace Models;

use Models\Image;

class Post {
  private $table = 'posts';
  private $db;

  public $id;
  public $title;
  public $content;

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

  public function image()
  {
    $query = 'SELECT image_path FROM images WHERE imageable_type = :imageable_type AND imageable_id = :imageable_id';
    $params = [
      'imageable_type' => 'Post',
      'imageable_id' => $this->id
    ];

    $stmt = $this->db->fetchAll($query, $params);
    return $stmt;
  }

}