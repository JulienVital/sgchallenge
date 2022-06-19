<?php

namespace app\Game;

class Application
{
    private $id ;
    private $cardNeeded;

    public function __construct(array $application){
        $this->id = $application['id'];
        unset($application['id']);
        $application = array_filter($application);
        unset($application['objectType']);



        $this->cardNeeded = $application;

    }

    public function canIbuildThisApplication(array $cards){
        $bonus= $cards['bonus'];
        $bonusNeeded=0;
       foreach ($this->cardNeeded as $key => $value) {

            if($value > $cards[$key]*2 ){
               $bonusNeeded+= $value - $cards[$key]*2;
           }
        }


        return $bonus >= $bonusNeeded;

    }

    public function whatIneedForBuildThisApplication(array $cards){
        $needed = [];
        foreach ($this->cardNeeded as $key => $value) {

            if($value > $cards[$key]*2 ){
               $needed[$key] = $value - $cards[$key]*2;
           }
       }
       return $needed;
    }

    public function canIbuildThisNextTurn(array $cards){
        if ($this->canIbuildThisApplication($cards)){
            return true;
        }
        $needed = $this->whatIneedForBuildThisApplication($cards);
        $valueOfCardGained=2;
        return array_sum($needed) <= ($cards['bonus']+$valueOfCardGained);
    }

    /**
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->id;
    }
}

