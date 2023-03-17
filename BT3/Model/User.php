<?php
require_once dirname(__DIR__).'/config/config.php';
class User {
  private $id;
  private $name;
  private $email;
  private $password;
  private static $db ;
  public function __construct($name, $email, $password) {
    $this->name = $name;
    $this->email = $email;
    $this->password = $password;
    $this->db = new config();
  }

  public function getId() {
    return $this->id;
  }

  public function getName() {
    return $this->name;
  }

  public function getEmail() {
    return $this->email;
  }

  public function getPassword() {
    return $this->password;
  }

  public function save() {
    // Insert or update the user in the database
    $stmt = $this->db->getDB()->prepare('SELECT email FROM users WHERE 1');
    $stmt->execute();
    $emails = $stmt->fetchAll();
      foreach ($emails as $value) {

            if($value == $_POST['email']) {

              $stmt = $this->db->getDB()->prepare('UPDATE users SET name =?,email =?,password =?');
              $stmt->execute([$_POST['name'],$_POST['email'],$_POST['password']]);
              return $stmt->rowcount();

            }
            else{

              $stmt = $this->db->getDB()->prepare('INSERT INTO users (name,email,password) VALUES(?,?,?)');
              $stmt->execute([$_POST['name'],$_POST['email'],$_POST['password']]);
              return $stmt->rowcount();

            }
      }
  }
  public static function getById($id) {
    // Retrieve the user with the given ID from the database
    $stmt = self::$db->getDB()->prepare('SELECT * FROM users WHERE id =?');
    $stmt->execute([$id]);
    return $stmt->fetchAll();
  }

  public static function getAll() {
    // Retrieve all users from the database
    $stmt = self::$db->getDB()->prepare('SELECT * FROM users WHERE 1');
    $stmt->execute();
    return $stmt->fetchAll();
  }

  public function delete() {
    // Delete the user from the database
    $stmt = $this->db->getDB()->prepare('DELETE users WHERE id=? ');
    $stmt->execute([$_POST['id']]);
    return $stmt->rowcount();
  }
}