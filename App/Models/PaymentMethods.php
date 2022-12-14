<?php

namespace App\Models;

use PDO;
use \App\Token;

class PaymentMethods extends \Core\Model{
    public static function getUserPaymentMethods(){
        $sql = 'SELECT name FROM payment_methods_assigned_to_users WHERE user_id = :user_id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_STR);
        $stmt->execute();
        $methodOfPayments = $stmt->fetchAll();

        return $methodOfPayments;
    }
}

