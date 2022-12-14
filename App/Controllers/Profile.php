<?php

namespace App\Controllers;

use \Core\View;
use \App\Auth;
use \App\Flash;
use \App\Models\IncomesCategories;
use \App\Models\ExpensesCategories;
use \App\Models\PaymentMethods;

class Profile extends Authenticated{
    protected function before(){
        parent::before();
        $this->user = Auth::getUser();
    }

    public function menuAction(){
        View::renderTemplate('Profile/menu.html', [
            'user' => $this->user
        ]);
    }

    public function editAction(){
        $categoriesInc = IncomesCategories::getUserIncomeCategories();
        $categoriesExp = ExpensesCategories::getUserExpenseCategories();
        $methods = PaymentMethods::getUserPaymentMethods();
        View::renderTemplate('Profile/edit.html', [
            'user' => $this->user,
            'categoriesInc' => $categoriesInc,
            'categoriesExp' => $categoriesExp,
            'methods' => $methods
        ]);
    }

    public function updateAction(){
        if(isset($_POST['saveChanges'])){
            if ($this->user->updateProfile($_POST)){

                Flash::addMessage('Zapisano zmiany', Flash::INFO);
    
                $this->redirect('/profile/menu');
    
            }else{
    
                Flash::addMessage('Nie zapisano zmian',Flash::WARNING);
    
                View::renderTemplate('Profile/edit.html', [
                    'user' => $this->user
                ]);
            }     
        }

        if(isset($_POST['deleteIncome'])){
            if ($this->user->deleteIncomeCategory($_POST)){

                Flash::addMessage('Zapisano zmiany', Flash::INFO);
    
                $this->redirect('/profile/menu');
            }else{
                Flash::addMessage('Nie zapisano zmian',Flash::WARNING);
    
                View::renderTemplate('Profile/edit.html', [
                    'user' => $this->user
                ]);
            }
        }

        if(isset($_POST['addIncome'])){
            if ($this->user->addIncomeCategory($_POST)){

                Flash::addMessage('Zapisano zmiany', Flash::INFO);
    
                $this->redirect('/profile/menu');
            }else{
                Flash::addMessage('Nie zapisano zmian',Flash::WARNING);
    
                View::renderTemplate('Profile/edit.html', [
                    'user' => $this->user
                ]);
            }
        }

        if(isset($_POST['deleteExpense'])){
            if ($this->user->deleteExpenseCategory($_POST)){

                Flash::addMessage('Zapisano zmiany', Flash::INFO);
    
                $this->redirect('/profile/menu');
            }else{
                Flash::addMessage('Nie zapisano zmian',Flash::WARNING);
    
                View::renderTemplate('Profile/edit.html', [
                    'user' => $this->user
                ]);
            }
        }

        if(isset($_POST['addExpense'])){
            if ($this->user->addExpenseCategory($_POST)){

                Flash::addMessage('Zapisano zmiany', Flash::INFO);
    
                $this->redirect('/profile/menu');
            }else{
                Flash::addMessage('Nie zapisano zmian',Flash::WARNING);
    
                View::renderTemplate('Profile/edit.html', [
                    'user' => $this->user
                ]);
            }

        }

        if(isset($_POST['deletePay'])){
            if ($this->user->deletePayCategory($_POST)){

                Flash::addMessage('Zapisano zmiany', Flash::INFO);
    
                $this->redirect('/profile/menu');
            }else{
                Flash::addMessage('Nie zapisano zmian',Flash::WARNING);
    
                View::renderTemplate('Profile/edit.html', [
                    'user' => $this->user
                ]);
            }

        }

        if(isset($_POST['addPay'])){
            if ($this->user->addPayCategory($_POST)){

                Flash::addMessage('Zapisano zmiany', Flash::INFO);
    
                $this->redirect('/profile/menu');
            }else{
                Flash::addMessage('Nie zapisano zmian',Flash::WARNING);
    
                View::renderTemplate('Profile/edit.html', [
                    'user' => $this->user
                ]);
            }
        }

        if(isset($_POST['addLimit'])){
            if ($this->user->addLimit($_POST)){

                Flash::addMessage('Zapisano zmiany', Flash::INFO);
    
                $this->redirect('/profile/menu');
            }else{
                Flash::addMessage('Nie zapisano zmian',Flash::WARNING);
    
                View::renderTemplate('Profile/edit.html', [
                    'user' => $this->user
                ]);
            }
        }
    }
}
