<?php

namespace Model;

class DatabaseFinder implements FinderInterface {
	
	public function findAll($connection, $criteria) {
		$query = "SELECT * FROM statuses";
		
		if(isset($criteria['orderBy'])){
			$query .= ' ORDER BY '.$criteria['orderBy'];
		}
		if(isset($criteria['limit'])){
			$query .= ' LIMIT 0, '.$criteria['limit'];
		}

		$stmt = $connection->query($query);
		$listeDb = $stmt->fetchAll(\PDO::FETCH_ASSOC);
		$listeStatuses = array();
		foreach($listeDb as $ind=>$elt) {
			$listeStatuses[] = new Status($elt['nom'], $elt['message'], $elt['date_tweet'], $elt['os'], $elt['id']);
		} 	
		return $listeStatuses;
	}
	
	public function findOneById($connection, $id) {
		$stmt = $connection->query("SELECT * FROM statuses WHERE id=".$id."");
		$tweet = $stmt->fetch(\PDO::FETCH_ASSOC);
		$status = new Status($tweet['nom'], $tweet['message'], $tweet['date_tweet'], $tweet['os'], $tweet['id']);
		return $status;
	}
	
}
