<?php
require_once('CRUD.php');

class Load extends CRUD
{
    protected string $table = 'contacts';
    public function update($idEdited, $nameEdit, $birthEdit, $emailEdit, $phoneEdit, $photoEdit)
    {
        $sql = "UPDATE $this->table SET name=?, birth=?, email=?, phone=?, photo=? WHERE id=?";
        $sql = DB::prepare($sql);
        echo $nameEdit, $birthEdit, $emailEdit, $phoneEdit, $photoEdit, $idEdited;
        $sql->execute(array($nameEdit, $birthEdit, $emailEdit, $phoneEdit, $photoEdit, $idEdited));
        return true;
    }
    public function insert()
    {
    }
}
