<?php
require_once('CRUD.php');

class Contact extends CRUD
{
    protected string $table = 'contacts';
    public function insert()
    {
        if (!$this->checkEmail()) {
            $this->error['emailError'] = 'This email is already registered';
            return false;
        }
        if (!$this->checkPhone()) {
            $this->error['phoneError'] = 'This phone number is already registered';
            return false;
        }
        $sql = "INSERT INTO $this->table VALUES (null,?,?,?,?,?)";
        $sql = DB::prepare($sql);
        $sql->execute(array($this->name, $this->birth, $this->email, $this->phone, $this->photo));
    }
    function checkEmail()
    {
        $sql = 'SELECT * FROM contacts WHERE email=? LIMIT 1';
        $sql = DB::prepare($sql);
        $sql->execute(array($this->email));
        $data = $sql->fetch();
        if (!$data) {
            return true;
        } else {
            return false;
        }
    }
    function checkPhone()
    {
        $sql = 'SELECT * FROM contacts WHERE phone=? LIMIT 1';
        $sql = DB::prepare($sql);
        $sql->execute(array($this->phone));
        $data = $sql->fetch();
        if (!$data) {
            return true;
        } else {
            return false;
        }
    }
    function __construct(
        public string $name,
        public string $birth,
        public string $email,
        public string $phone,
        public string $photo,
        public array $error = []
    ) {
    }
}
