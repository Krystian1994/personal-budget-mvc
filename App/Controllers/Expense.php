<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\Expenses;
use \App\Auth;
use \App\Flash;
use \App\Models\ExpensesCategories;
use \App\Models\PaymentMethods;

class Expense extends Authenticated{
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
            
            $categories = ExpensesCategories::getUserExpenseCategories();
            $methods = PaymentMethods::getUserPaymentMethods();
            View::renderTemplate('Expense/expense.html', [
                'expense' => $expense,
                'user' => $this->user,
                'categories' => $categories,
                'methods' => $methods
            ]);
        }
    }

    public function expenseSumMonthlyAction(){
        $category = $this->route_params['category'];
        $date = $this->route_params['date'];

        echo json_encode(ExpensesCategories::getSumExpenseCategoriesMonthly($category,$date), JSON_UNESCAPED_UNICODE);
    }

    public function limitAction(){ 
        $category = $this->route_params['category'];
    
        echo json_encode(ExpensesCategories::getLimitExpenseCategories($category), JSON_UNESCAPED_UNICODE);
    }
}
