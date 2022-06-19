<?php
namespace tests;

use app\Game\Parser;
use PHPUnit\Framework\TestCase;

class ParserTest extends TestCase
{
    public function __construct()
    {
        parent::__construct(); 
        $this->state = 'MOVE|12|APPLICATION,13,0,0,0,0,2,0,2,2/APPLICATION,27,0,0,2,0,2,2,0,0/APPLICATION,14,2,2,0,2,0,0,0,0/APPLICATION,7,2,0,0,2,0,2,0,0/APPLICATION,8,2,0,0,0,2,2,0,0/APPLICATION,11,0,0,2,2,2,0,0,0/APPLICATION,25,2,2,0,0,2,0,0,0/APPLICATION,18,0,0,2,0,2,0,2,0/APPLICATION,1,0,2,0,0,2,0,0,2/APPLICATION,15,2,2,0,0,0,0,0,2/APPLICATION,20,0,0,0,2,0,0,2,2/APPLICATION,17,2,2,0,0,0,2,0,0/|-1,0,0,0/-1,0,0,0/|3|HAND,0,0,0,0,0,0,0,0,1,3/DRAW,0,0,0,0,0,0,0,0,3,1/OPPONENT_CARDS,0,0,0,0,0,0,0,0,4,4/|9|MOVE 0,MOVE 1,MOVE 2,MOVE 3,MOVE 4,MOVE 5,MOVE 6,MOVE 7,RANDOM,|';
        $parser = new Parser();
        $this->stateParsed = $parser->parse($this->state);
    }
    
       
    
    public function testParse()
    {
  
        $result = $this->stateParsed;
        $this->assertEquals('MOVE', $result['gamePhase']);
        $this->assertEquals('12', $result['applicationsCount']);
        $this->assertEquals('12', count($result['applications']));
        $this->assertEquals('APPLICATION', $result['applications'][0]['objectType']);
        $this->assertEquals('13', $result['applications'][0]['id']);
        $this->assertEquals('0', $result['applications'][0]['training']);
        $this->assertEquals('0', $result['applications'][0]['coding']);
        $this->assertEquals('0', $result['applications'][0]['dailyRoutine']);
        $this->assertEquals('0', $result['applications'][0]['taskPrioritization']);
        $this->assertEquals('2', $result['applications'][0]['architectureStudy']);
        $this->assertEquals('0', $result['applications'][0]['continuousDelivery']);
        $this->assertEquals('2', $result['applications'][0]['codeReview']);
        $this->assertEquals('2', $result['applications'][0]['refactoring']);
        $this->assertEquals('17', $result['applications'][11]['id']);
        
    }

    public function testCardLocationParse(){
        $string= "HAND,1,0,0,1,0,0,0,0,0,3/DRAW,0,0,0,0,0,0,0,0,2,0/DISCARD,0,0,0,0,2,0,0,1,2,9/OPPONENT_CARDS,1,5,1,1,0,0,0,0,4,18/";
        $parser = new Parser();
        $result = $parser->parseCardLocations($string);
        $this->assertEquals('HAND', $result['HAND']['cardsLocation']);
        $this->assertEquals(3, $result['HAND']['technicalDebt']);
        $this->assertEquals('DRAW', $result['DRAW']['cardsLocation']);
        $this->assertEquals(2, $result['DRAW']['bonus']);
        $this->assertEquals('DISCARD', $result['DISCARD']['cardsLocation']);
        $this->assertEquals(1, $result['DISCARD']['refactoring']);
        $this->assertEquals('OPPONENT_CARDS', $result['OPPONENT_CARDS']['cardsLocation']);
        $this->assertEquals(5, $result['OPPONENT_CARDS']['coding']);
    }
    public function testParsePlayers()
    {
    
        $result = $this->stateParsed['players'];
        $this->assertEquals('-1', $result[0]['location']);
        $this->assertEquals('0', $result[0]['score']);
        $this->assertEquals('0', $result[0]['permanentDailyRoutineCards']);
        $this->assertEquals('0', $result[0]['permanentArchitectureStudyCards']);
        $this->assertEquals('-1', $result[1]['location']);
        $this->assertEquals('0', $result[1]['score']);
        $this->assertEquals('0', $result[1]['permanentDailyRoutineCards']);
        $this->assertEquals('0', $result[1]['permanentArchitectureStudyCards']);
    }

    public function testParseOneApplication(){
        $stringToParse = 'APPLICATION,13,0,0,0,0,2,0,2,2';
        $arrayExcept = [
            'objectType' => 'APPLICATION',
            'id' => 13,
            'training' => 0,
            'coding' => 0,
            'dailyRoutine' => 0,
            'taskPrioritization' => 0,
            'architectureStudy' => 2,
            'continuousDelivery' => 0,
            'codeReview' => 2,
            'refactoring' => 2,
        ];
        $parser = new Parser();

        $this->assertEquals($arrayExcept, $parser->parseOneApplication($stringToParse));

    }

    public function testParseMultipleApplications(){
         $stringToParse ='APPLICATION,13,0,0,0,0,2,0,2,2/APPLICATION,27,0,0,2,0,2,2,0,0/';
         $arrayExcept = [[
            'objectType' => 'APPLICATION',
            'id' => 13,
            'training' => 0,
            'coding' => 0,
            'dailyRoutine' => 0,
            'taskPrioritization' => 0,
            'architectureStudy' => 2,
            'continuousDelivery' => 0,
            'codeReview' => 2,
            'refactoring' => 2,
        ],[
            'objectType' => 'APPLICATION',
            'id' => 27,
            'training' => 0,
            'coding' => 0,
            'dailyRoutine' => 2,
            'taskPrioritization' => 0,
            'architectureStudy' => 2,
            'continuousDelivery' => 2,
            'codeReview' => 0,
            'refactoring' => 0,
        ]];
        $parser = new Parser();
        
        $this->assertEquals($arrayExcept, $parser->parseApplications($stringToParse));
    }

}

