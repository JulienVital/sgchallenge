<?php
// Last compile time: 20/06/22 0:03 




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

