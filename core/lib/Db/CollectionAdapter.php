<?php
namespace Zotto\Db;

class CollectionAdapter
{

    /**
     * @var \MongoCollection $collection
     */
    protected $collection;
    protected $collectionName;

    protected $mongo;

    public function __construct($mongo=null, $collectionName = null )
    {
        if($mongo===null){
            $this->mongo = $this->getDefaultConnection();
        }

        if ($mongo instanceof \MongoDB){
           $this->mongo = $mongo;
        }

        if ($collectionName !== null){
            $this->collectionName = $collectionName;
        }

        $this->collection =  $this->mongo->{$this->collectionName};

    }

    public function getDefaultConnection(){
       throw new \Exception('abstract get default connection');
    }

    public function getCollection(){
        return $this->collection;
    }

    /**
     * Find all elementsHover of collection
     * @return \MongoCursor
     */
    public function findAll()
    {
        return $this->collection->find();
    }


    /**
     * Get array containts all elements of collection
     * @return array
     */
    public function getAll()
    {
        return iterator_to_array($this->findAll());
    }


    /**
     * Get Element by mongoId
     * @param $id
     * @return array|bool|null
     */
    public function getById($id)
    {
        try {
            return $this->collection->findOne(array("_id" => new \MongoId($id)));
        } catch (\MongoException $e) {
            return false;
        }
    }


    /**
     * Get element by criteria
     * @param $fields
     * @param bool $val
     * @return array
     */
    public function getBy($fields, $val = false)
    {

        return iterator_to_array($this->findBy($fields, $val));

    }

    /**
     * Find Element by criteria
     * @param $fields
     * @param bool $val
     * @return \MongoCursor
     */
    public function findBy($fields, $val = false)
    {
        if (is_array($fields)) {
            return $this->collection->find($fields);
        } else {
            if (is_array($val)) {
                return $this->collection->find(array($fields => array('$in' => $val)));
            } else {
                return $this->collection->find(array($fields => $val));
            }
        }
    }

    public function getOneBy($fields, $val = false)
    {
        if (is_array($fields)) {
            if($val===false){
                $val=[];
            }
            return $this->collection->findOne($fields, $val);
        } else {
            return $this->collection->findOne(array($fields => $val));
        }
    }

    public function update($criteria, $set)
    {
        if ($criteria instanceof \MongoId) {
            return $this->collection->update(array("_id" => $criteria), $set);
        } else {
            return $this->collection->update($criteria, $set);
        }
    }

    public function inc($criteria, $field, $count = 1)
    {
        if ($criteria instanceof \MongoId) {
            return $this->collection->update(array("_id" => $criteria), array('$inc' => array($field => $count)));
        } else {
            return $this->collection->update($criteria, array('$inc' => array($field => $count)));
        }
    }

    public function dec($criteria, $field, $count = 1)
    {
        if ($criteria instanceof \MongoId) {
            return $this->collection->update(array("_id" => $criteria), array('$inc' => array($field => -$count)));
        } else {
            return $this->collection->update($criteria, array('$inc' => array($field => -$count)));
        }

    }

    public function unsetField($criteria, $field)
    {
        if ($criteria instanceof \MongoId) {
            return $this->collection->update(array("_id" => $criteria), array('$unset' => array($field => "")));
        } else {
            return $this->collection->update($criteria, array('$unset' => array($field => "")));
        }

    }

    public function set($criteria, $set, $multi = true)
    {
        if ($criteria instanceof \MongoId) {
            return $this->collection->update(array("_id" => $criteria), array('$set' => $set));
        } else {
            return $this->collection->update($criteria, array('$set' => $set), array('multiple' => $multi));
        }

    }

    public function insert($set)
    {
        $this->collection->insert($set);
        return $set;
    }


    public function remove($criteria)
    {
        if ($criteria instanceof \MongoId) {
            return $this->collection->remove(array("_id" => $criteria));
        } else {
            return $this->collection->remove($criteria);
        }

    }

    public function ensureIndex($fields){
        $this->collection->ensureIndex($fields);
    }

}
