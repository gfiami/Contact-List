<?php
require_once('CRUD.php');

class Contact extends CRUD
{
    protected string $table = 'contacts';
    public function insert()
    {
        if ($this->emailAtDatabase() || $this->phoneAtDatabase()) {
            $this->error['contactError'] = 'This contact is already registered';
            return false;
        }
        $sql = "INSERT INTO $this->table VALUES (null,?,?,?,?,?)";
        $sql = DB::prepare($sql);
        $sql->execute(array($this->name, $this->birth, $this->email, $this->phone, $this->photo));
        return true;
    }
    function emailAtDatabase()
    {
        $sql = 'SELECT * FROM contacts WHERE email=? LIMIT 1';
        $sql = DB::prepare($sql);
        $sql->execute(array($this->email));
        $data = $sql->fetch();
        if ($data) {
            return true; //email at database
        } else {
            return false; //email not found
        }
    }
    function phoneAtDatabase()
    {
        $sql = 'SELECT * FROM contacts WHERE phone=? LIMIT 1';
        $sql = DB::prepare($sql);
        $sql->execute(array($this->phone));
        $data = $sql->fetch();
        if ($data) {
            return true; //phone at database
        } else {
            return false; //phone not found
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
