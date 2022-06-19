<?php

namespace app\Game\Strategy;

use app\Game\Game;

class Release
{
    private $game;

    public function __construct(Game $game){
        $this->game = $game;
    }

    public function canIbuildAnApplicationsWithMyhand()
    {
        $myhand = $this->game->getMyHand();
        $applications = $this->game->getApplications();
        $canbuild= [];
        foreach ($applications as $application) {
            if($application->canIbuildThisApplication($myhand)){
                $canbuild[]= $application->getId();
            }
        }

        return !empty($canbuild)?$canbuild:false;
 
    }
}

