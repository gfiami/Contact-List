<?php
require_once('CRUD.php');

class Contact extends CRUD
{
    protected string $table = 'contacts';
    public function update($idEdited, $nameEdit, $birthEdit, $emailEdit, $phoneEdit, $photoEdit)
    {
        //check if user if not changing mail
        if (!($this->emailEdit($idEdited, $emailEdit))) {
            //email = new email
            $sql = "SELECT *FROM $this->table WHERE email=?";
            $sql = DB::prepare($sql);
            $sql->execute(array($emailEdit));
            $value = $sql->fetchAll();
            if (count($value) > 0) {
                $this->error['updateError'] = "This email is already registered.";
                return false; //email already exists
            }
        }
        if (!($this->phoneEdit($idEdited, $phoneEdit))) {
            //phone = new phone
            $sql = "SELECT *FROM $this->table WHERE phone=?";
            $sql = DB::prepare($sql);
            $sql->execute(array($phoneEdit));
            $value = $sql->fetchAll();
            if (count($value) > 0) {
                $this->error['updateError'] = "This phone is already registered.";
                return false; //phone already exists
            }
        }

        $sql = "UPDATE $this->table SET name=?, birth=?, email=?, phone=?, photo=? WHERE id=?";
        $sql = DB::prepare($sql);
        $sql->execute(array($nameEdit, $birthEdit, $emailEdit, $phoneEdit, $photoEdit, $idEdited));
        return true;
    }
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
    function emailEdit($idEdited, $emailEdit)
    {
        //$data = $this->find($idEdited);
        $sql = "SELECT * FROM $this->table WHERE id = ?";
        $sql = DB::prepare($sql);
        $sql->execute(array($idEdited));
        $data = $sql->fetchAll(PDO::FETCH_ASSOC);
        foreach ($data as $value) {
            if ($value['email'] == $emailEdit) {
                return true; //keep email
            } else {
                return false; //change email
            }
        }
    }
    function phoneEdit($idEdited, $phoneEdit)
    {
        //$data = $this->find($idEdited);
        $sql = "SELECT * FROM $this->table WHERE id = ?";
        $sql = DB::prepare($sql);
        $sql->execute(array($idEdited));
        $data = $sql->fetchAll(PDO::FETCH_ASSOC);
        foreach ($data as $value) {
            if ($value['phone'] == $phoneEdit) {
                return true; //keep phone
            } else {
                return false; //change phone
            }
        }
    }
    function setContactInfo($name, $birth, $email, $phone, $photo)
    {
        $this->name = $name;
        $this->birth = $birth;
        $this->email = $email;
        $this->phone = $phone;
        $this->photo = $photo;
    }
    function __construct(
        public string $name = '',
        public string $birth = '',
        public string $email = '',
        public string $phone = '',
        public string $photo = '',
        public array $error = []
    ) {
    }
}
