<?php

namespace App\Models;

use PDO;
use \App\Token;

/**
 * User model
 *
 * PHP version 7.0
 */
class User extends \Core\Model
{
    /**
     * Error messages
     *
     * @var array
     */
    public $errors = [];

    /**
     * Class constructor
     *
     * @param array $data  Initial property values (optional)
     *
     * @return void
     */
    public function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        };
    }
    public function addIncome()
    {
        $this->validateIncome();

        if (empty($this->errors)) {

            $idIncomeCategory = $this->getIdOfIncomesCategory();
            
            //piszemy dalszy ciag dodania przychodu ze sarego skryptu php.
            return true;
        }

        return false;
    }

    public function getIdOfIncomesCategory()
    {
        $sql = 'SELECT id FROM incomes_category_assigned_to_users WHERE user_id = :user_id AND name LIKE :income';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_STR);
        $stmt->bindValue(':income', $this->income, PDO::PARAM_STR);
        $stmt->execute();

        $incomesId = $stmt->fetch();

        return $incomesId['id'];
}

    public function validateIncome()
    {
        // Amount
        if ((!isset($this->amount)) || ($this->amount <= 0)) {
            $this->errors[]  = "Wprowadzona kwota przychodu jest nieprawidłowa.";
        }

        // Comment
        $this->comment = htmlentities($this->comment,ENT_QUOTES,"UTF-8");

        // Date
        if(!isset($this->date)){
            $this->errors[] = "Nie podano daty przychodu.";
        }
        
        // Income
        if(!isset($this->income)){
            $this->errors[] = "Nie wybrano kategorii przychodu.";
        }
    }

    public static function findByID($id)
    {
        $sql = 'SELECT * FROM users WHERE id = :id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();

        return $stmt->fetch();
    }

}

