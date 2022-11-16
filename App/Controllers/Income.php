<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\Incomes;
use \App\Auth;
use \App\Flash;

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
    protected function before()
    {
        parent::before();
        $this->user = Auth::getUser();
    }

    public function newAction(){
        View::renderTemplate('Income/income.html', [
            'user' => $this->user
        ]);
    }

    public function incomeAction()
    {
        $income = new Incomes($_POST);
 
        if ($income->addIncome()) {
            echo "Przychód dodano!";
            $this->redirect('/Income/new');
        } else {
            echo "Przychodu nie dodano";
            $this->redirect('/Income/new');
        }
    }
}
