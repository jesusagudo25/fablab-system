<?php 

class User extends Model implements IModel
{
    private $user_id;
    private $role_id;
    private $name;
    private $lastname;
    private $email;
    private $password;
    private $status;
    private $token;
    private $date_token;

    public function __construct()
    {
        parent::__construct();
    }

    public function get($id)
    {
        // TODO: Implement get() method.
    }

    public function validateEmail($email){

        $consulta = $this->prepare("SELECT * FROM users WHERE email = :email");
        $consulta->execute(['email'=>$email]);
        $usuario = $consulta->fetch();

        return $usuario;
    }

    public function save(...$args)
    {
        // TODO: Implement save() method.
    }

    public function getAll()
    {
        // TODO: Implement getAll() method.
    }

    public function delete($id)
    {
        // TODO: Implement delete() method.
    }

    public function update()
    {
        // TODO: Implement update() method.
    }

    public function validateSession($email,$password){

    }

}
