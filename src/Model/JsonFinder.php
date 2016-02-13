<?php

namespace Model;

	class JsonFinder implements FinderInterface {
		
		private $fileName = "../src/Model/data/statuses.json";
		private $content;
		private $liste;
		
		public function __construct() {
			$this->content = file_get_contents($this->fileName);
			$this->liste = json_decode($this->content, true);
		}
		
		public function findAll($connection, $criteria) {
			return $this->liste;
		}
		
		public function findOneById($connection, $id) {
			foreach($this->liste as $key=>$tweet) {
				if($tweet['id'] == $id) {
					return $tweet;
				}
			}
			return null;
		}

		public function getFileName() {
			return $this->fileName;
		}
		
	}
