<?php
namespace app;

use app\Game\Application;
use app\Game\Game;
use app\Game\Parser;
use app\Game\Strategy\Move;
use app\Game\Strategy\Release;

/**
 * Complete the hackathon before your opponent by following the principles of Green IT
 **/

// game loop
while (TRUE)
{
    $state='';
    // $gamePhase: can be MOVE, GIVE_CARD, THROW_CARD, PLAY_CARD or RELEASE
    fscanf(STDIN, "%s", $gamePhase);
    $state.=$gamePhase.'|';

    fscanf(STDIN, "%d", $applicationsCount);
    $state.=$applicationsCount.'|';


    for ($i = 0; $i < $applicationsCount; $i++)
    {
        // $trainingNeeded: number of TRAINING skills needed to release this application
        // $codingNeeded: number of CODING skills needed to release this application
        // $dailyRoutineNeeded: number of DAILY_ROUTINE skills needed to release this application
        // $taskPrioritizationNeeded: number of TASK_PRIORITIZATION skills needed to release this application
        // $architectureStudyNeeded: number of ARCHITECTURE_STUDY skills needed to release this application
        // $continuousDeliveryNeeded: number of CONTINUOUS_DELIVERY skills needed to release this application
        // $codeReviewNeeded: number of CODE_REVIEW skills needed to release this application
        // $refactoringNeeded: number of REFACTORING skills needed to release this application
        fscanf(STDIN, "%s %d %d %d %d %d %d %d %d %d", $objectType, $id, $trainingNeeded, $codingNeeded, $dailyRoutineNeeded, $taskPrioritizationNeeded, $architectureStudyNeeded, $continuousDeliveryNeeded, $codeReviewNeeded, $refactoringNeeded);
        
        $state.=
            $objectType.','
            .$id.','
            .$trainingNeeded.','
            .$codingNeeded.','
            .$dailyRoutineNeeded.','
            .$taskPrioritizationNeeded.','
            .$architectureStudyNeeded.','
            .$continuousDeliveryNeeded.','
            .$codeReviewNeeded.','
            .$refactoringNeeded.'/';

    }
    $state.='|';
    for ($i = 0; $i < 2; $i++)
    {
        // $playerLocation: id of the zone in which the player is located
        // $playerPermanentDailyRoutineCards: number of DAILY_ROUTINE the player has played. It allows them to take cards from the adjacent zones
        // $playerPermanentArchitectureStudyCards: number of ARCHITECTURE_STUDY the player has played. It allows them to draw more cards
        fscanf(STDIN, "%d %d %d %d", $playerLocation, $playerScore, $playerPermanentDailyRoutineCards, $playerPermanentArchitectureStudyCards);
        $state.=
            $playerLocation.','
            .$playerScore.','
            .$playerPermanentDailyRoutineCards.','
            .$playerPermanentArchitectureStudyCards.'/';
    }
    $state.='|';
    fscanf(STDIN, "%d", $cardLocationsCount);
    $state.=$cardLocationsCount.'|';

    for ($i = 0; $i < $cardLocationsCount; $i++)
    {
        // $cardsLocation: the location of the card list. It can be HAND, DRAW, DISCARD or OPPONENT_CARDS (AUTOMATED and OPPONENT_AUTOMATED will appear in later leagues)
        fscanf(STDIN, "%s %d %d %d %d %d %d %d %d %d %d", $cardsLocation, $trainingCardsCount, $codingCardsCount, $dailyRoutineCardsCount, $taskPrioritizationCardsCount, $architectureStudyCardsCount, $continuousDeliveryCardsCount, $codeReviewCardsCount, $refactoringCardsCount, $bonusCardsCount, $technicalDebtCardsCount);
        $state.=
            $cardsLocation.','
            .$trainingCardsCount.','
            .$codingCardsCount.','
            .$dailyRoutineCardsCount.','
            .$taskPrioritizationCardsCount.','
            .$architectureStudyCardsCount.','
            .$continuousDeliveryCardsCount.','
            .$codeReviewCardsCount.','
            .$refactoringCardsCount.','
            .$bonusCardsCount.','
            .$technicalDebtCardsCount.'/';

    }
    $state.='|';
    fscanf(STDIN, "%d", $possibleMovesCount);
    $state.=$possibleMovesCount.'|';

    for ($i = 0; $i < $possibleMovesCount; $i++)
    {
        $possibleMove = stream_get_line(STDIN, 256 + 1, "\n");
        $state.=$possibleMove.',';
    }

    error_log(var_export($state, true));
    $parser = new Parser();
    $gameStateParsed = $parser->parse($state);
    $game = new Game();
    $game->update($gameStateParsed);
   
    
    if ($gamePhase == 'RELEASE')
    {
        $strategy = new Release($game);
        if ($canbuild = $strategy->canIbuildAnApplicationsWithMyhand()){
            echo 'RELEASE '.array_shift($canbuild). "\n";
        }else {
            echo("WAIT\n");

        }
    }else if ($gamePhase == 'MOVE'){
        $strategy = new Move($game);
        $canbuild = $strategy->whatApplicationsCanIbuildWithMyhandForReleasePhase();
        
        if (!empty($canbuild)){

            echo 'MOVE '.array_shift($canbuild). "\n";
        }else {
            echo("RANDOM\n");

        }
    }else {
        echo("RANDOM\n");
    }
    //else if ($whatCanIbuildnextTurn = $game->canIbuildAnApplicationsWithMyhandNextTurn()) {
    //     var_dump($whatCanIbuildnextTurn);

    //     echo("RANDOM\n");

    // }else
    // {
    //     $position = rand(0,7);
    // }
    // Write an action using echo(). DON'T FORGET THE TRAILING \n
    // To debug: error_log(var_export($var, true)); (equivalent to var_dump)

        
        // In the first league: RANDOM | MOVE <zoneId> | RELEASE <applicationId> | WAIT; In later leagues: | GIVE <cardType> | THROW <cardType> | TRAINING | CODING | DAILY_ROUTINE | TASK_PRIORITIZATION <cardTypeToThrow> <cardTypeToTake> | ARCHITECTURE_STUDY | CONTINUOUS_DELIVERY <cardTypeToAutomate> | CODE_REVIEW | REFACTORING;
    
}

