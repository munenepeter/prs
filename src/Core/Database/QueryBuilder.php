<?php

namespace PRS\Core\Database;

class QueryBuilder{
    protected $pdo;

    public function __construct($pdo) {
  
      $this->pdo = $pdo;
    }
  
    /**
     * selectAll
     * 
     * This selects everything from a given table
     * @param String $table table from which to selct the data
     * 
     * @return Model returns an instance of Model with the same table name
     */
    public function selectAll(String $table) {
  
      $statement = $this->pdo->prepare("select * from {$table}");
  
      if (!$statement->execute()) {
  
        throw new \Exception("Something is up with your Select {$statement}!");
      }
  
      $model = singularize(ucwords($table));
  
      return $statement->fetchAll(\PDO::FETCH_CLASS,  "PRS\\Models\\{$model}");
    }
    /**
     * Select
     * Selects given values 
     * 
     * @param String $table Table from which to select
     * @param Array $values The columns in the db to select from
     * 
     * @return Model returns an instance of Model with the same table name
     */
    public function select(string $table, array $values) {
  
      $values =  implode(',', $values);
      $statement = $this->pdo->prepare("select {$values}  from {$table}");
  
      if (!$statement->execute()) {
  
        throw new \Exception("Something is up with your Select {$statement}!");
      }
      $model = ucwords($table);
      return $statement->fetchAll(\PDO::FETCH_CLASS,  "PRS\\Models\\{$model}");
    }
  
    /**
     * SelectWhere
     * 
     * Selects given column names given a certain condition
     * 
     * @param String $table Table from which to select
     * @param Array $values The columns in the db to select from
     * @param Array $condition The condition to be fulfiled by the where clause
     * 
     * @example 
     *  selectWhere('table_name", ['email', 'pass'], ['email','test@test.com']);
     */
  
    public function selectWhere(string $table, array $values, array $condition) {
  
      $values =  implode(', ', $values);
      //pure madness
      $condition[1] = sprintf("%s$condition[1]%s", '"', '"');
  
      $condition =  implode(' = ', $condition);
      $statement = $this->pdo->prepare("select {$values}  from {$table} where {$condition}");
  
      $sql = "select {$values}  from {$table} where {$condition}"; 
      if (!$statement->execute()) {
        throw new \Exception("Something is up with your Select {$statement}!");
      }
      $model = singularize(ucwords($table));
      return $statement->fetchAll(\PDO::FETCH_CLASS,  "PRS\\Models\\{$model}");
    }
  
    public function update(string $table, $dataToUpdate, $where, $isValue) {
  
      $sql = "UPDATE {$table} SET $dataToUpdate WHERE $where = $isValue"; 
  
      try {
  
        $statement = $this->pdo->prepare($sql);
        $statement->execute();
      } catch (\Exception $e) {
  
        throw new \Exception('Something is up with your Update!' . $e->getMessage());
        die();
      }
    }
    //DELETE FROM table_name WHERE condition;
    public function delete(string $table, $where, $isValue) {
  
      $sql = "DELETE FROM {$table} WHERE $where = $isValue"; 
  
      try {
  
        $statement = $this->pdo->prepare($sql);
        $statement->execute();
      } catch (\Exception $e) {
  
        throw new \Exception('Something is up with your Delete!' . $e->getMessage());
        die();
      }
    }
  
    public function insert(string $table, array $parameters) {
  
      $sql = sprintf(
        'insert into %s (%s) values (%s)',
  
        $table,
  
        implode(', ', array_keys($parameters)),
  
        ':' . implode(', :', array_keys($parameters))
      );
     
      try {
  
        $statement = $this->pdo->prepare($sql);
        $statement->execute($parameters);
      } catch (\Exception $e) {
  
        throw new \Exception('Something is up with your Insert!' . $e->getMessage());
        die();
      }
    }
}