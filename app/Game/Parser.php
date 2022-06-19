<?php

namespace app\Game;

class Parser
{
    public function parse($state)
    {
        $state = explode('|', $state);
        $gamePhase = $state[0];
        $applicationsCount = $state[1];
        $applications = $this->parseApplications($state[2]);
        $players = $this->parsePlayers($state[3]);
        $cardLocationsCount = $state[4];
        $cardLocations = $this->parseCardLocations($state[5]);
        $possibleMovesCount = $state[6];
        $possibleMoves = explode(',', $state[7]);
        unset($possibleMoves[array_key_last($possibleMoves)]);


        return [
            'gamePhase' => $gamePhase,
            'applicationsCount' => $applicationsCount,
            'applications' => $applications,
            'players' => $players,
            'cardLocationsCount' => $cardLocationsCount,
            'cardLocations' => $cardLocations,
            'possibleMovesCount' => $possibleMovesCount,
            'possibleMoves' => $possibleMoves,
        ];
    }

    public function parseCardLocations(string $cardLocationsRaw)
    {
        $cardLocationsRaw = explode('/', $cardLocationsRaw);
        unset($cardLocationsRaw[array_key_last($cardLocationsRaw)]);
        $cards = [];

        foreach ($cardLocationsRaw as $key=> $card){
            $card = explode(',', $card);
            $cards[$card[0]] = [
                'cardsLocation' => $card[0],
                'training' => $card[1],
                'coding' => $card[2],
                'dailyRoutine' => $card[3],
                'taskPrioritization' => $card[4],
                'architectureStudy' => $card[5],
                'continuousDelivery' => $card[6],
                'codeReview' => $card[7],
                'refactoring' => $card[8],
                'bonus' => $card[9],
                'technicalDebt' =>$card[10]
            ];
                
        }
        return $cards;
    }

    public function parsePlayers($playersRaw)
    {
        $playersRaw = explode('/', $playersRaw);
        unset($playersRaw[array_key_last($playersRaw)]);
        $players = [];
        foreach ($playersRaw as $key=> $player){
            $player = explode(',', $player);
            $players[$key] = [
                    'location' => $player[0],
                    'score' => $player[1],
                    'permanentDailyRoutineCards' => $player[2],
                    'permanentArchitectureStudyCards' => $player[3],
                ];
            }
        return $players;
    }

    public function parseOneApplication(string $application) :array{

        $application = explode(',', $application);
        return [
            'objectType' => $application[0],
            'id' => intval($application[1]),
            'training' => $application[2],
            'coding' => $application[3],
            'dailyRoutine' => $application[4],
            'taskPrioritization' => $application[5],
            'architectureStudy' => $application[6],
            'continuousDelivery' => $application[7],
            'codeReview' => $application[8],
            'refactoring' => $application[9],
        ];
    }

    public function parseApplications(string $applicationsRaw)
    {
 
        $applicationsRawArray = explode('/', $applicationsRaw);
        unset($applicationsRawArray[array_key_last($applicationsRawArray)]);
        $applications = [];
        foreach ($applicationsRawArray as $applicationRaw) {
            $applications[] = $this->parseOneApplication($applicationRaw);
        }
        return $applications;

    }
}

