<?php

namespace App\Controllers;

use \Core\View;
use \App\DateValidator;
use \App\Models\Balances;
use \App\Auth;
use \App\Flash;

/**
 * Profile controller
 *
 * PHP version 7.0
 */
class Balance extends Authenticated
{
        /**
     * Before filter - called before each action method
     *
     * @return void
     */
    protected function before(){
        parent::before();
        $this->user = Auth::getUser();
    }

    public function newAction(){
        View::renderTemplate('Balance/balance.html', [
            'user' => $this->user
        ]);
    }

    public function balanceAction(){   
        if(isset($_POST['currentMonth'])){
           $this->balanceCurrentMonth();
        }

        if(isset($_POST['previousMonth'])){
            $this->balancePreviousMonth();
        }

        if(isset($_POST['currentYear'])){
            $this->balanceCurrentYear();
        }  

        if(isset($_POST['submit']) && isset($_POST['firstDate']) && isset($_POST['secondDate'])){
            $this->balanceSelectedPeriod();
        }
    }

    //balance from current month
    private function balanceCurrentMonth(){
        $startDate = DateValidator::getCurrentMonthStartDate();
        $endDate = DateValidator::getActualDate();

        return $this->rendering($startDate,$endDate);
    }

    // balance from previous month
    private function balancePreviousMonth(){
        $startDate = DateValidator::getPreviousMonthStart();
        $endDate = DateValidator::getPreviousMonthEnd();

        return $this->rendering($startDate,$endDate);
    }

    // balance from current year
    private function balanceCurrentYear(){
        $startDate = DateValidator::getCurrentYearStart();
        $endDate = DateValidator::getActualDate();

        return $this->rendering($startDate,$endDate);
    }
    
    //balance from selected period
    private function balanceSelectedPeriod(){
        if(DateValidator::validateDate($_POST['firstDate'],$_POST['secondDate'])){
            $startDate = $_POST['firstDate'];
            $endDate = $_POST['secondDate'];

            return $this->rendering($startDate,$endDate);
        } else {
            View::renderTemplate('Balance/balance.html', [
                'user' => $this->user,
                'datevalidator' => $this -> errors
            ]);
        }
    }

    private function rendering($startDate,$endDate){
        $incomesArray = Balances::incomesBalance($startDate,$endDate);
        $expensesArray = Balances::expensesBalance($startDate,$endDate);

        $incomesBalance = $this->sum($incomesArray);
        $expensesBalance = $this->sum($expensesArray);

        $difference = $this->sumOfBalance($incomesBalance,$expensesBalance);
        $statement = $this->getStatement($incomesBalance,$expensesBalance);

        View::renderTemplate('Balance/balance.html', [
            'user' => $this->user,
            'incomesBalance' => $incomesBalance,
            'expensesBalance' => $expensesBalance,
            'difference' => $difference,
            'statement' => $statement,
        ]);
    }


    private function sum($array){
        $sum = 0;
        foreach($array as $row){
            $sum += $row[1];
        }
        return $sum;
    }

    private function getStatement($firstArg, $secondArg){
        if($firstArg >= $secondArg){
            return "Dobrze zarządzasz budżetem. Twój bilans wynosi: ";
        } else {
            return "Wpadasz w długi. Twój bilans wynosi: ";
        }
    }

    private function sumOfBalance($firstArg, $secondArg){
        $difference =  ($firstArg - $secondArg);
        return $difference;
    }
}
