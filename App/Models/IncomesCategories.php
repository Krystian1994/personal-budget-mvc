<?php

namespace App\Models;

use PDO;
use \App\Token;

class IncomesCategories extends \Core\Model{
    public static function getUserIncomeCategories(){
        $sql = 'SELECT name FROM incomes_category_assigned_to_users WHERE user_id = :user_id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_STR);
        $stmt->execute();
        $categoryOfIncomes = $stmt->fetchAll();

        return $categoryOfIncomes;
    }
}

