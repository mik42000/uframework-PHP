<?php

namespace Model;

class Connection extends \PDO {

	public function executeQuery($query, array $parameters = []) {
		$stmt = $this->prepare($query);

		foreach($parameters as $name=>$value) {
			$stmt->bindValue(':'.$name,$value);
		}

		return $stmt->execute();
	}
	
}
