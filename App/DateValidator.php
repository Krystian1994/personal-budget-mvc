<?php

namespace App;

use \Core\View;
use \App\Auth;
use \App\Flash;

/**
 * Profile controller
 *
 * PHP version 7.0
 */
class DateValidator
{
    public static function getActualDate(){
        return date('Y-m-d');
    }

    public static function getCurrentMonthStartDate(){
        return date('Y-m-01');
    }

    public static function getPreviousMonthEnd(){
        $currentMonth = date('m');
        $previousMonth = $currentMonth-1;

        $year = date('Y');
        if ( ( ($year % 4 == 0) && ($year % 100 <> 0) ) || ($year % 400 == 0) ){
            $monthsDays = array(
                '1' => '31',
                '2' => '29',
                '3' => '31',
                '4' => '30',
                '5' => '31',
                '6' => '30',
                '7' => '31',
                '8' => '31',
                '9' => '30',
                '10' => '31',
                '11' => '30',
                '12' => '31'
            );
        } else {
            $monthsDays = array(
                '1' => '31',
                '2' => '28',
                '3' => '31',
                '4' => '30',
                '5' => '31',
                '6' => '30',
                '7' => '31',
                '8' => '31',
                '9' => '30',
                '10' => '31',
                '11' => '30',
                '12' => '31'
            );
        }

        $previousMonthDays = $monthsDays[$previousMonth];

        if($previousMonth < 10){
            $previousMonth = '0'.$previousMonth;
        }

        $previousMonthEnd = $year.'-'.$previousMonth.'-'.$previousMonthDays;

        return $previousMonthEnd;
    }

    public static function getPreviousMonthStart(){
        $currentMonth = date('m');
        $previousMonth = $currentMonth-1;

        $year = date('Y');

        if($previousMonth < 10){
            $previousMonth = '0'.$previousMonth;
        }

        $previousMonthStart = $year.'-'.$previousMonth.'-01';

        return $previousMonthStart;
    }

    public static function getCurrentYearStart(){
        return date('Y-01-01');
    }

    public static function validateDate($firstDate,$secondDate){
        if($secondDate < $firstDate){
            return false;
        }
        
        return true;
    }

    public static function getStartSelectedDate($selectedDate){
        $timestamp = strtotime($selectedDate); 
        $month=date('m',$timestamp);
        $year=date('Y',$timestamp);
       
        $date = $year.'-'.$month.'-01';
        return $date;
    }

    public static function getEndSelectedDate($selectedDate){
        $timestamp = strtotime($selectedDate); 
        $month=date('m',$timestamp);
        $year=date('Y',$timestamp);

        $date = "{$year}-{$month}-31";
        return $date;
    }
}
