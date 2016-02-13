<?php

namespace Model;

interface FinderInterface
{
    /**
     * Returns all elements.
     *
*@param  mixed      $connection
     * @return array
     */
    public function findAll($connection, $criteria);

    /**
     * Retrieve an element by its id.
     *
	*@param  mixed      $connection
     * @param  mixed      $id
     * @return null|mixed
     */
    public function findOneById($connection, $id);
}
