<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\Expenses;
use \App\Auth;
use \App\Flash;

/**
 * Profile controller
 *
 * PHP version 7.0
 */
class Expense extends Authenticated
{
        /**
     * Before filter - called before each action method
     *
     * @return void
     */
    protected function before()
    {
        parent::before();
        $this->user = Auth::getUser();
    }

    public function newAction(){
        View::renderTemplate('Expense/expense.html', [
            'user' => $this->user
        ]);
    }

    public function expenseAction()
    {
        $expense = new Expenses($_POST);

        if ($expense->addExpense()) {
            echo "Wydatek dodano!";
            $this->redirect('/Expense/new');
        } else {
            echo "Wydatku nie dodano";
            $this->redirect('/Expense/new');
        }
    }
}
