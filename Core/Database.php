<?php

namespace Core;

use PDO;

class Database {
  private $connection;

  public function __construct($config, $username = 'root', $password = '')
  {
    $dsn = 'mysql:' . http_build_query($config, '', ';');

    try {
      $this->connection = new PDO($dsn, $username, $password, [
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      ]);
    } catch (PDOException $e) {
      exit('Failed to connect to the database: ' . $e->getMessage());
    }
  }

  public function query($query, $params = [])
  {
    $stmt = $this->connection->prepare($query);
    $stmt->execute($params);
    return $stmt;
  }

  public function fetch($query, $params = [])
  {
    $stmt = $this->query($query, $params);
    return $stmt->fetch();
  }

  public function fetchAll($query, $params = [])
  {
    $stmt = $this->query($query, $params);
    return $stmt->fetchAll();
  }

  public function insert($query, $params = [])
  {
    $stmt = $this->query($query, $params);
    return $this->connection->lastInsertId();
  }

  public function update($query, $params = [])
  {
    $stmt = $this->query($query, $params);
    return $stmt->rowCount();
  }

  public function delete($query, $params = [])
  {
    $stmt = $this->query($query, $params);
    return $stmt->rowCount();
  }
}