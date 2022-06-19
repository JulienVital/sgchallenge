<?php
namespace tests;

use app\Game\Game;
use app\Game\Parser;
use app\Game\Strategy\Move;
use PHPUnit\Framework\TestCase;

class MoveStrategyTest extends TestCase
{
    public function __construct(){
        parent::__construct();
        $this->parser = new Parser();

    }

    public function testStarted(){
        
        $this->assertTrue(true);
    }

    public function testWhatCanIBuildForRelease(){
        $state = 'MOVE|6|APPLICATION,16,2,0,0,0,0,2,2,0/APPLICATION,18,0,0,2,0,2,0,2,0/APPLICATION,0,0,0,0,2,2,0,2,0/APPLICATION,17,2,2,0,0,0,2,0,0/APPLICATION,20,0,0,0,2,0,0,2,2/APPLICATION,8,2,0,0,0,2,2,0,0/|2,2,0,0/5,4,0,0/|3|HAND,0,0,1,0,1,0,0,0,1,1/DISCARD,1,1,1,1,0,0,0,0,3,3/OPPONENT_CARDS,1,1,1,1,1,1,0,0,4,16/|8|MOVE 3,MOVE 4,MOVE 5,MOVE 6,MOVE 7,MOVE 0,MOVE 1,RANDOM,';
        $game = new Game();
        $gameStateParsed = $this->parser->parse($state);
        $game->update($gameStateParsed);
        $strategy = new Move($game);
        $canbuild = $strategy->whatApplicationsCanIbuildWithMyhandForReleasePhase();
        $expect = 6;
        $this->assertEquals($expect, array_shift($canbuild));
    }

    public function testWhatCanIBuildForReleaseOther(){
        $state ='MOVE|6|APPLICATION,11,0,0,2,2,2,0,0,0/APPLICATION,0,0,0,0,2,2,0,2,0/APPLICATION,13,0,0,0,0,2,0,2,2/APPLICATION,1,0,2,0,0,2,0,0,2/APPLICATION,23,2,0,0,2,0,0,0,2/APPLICATION,19,0,2,2,0,0,0,0,2/|7,2,0,0/0,4,0,0/|4|HAND,1,0,0,1,0,1,0,0,0,1/DRAW,0,0,0,0,0,1,0,1,0,1/DISCARD,0,1,0,1,0,1,0,1,4,2/OPPONENT_CARDS,2,1,1,1,1,1,1,1,4,17/|8|MOVE 0,MOVE 1,MOVE 2,MOVE 3,MOVE 4,MOVE 5,MOVE 6,RANDOM,';
        $game = new Game();
        $gameStateParsed = $this->parser->parse($state);
        $game->update($gameStateParsed);
        $strategy = new Move($game);
        $canbuild = $strategy->whatApplicationsCanIbuildWithMyhandForReleasePhase();
        $expect = 7;
        $this->assertNotEquals($expect, array_shift($canbuild));
    }

    public function testGetAcardIfCantBuild(){
        $state ='MOVE|11|APPLICATION,9,2,0,2,0,0,0,2,0/APPLICATION,5,0,0,2,2,0,2,0,0/APPLICATION,19,0,2,2,0,0,0,0,2/APPLICATION,27,0,0,2,0,2,2,0,0/APPLICATION,7,2,0,0,2,0,2,0,0/APPLICATION,24,0,0,0,2,0,2,2,0/APPLICATION,2,0,0,2,0,0,0,2,2/APPLICATION,4,0,2,0,2,0,0,0,2/APPLICATION,0,0,0,0,2,2,0,2,0/APPLICATION,25,2,2,0,0,2,0,0,0/APPLICATION,11,0,0,2,2,2,0,0,0/|2,0,0,0/0,1,0,0/|3|HAND,0,0,0,0,0,0,0,0,3,1/DISCARD,0,0,1,0,0,0,0,0,1,3/OPPONENT_CARDS,1,0,0,0,0,0,0,0,4,5/|8|MOVE 3,MOVE 4,MOVE 5,MOVE 6,MOVE 7,MOVE 0,MOVE 1,RANDOM,';
        $game = new Game();
        $gameStateParsed = $this->parser->parse($state);
        $game->update($gameStateParsed);
        $strategy = new Move($game);
        $canbuild = $strategy->calcCardForAllApplication();
        var_dump($canbuild);
    }
}

