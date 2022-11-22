<?php

namespace App\Models;

use PDO;
use \App\Token;

/**
 * User model
 *
 * PHP version 7.0
 */
class ExpensesCategories extends \Core\Model{
    public static function getUserExpenseCategories(){
        $sql = 'SELECT name FROM expenses_category_assigned_to_users WHERE user_id = :user_id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_STR);
        $stmt->execute();
        $categoryOfExpenses = $stmt->fetchAll();

        return $categoryOfExpenses;
    }
}

