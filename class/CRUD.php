<?php
require_once('DB.php');

abstract class CRUD extends DB
{
    protected string $table;
    abstract public function insert();
    abstract public function update($idEdited, $nameEdit, $birthEdit, $emailEdit, $phoneEdit, $photoEdit);


    public function find($id)
    {
        $sql = "SELECT *FROM $this->table WHERE id=?";
        $sql = DB::prepare($sql);
        $sql->execute(array($id));
        $value = $sql->fetch();
        return $value;
    }
    public function findAll()
    {
        $sql = "SELECT *FROM $this->table";
        $sql = DB::prepare($sql);
        $sql->execute(array());
        $value = $sql->fetchAll(PDO::FETCH_ASSOC);
        return $value;
    }

    public function delete($id, $name, $email, $phone)
    {
        $sql = "DELETE FROM $this->table WHERE id=? AND name=? AND email=? AND phone=?";
        $sql = DB::prepare($sql);
        $sql->execute(array($id, $name, $email, $phone));
        return true;
    }

    public function orderBy($categoryOrder, $directionOrder)
    {
        $sql = "SELECT * FROM $this->table ORDER BY $categoryOrder $directionOrder";
        $sql = DB::prepare($sql);
        $sql->execute(array());
        $valueOrder = $sql->fetchAll(PDO::FETCH_ASSOC);
        return $valueOrder;}

    public function search($search, $column, $category, $direction){
        $sql = "SELECT * FROM $this->table WHERE $column LIKE '%{$search}%' ORDER BY $category $direction";
        $sql = DB::prepare($sql);
        $sql->execute(array());
        $valueSearch = $sql->fetchAll(PDO::FETCH_ASSOC);
        return $valueSearch;

    }
}
