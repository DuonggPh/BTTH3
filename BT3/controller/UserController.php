<?php
require_once(dirname(__DIR__,2).'/vendor/autoload.php');
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
class UserController {
  private $twig;
  private $loader;
  public function __construct(){
    $this->loader = new FilesystemLoader(dirname(__DIR__).'/views');
    $this->twig = new Environment($this->loader);
  }
  public function index() {
    $users = User::getAll();
    $this->twig->render('index.twig',['user' => $users]);
  }

  public function show($id) {
    $user = User::getById($id);
    // Render a Twig template with the user's details
    $this->twig->render('show.twig',['user' =>$user ]);
  }

  public function create() {
    // Render a Twig template with a form to create a new user
    $this->twig->render('create.twig');
  }

  public function store() {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $user = new User($name, $email, $password);
    $user->save();
    // Redirect to the user's details page
    $this->twig->render('base.twig',['user' =>$user ]);
  }

  public function edit($id) {
    $user = User::getById($id);
    // Render a Twig template with a form to edit the user's details
    $this->twig->render('edit.twig',['user' =>$user ]);
  }

  public function update($id) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $user = User::getById($id);
    $user->setName($name);
    $user->setEmail($email);
    $user->setPassword($password);
    $user->save();
    // Redirect to the user's details page
    // ...
  }

  public function delete($id) {
    $user = User::getById($id);
    $user->delete();
    // Redirect to the list of users
    $this->twig->render('show.twig',['user' =>$user ]);
  }
}