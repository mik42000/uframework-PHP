<?php

class TestCase extends \PHPUnit_Framework_TestCase
{

	public function testConnectionReturnGoodQuery(){
		$mock = $this->getMock('MockConnection');

		$query = 'SELECT * FROM statuses WHERE nom=:nom AND message=:message';
		$tabParam = [
			'nom' => 'toto',
			'message' => 'tata',
		];
		$result = $mock->executeQuery($query, $tabParam);
		$this->assertEquals("SELECT * FROM statuses WHERE nom='toto' AND message='tata'", $result);
	}

}
