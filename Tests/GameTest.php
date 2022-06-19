<?php
namespace tests;

use app\Game\Game;
use app\Game\Parser;
use PHPUnit\Framework\TestCase;

class GameTest extends TestCase
{
    public function __construct(){
        parent::__construct();
        $this->parser = new Parser();

    }
    public function testCalcCardForAllApplication(){
        $state ='MOVE|5|APPLICATION,27,0,0,2,0,2,2,0,0/APPLICATION,26,2,0,2,0,2,0,0,0/APPLICATION,7,2,0,0,2,0,2,0,0/APPLICATION,8,2,2,0,0,2,2,0,0/APPLICATION,11,0,0,0,2,2,0,0,0/|7,3,0,0/3,4,0,0/|4|HAND,0,0,0,0,0,0,0,0,2,2/DRAW,0,0,0,0,0,1,0,0,1,2/DISCARD,0,0,1,1,0,0,0,1,1,11/OPPONENT_CARDS,1,1,1,1,0,0,0,0,4,15/|8|MOVE 0,MOVE 1,MOVE 2,MOVE 3,MOVE 4,MOVE 5,MOVE 6,RANDOM,';
        $parser = new Parser();
        $stateParsed = $parser->parse($state);
        $game = new Game();
        $game->update($stateParsed);
        $cards = $game->calcCardForAllApplication($stateParsed['applications']);
        
        $this->assertEquals($cards['training'], 6);
        $this->assertEquals($cards['coding'], 2);
        $this->assertEquals($cards['dailyRoutine'], 4);
        $this->assertEquals($cards['taskPrioritization'], 4);
        $this->assertEquals($cards['architectureStudy'], 8);
        $this->assertEquals($cards['continuousDelivery'], 6);
        $this->assertEquals($cards['codeReview'], 0);
        $this->assertEquals($cards['refactoring'], 0);
    }



}

