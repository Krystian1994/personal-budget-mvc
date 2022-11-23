<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\Incomes;
use \App\Auth;
use \App\Flash;
use \App\Models\IncomesCategories;

/**
 * Profile controller
 *
 * PHP version 7.0
 */
class Income extends Authenticated
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
        $categories = IncomesCategories::getUserIncomeCategories();
        View::renderTemplate('Income/income.html', [
            'user' => $this->user,
            'categories' => $categories
        ]);
    }

    public function incomeAction(){
        $income = new Incomes($_POST);
 
        if ($income->addIncome()) {
            Flash::addMessage('Przychód został dodany.',Flash::INFO);

            $this->redirect('/income/new');
        } else {
            Flash::addMessage('Błąd. Przychód nie został dodany.',Flash::WARNING);

            View::renderTemplate('Income/income.html', [
                'income' => $income,
                'user' => $this->user
            ]);
        }
    }
}
