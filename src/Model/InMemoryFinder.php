<?php

namespace Model;

class InMemoryFinder implements FinderInterface {

	private $liste;

	public function __construct() {
		$this->liste = array(
			array(
				"id" => "1",
				"message" => "Ce tweet is good",
				"date" => "26-01-2016",
				"nom" => "jojo42",
				"os" => "Android",
			),
			array(
				"id" => "2",
				"message" => "Aller voir tou les film d mila kunis",
				"date" => "26-01-2016",
				"nom" => "lesrabatdu63",
			),

		);
	}

	public function findAll() {
		return $this->liste;
	}

	public function findOneById($id) {
		foreach($this->liste as $ind=>$elt) {
			if($elt["id"] == $id) {
				return $elt;
			}		
		}
		return null;
	}
}
