<?php

namespace App\Models;

use PDO;
use \App\Token;
use \App\DateValidator;

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

    public static function getSumExpenseCategoriesMonthly($category, $date){
        $startDate = DateValidator::getStartSelectedDate($date);
        $endDate = DateValidator::getEndSelectedDate($date);

        $sql = 'SELECT SUM(amount) AS sumExpense FROM expenses, expenses_category_assigned_to_users WHERE date_of_expense >= :firstDate AND date_of_expense <= :secondDate AND expenses.expense_category_assigned_to_user_id = expenses_category_assigned_to_users.id AND expenses.user_id = :user_id AND expenses_category_assigned_to_users.name = :name';


        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':firstDate', $startDate, PDO::PARAM_STR);
        $stmt->bindValue(':secondDate', $endDate, PDO::PARAM_STR);
        $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_STR);
        $stmt->bindValue(':name', $category, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC); 
 
        return $result[0]['sumExpense'];
    }

    public static function getLimitExpenseCategories($category){
        $sql = 'SELECT limit_expense FROM expenses_category_assigned_to_users WHERE user_id = :user_id AND name = :name';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_STR);
        $stmt->bindValue(':name', $category, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC); 
 
        return $result[0]['limit_expense'];
    }
}

