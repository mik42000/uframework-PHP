<?php

namespace Model;

class Status
{
    /**
     * @var int|null
     */
    private $id;

	/**
     * @var string
     */
    private $nom;

	/**
     * @var string
     */
    private $message;

	/**
     * @var DateTime
     */
    private $dateTweet;

    /**
     * @var string|null
     */
    private $os;

    public function __construct($nom, $message, $dateTweet, $os = null, $id = null)
    {
		$this->id = $id;
		$this->nom = $nom;
		$this->message = $message;
		$this->dateTweet = $dateTweet;
		$this->os = $os;
    }

	
	public function getId() {
		return $this->id;
	}

	public function getNom() {
		return $this->nom;
	}

	public function getMessage() {
		return $this->message;
	}

	public function getDateTweet() {
		return $this->dateTweet;
	}

	public function getOs() {
		return $this->os;
	}
}
