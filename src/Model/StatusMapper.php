<?php

namespace Model;

class StatusMapper {

	private $con;

	public function __construct(Connection $con) {
		$this->con = $con;
	}

	public function persist(Status $status) {
		$query = "INSERT INTO statuses(id, nom, message, date_tweet, os) VALUES(:id, :nom, :message, :date_tweet, :os)";
		$this->con->executeQuery($query, ["id" => $status->getId(), "nom" => $status->getNom(), "message" => $status->getMessage(), "date_tweet" => $status->getDateTweet(), "os" => $status->getOs()]);
	}

	public function remove(Status $status) {
		$query = "DELETE FROM statuses WHERE id=:id";
		$this->con->executeQuery($query, ["id" => $status->getId()]);
	}

}
