<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\Expenses;
use \App\Auth;
use \App\Flash;
use \App\Models\ExpensesCategories;
use \App\Models\PaymentMethods;

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
    protected function before(){
        parent::before();
        $this->user = Auth::getUser();
    }

    public function newAction(){
        $categories = ExpensesCategories::getUserExpenseCategories();
        $methods = PaymentMethods::getUserPaymentMethods();
        View::renderTemplate('Expense/expense.html', [
            'user' => $this->user,
            'categories' => $categories,
            'methods' => $methods
        ]);
    }

    public function expenseAction(){
        $expense = new Expenses($_POST);

        if ($expense->addExpense()) {
            Flash::addMessage('Wydatek został dodany.', Flash::INFO);
            
            $this->redirect('/Expense/new');
        } else {
            Flash::addMessage('Błąd. Wydatek nie został dodany.', Flash::WARNING);

            View::renderTemplate('Expense/expense.html', [
                'expense' => $expense,
                'user' => $this->user
            ]);
        }
    }




    // public function expensesAction(){
    //     echo json_encode(ExpensesCategories::getUserExpenseCategories(), JSON_UNESCAPED_UNICODE);
    // }

    public function limitAction(){
        Flash::addMessage('pushnelo do PHP controllera', Flash::INFO);
        
        $category = $this->route_params['category'];
    
        echo json_encode(ExpensesCategories::getUserExpenseCategories($category), JSON_UNESCAPED_UNICODE);
    }
}
