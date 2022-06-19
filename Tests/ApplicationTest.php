<?php
namespace tests;

use app\Game\Application;
use app\Game\Game;
use app\Game\Parser;
use PHPUnit\Framework\TestCase;

class ApplicationTest extends TestCase
{
    private $parser;
    public function __construct(){
        parent::__construct();
        $this->parser = new Parser();

    }
    
    public function testStarted(){
        
        $this->assertTrue(true);
    }
    public function testCanIbuildAnApplicationWithMyhand(){

        $appArray = [
            "objectType"=> "APPLICATION",
            "id"=> 27,
            "training"=> "2",
            "coding"=> "2",
            "dailyRoutine"=> "2",
            "taskPrioritization"=> "0",
            "architectureStudy"=> "0",
            "continuousDelivery"=> "0",
            "codeReview"=> "0",
            "refactoring"=> "0"
        ];

        
        $myHand =[
            "cardsLocation"=> "HAND",
            "training"=> "1",
            "coding"=> "1",
            "dailyRoutine"=> "1",
            "taskPrioritization"=> "0",
            "architectureStudy"=> "0",
            "continuousDelivery"=> "0",
            "codeReview"=> "0",
            "refactoring"=> "0",
            "bonus"=> "0",
            "technicalDebt"=> "1"
            ];
        $app = new Application($appArray);

        $canIbuild = $app->canIbuildThisApplication($myHand);

        $this->assertTrue($canIbuild);
        
    }
    public function testCantbuildAnApplicationWithMyhand(){

        $appArray = [
            "objectType"=> "APPLICATION",
            "id"=> 27,
            "training"=> "2",
            "coding"=> "2",
            "dailyRoutine"=> "2",
            "taskPrioritization"=> "0",
            "architectureStudy"=> "0",
            "continuousDelivery"=> "0",
            "codeReview"=> "0",
            "refactoring"=> "0"
        ];

        
        $myHand =[
            "cardsLocation"=> "HAND",
            "training"=> "0",
            "coding"=> "0",
            "dailyRoutine"=> "0",
            "taskPrioritization"=> "0",
            "architectureStudy"=> "0",
            "continuousDelivery"=> "0",
            "codeReview"=> "0",
            "refactoring"=> "0",
            "bonus"=> "0",
            "technicalDebt"=> "4"
            ];
        $app = new Application($appArray);

        $canIbuild = $app->canIbuildThisApplication($myHand);
        $this->assertFalse($canIbuild);
        
    }
    public function testCantbuildAnApplicationWithMyhandAndBonus(){

        $appArray = [
            "objectType"=> "APPLICATION",
            "id"=> 27,
            "training"=> "2",
            "coding"=> "2",
            "dailyRoutine"=> "2",
            "taskPrioritization"=> "0",
            "architectureStudy"=> "0",
            "continuousDelivery"=> "0",
            "codeReview"=> "0",
            "refactoring"=> "0"
        ];

        
        $myHand =[
            "cardsLocation"=> "HAND",
            "training"=> "1",
            "coding"=> "1",
            "dailyRoutine"=> "0",
            "taskPrioritization"=> "0",
            "architectureStudy"=> "0",
            "continuousDelivery"=> "0",
            "codeReview"=> "0",
            "refactoring"=> "0",
            "bonus"=> "2",
            "technicalDebt"=> "0"
            ];
        $app = new Application($appArray);

        $canIbuild = $app->canIbuildThisApplication($myHand);
        $this->assertTrue($canIbuild);
        
    }

    public function testCanIbuildThisForReleasePhase(){

        $appArray = [
            "objectType"=> "APPLICATION",
            "id"=> 27,
            "training"=> "2",
            "coding"=> "2",
            "dailyRoutine"=> "2",
            "taskPrioritization"=> "0",
            "architectureStudy"=> "0",
            "continuousDelivery"=> "0",
            "codeReview"=> "0",
            "refactoring"=> "0"
        ];

        
        $myHand =[
            "cardsLocation"=> "HAND",
            "training"=> "1",
            "coding"=> "0",
            "dailyRoutine"=> "0",
            "taskPrioritization"=> "0",
            "architectureStudy"=> "0",
            "continuousDelivery"=> "0",
            "codeReview"=> "0",
            "refactoring"=> "1",
            "bonus"=> "2",
            "technicalDebt"=> "0"
            ];
        $app = new Application($appArray);

        $canIbuild = $app->canIbuildThisNextTurn($myHand);
        $this->assertTrue($canIbuild);
        
    }
}

