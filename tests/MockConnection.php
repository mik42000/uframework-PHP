<?php

use Model\Connection;

class MockConnection extends Connection
{
    public function __construct()
    {
    }

	public function executeQuery($query, array $parameters = []) {
		$stmt = $this->prepare($query);

		foreach($parameters as $name=>$value) {
			$stmt->bindValue(':'.$name,$value);
		}

		return $stmt->queryString;
	}
}
