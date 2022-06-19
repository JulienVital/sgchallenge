<?php

namespace app\Game\Strategy;

class Move
{
    private $game;

    public function __construct($game){
        $this->game = $game;
    }

    public function whatApplicationsCanIbuildWithMyhandForReleasePhase(): array
    {
        $myhand = $this->game->getMyHand();
        $applications = $this->game->getApplications();
        $needed = [];
        foreach ($applications as $application) {
            if($application->canIbuildThisNextTurn($myhand)){
                $needed = array_merge($needed, $application->whatIneedForBuildThisApplication($myhand));
            }
        }
        $needed = $this->removeActualPosition($needed);
        $needed = $this->replaceValueByIndexOfLocation($needed);

        if (!empty($needed)){

            return $needed;
        }
        return $this->calcCardForAllApplication();
 
    }

    public function removeActualPosition( array $needed){
        if ( empty($needed) ){
            [];
        }
        
        $actualPosition = $this->game->getActualPosition()['card'];
        
        if (array_key_exists($actualPosition, $needed)){
            
            unset($needed[$actualPosition]);
        }
        return $needed;
    }

    public function replaceValueByIndexOfLocation( array $needed){

        if ( empty($needed) ){
            return [];
        }

        foreach ($needed as $key => $value) {
            $needed[$key] = $this->game->openSpaceCardCorrespondence[$key];
        }
        return $needed;
    }

    public function calcCardForAllApplication(): array
    {
        $applications = $this->game->getState()['applications'];
        $training = array_column($applications, 'training');
        $coding = array_column($applications, 'coding');
        $dailyRoutine = array_column($applications, 'dailyRoutine');
        $taskPrioritization = array_column($applications, 'taskPrioritization');
        $architectureStudy = array_column($applications, 'architectureStudy');
        $continuousDelivery = array_column($applications, 'continuousDelivery');
        $codeReview = array_column($applications, 'codeReview');
        $refactoring = array_column($applications, 'refactoring');
        $applicationsSorted = [
            'training'=>array_sum($training),
            'coding'=>array_sum($coding),
            'dailyRoutine'=>array_sum($dailyRoutine),
            'taskPrioritization'=>array_sum($taskPrioritization),
            'architectureStudy'=>array_sum($architectureStudy),
            'continuousDelivery'=>array_sum($continuousDelivery),
            'codeReview'=>array_sum($codeReview),
            'refactoring'=>array_sum($refactoring),
        ];
        arsort($applicationsSorted);
        $applicationsSorted = $this->removeActualPosition($applicationsSorted);
        return $this->replaceValueByIndexOfLocation($applicationsSorted);
    }
}

