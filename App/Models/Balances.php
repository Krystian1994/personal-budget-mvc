<?php

namespace App\Models;

use PDO;
use \App\Token;

class Balances extends \Core\Model
{
    public $errors = [];

    public function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        };
    }

    public static function incomesBalance($startDate, $endDate){
        $sql = 'SELECT name, SUM(amount) AS sumIncome FROM incomes_category_assigned_to_users, incomes WHERE date_of_income >= :firstDate AND date_of_income <= :secondDate AND incomes.income_category_assigned_to_user_id = incomes_category_assigned_to_users.id AND incomes.user_id = :user_id GROUP BY name ORDER BY sumIncome DESC';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':firstDate', $startDate, PDO::PARAM_STR);
        $stmt->bindValue(':secondDate', $endDate, PDO::PARAM_STR);
        $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_STR);
        $stmt->execute();
        $incomesBudget = $stmt->fetchAll();

        return $incomesBudget;
    }

    public static function expensesBalance($startDate, $endDate){
        $sql = 'SELECT name, SUM(amount) AS sumExpense FROM expenses_category_assigned_to_users, expenses WHERE date_of_expense >= :firstDate AND date_of_expense <= :secondDate AND expenses.expense_category_assigned_to_user_id = expenses_category_assigned_to_users.id AND expenses.user_id = :user_id GROUP BY name ORDER BY sumExpense DESC';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':firstDate', $startDate, PDO::PARAM_STR);
        $stmt->bindValue(':secondDate', $endDate, PDO::PARAM_STR);
        $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_STR);
        $stmt->execute();
        $expensesBudget = $stmt->fetchAll();

        return $expensesBudget;
    }

    public static function sum($array){
        $sum = 0;
        $sum = number_format($sum,2);
        foreach($array as $row){
            $sum += $row[1];
        }
        return $sum;
    }

    public static function getStatement($firstArg, $secondArg){
        if($firstArg > $secondArg){
            return "Dobrze zarz??dzasz bud??etem. Tw??j bilans wynosi: ";
        } else if($firstArg < $secondArg){
            return "Wpadasz w d??ugi. Tw??j bilans wynosi: ";
        } else {
            return "Tw??j bilans wynosi: ";
        }
    }

    public static function sumOfBalance($firstArg, $secondArg){
        $difference =  ($firstArg - $secondArg);
        return $difference;
    }
}

