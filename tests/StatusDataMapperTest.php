<?php

class StatusDataMapperTest extends TestCase
{
    private $con;

    public function setUp()
    {
        $this->con = new \Model\Connection('sqlite::memory:');
		$this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $tmp = $this->con->exec(<<<SQL
CREATE TABLE IF NOT EXISTS statuses (
  `id` int(11) PRIMARY KEY,
  `message` varchar(140) NOT NULL,
  `date_tweet` datetime NOT NULL,
  `nom` varchar(50) NOT NULL,
  `os` varchar(20) NULL
);
SQL
        );
    }

    public function testPersist()
    {
        $mapper = new \Model\StatusMapper($this->con);

		$rows = $this->con->query('SELECT COUNT(*) FROM statuses')->fetch(\PDO::FETCH_NUM);
        $nbRows = $rows[0];

		$status = new \Model\Status('Toto', 'le message', '2016-01-01', 'android', 1);
		$mapper->persist($status);
		$nbRows++;

		$rows = $this->con->query('SELECT COUNT(*) FROM statuses')->fetch(\PDO::FETCH_NUM);
        $this->assertEquals($nbRows, $rows[0]);
    }

	public function testRemove()
    {
        $mapper = new \Model\StatusMapper($this->con);

		$rows = $this->con->query('SELECT COUNT(*) FROM statuses')->fetch(\PDO::FETCH_NUM);
        $nbRows = $rows[0];

		$status = new \Model\Status('Toto', 'le message', '2016-01-01', 'android', 1);
		$mapper->persist($status);

		$mapper->remove($status);

		$rows = $this->con->query('SELECT COUNT(*) FROM statuses')->fetch(\PDO::FETCH_NUM);
        $this->assertEquals($nbRows, $rows[0]);
    }
}
