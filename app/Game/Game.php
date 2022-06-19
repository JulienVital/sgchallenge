<?php

namespace app\Game;

class Game
{
    private $state;
    private $applications;
    private $myHand;
    private $actualPosition;

    public $openSpaceCardCorrespondence = [
        'training'=> 0,
        'coding'=> 1,
        'dailyRoutine'=> 2,
        'taskPrioritization'=> 3,
        'architectureStudy'=> 4,
        'continuousDelivery'=> 5,
        'codeReview'=> 6,
        'refactoring'=> 7,
    ];

    public $CardCorrespondenceOpenSpace = [
        -1=> 'desk',
        0 =>'training',
        1 =>'coding',
        2 =>'dailyRoutine',
        3 =>'taskPrioritization',
        4 =>'architectureStudy',
        5 =>'continuousDelivery',
        6 =>'codeReview',
        7 =>'refactoring',
    ];

    public function calcCardForAllApplication(): array
    {
        $applications = $this->state['applications'];
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
        return $applicationsSorted;
    }

    public function calcCardForMe(){
        $cards = array_merge(array(), $this->state['cardLocations']);
        unset($cards['OPPONENT_CARDS']);

        $training = array_column($cards, 'training');
        $coding = array_column($cards, 'coding');
        $dailyRoutine = array_column($cards, 'dailyRoutine');
        $taskPrioritization = array_column($cards, 'taskPrioritization');
        $architectureStudy = array_column($cards, 'architectureStudy');
        $continuousDelivery = array_column($cards, 'continuousDelivery');
        $codeReview = array_column($cards, 'codeReview');
        $refactoring = array_column($cards, 'refactoring');
        $cardsSorted = [
            'training'=>array_sum($training),
            'coding'=>array_sum($coding),
            'dailyRoutine'=>array_sum($dailyRoutine),
            'taskPrioritization'=>array_sum($taskPrioritization),
            'architectureStudy'=>array_sum($architectureStudy),
            'continuousDelivery'=>array_sum($continuousDelivery),
            'codeReview'=>array_sum($codeReview),
            'refactoring'=>array_sum($refactoring),
        ];
        return $cardsSorted;
    }

    public function update(array $gameStateParsed)
    {
        $this->state = $gameStateParsed;
        $this->myHand = $this->state['cardLocations']['HAND'];
        $this->createApplications();
        $this->actualPosition = ['integer'=>$this->state['players'][0]['location'], 'card'=>$this->CardCorrespondenceOpenSpace[$this->state['players'][0]['location']]];
        return $this;
    }

    public function createApplications()
    {
        $applicationsArray = $this->state['applications'];

        $applications=[];
        foreach ($applicationsArray as $application) {
            $applications[] = new Application($application);
        }
        $this->applications= $applications;
        return $this;
    }

    /**
     * Get the value of applications
     */ 
    public function getApplications()
    {
        return $this->applications;
    }





 
    /**
     * Get the value of myHand
     */ 
    public function getMyHand()
    {
        return $this->myHand;
    }

    /**
     * Get the value of actualPosition
     */ 
    public function getActualPosition()
    {
        return $this->actualPosition;
    }

    /**
     * Get the value of state
     */ 
    public function getState()
    {
        return $this->state;
    }
}

