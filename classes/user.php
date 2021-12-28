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
        $consulta = $this->prepare("SELECT * FROM users WHERE user_id = :user_id");
        $consulta->execute(['user_id'=>$id]);
        $usuario = $consulta->fetch();

        $this->name = $usuario['name'];
        $this->lastname = $usuario['lastname'];
        $this->email = $usuario['email'];
    }

    public function validateEmail(){

        $consulta = $this->prepare("SELECT * FROM users WHERE email = :email");
        $consulta->execute(['email'=>$this->email]);
        $usuario = $consulta->fetch();

        return $usuario;
    }

    public function save(...$args)
    {
        $nuevoUsuario = $this->prepare('INSERT INTO users(role_id, name, lastname, email,password) VALUES (:role_id, :name,:lastname ,:email, :password)');

        $nuevoUsuario->execute([
            'role_id' => $this->role_id,
            'name' => $this->name,
            'lastname' => $this->lastname,
            'email' => $this->email,
            'password' => password_hash($this->password, PASSWORD_BCRYPT)
        ]);

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

    /**
     * @param mixed $role_id
     */
    public function setRoleId($role_id): void
    {
        $this->role_id = $role_id;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @param mixed $lastname
     */
    public function setLastname($lastname): void
    {
        $this->lastname = $lastname;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password): void
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }




}
