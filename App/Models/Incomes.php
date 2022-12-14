<?php

namespace App\Models;

use PDO;
use \App\Token;

class Incomes extends \Core\Model{
    public $errors = [];

    public function __construct($data = []){
        foreach ($data as $key => $value) {
            $this->$key = $value;
        };
    }
    public function addIncome(){
        $this->validateIncome();

        if (empty($this->errors)) {

            $idIncomeCategory = $this->getIdOfIncomesCategory();
            
            $sql = 'INSERT INTO incomes (user_id, income_category_assigned_to_user_id, amount, date_of_income, income_comment) VALUES (:user_id, :idIncomeCategory, :amount, :date, :comment)';

            $db = static::getDB();
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_STR);
            $stmt->bindValue(':idIncomeCategory', $idIncomeCategory, PDO::PARAM_STR);
            $stmt->bindValue(':amount', $this->amount, PDO::PARAM_STR);
            $stmt->bindValue(':date', $this->date, PDO::PARAM_STR);
            $stmt->bindValue(':comment', $this->comment, PDO::PARAM_STR);
            $stmt->execute();
            
            return true;
        }
        return false;
    }

    public function getIdOfIncomesCategory(){
        $sql = 'SELECT id FROM incomes_category_assigned_to_users WHERE user_id = :user_id AND name LIKE :income';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_STR);
        $stmt->bindValue(':income', $this->income, PDO::PARAM_STR);
        $stmt->execute();

        $incomesId = $stmt->fetch();

        return $incomesId['id'];
}

    public function validateIncome(){
        // Amount
        if ((!isset($this->amount)) || ($this->amount <= 0)) {
            $this->errors[]  = "Wprowadzona kwota przychodu jest nieprawidłowa.";
        }

        // Comment
        $this->comment = htmlentities($this->comment,ENT_QUOTES,"UTF-8");

        // Date
        $badDate = '';
        if($this->date == $badDate){
            $this->errors[] = "Nie podano daty przychodu.";
        }
        
        // Income
        if(!isset($this->income)){
            $this->errors[] = "Nie wybrano kategorii przychodu.";
        }
    }
}

