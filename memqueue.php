<?php
	

/**
 * Simple implementation of a Message Queue 
 * that uses Memcached https://memcached.org/
 * as backend
 * 
 * Needs PHP Memcached extensions to be installed to work properly
 * 
 * @author Maurizio Giunti https://www.mauriziogiunti.it / https://codeguru.it
 * @license MIT
 *  
 * */	
class Memqueue {
	
	var $memcached = NULL;
	var $ttl = 3600; // Tempo max permanenza in coda
	
	/**
	 * constructor: need connection params
	 * @param string $host Memcached server hostname or ip
	 * @param int $port Memcached server port 
	 * @param int $ttl Time to live in seconds for the queue
	 * */
	function __construct($host,$port,$ttl=3600) {
		
		if(!class_exists('Memcached')) {
			die("Class Memcached not found!\n");
		}

		$this->memcached = new Memcached;			
		$this->memcached->addServer($host, $port);
		// Todo: track init error?
	}
	
	private function __clone() {}
			
			
	/**
	 * is_empty: is the queue empty?
	 * @param string $queue name of the queue
	 * @return TRUE: the queue is empty, FALSE: there is something in the queue
	 * */
	public function is_empty($queue) {
	
		$head = intval($this->memcached->get($queue."_head"));
		$tail = intval($this->memcached->get($queue."_tail"));
		
		return ($tail>0 && $head >= $tail);
	}
	
	/* * 
	 * pop : pops an element from the beginning of the queue
	 * @param string $queue name of the queue
	 * @return the stored object or FALSE if the queue is empty
	 * */
	public function pop($queue) {
		if($this->is_empty($queue)) return FALSE; // Empty queue
		
		$tail = $this->memcached->get($queue."_tail");
		$id = $this->memcached->increment($queue."_head");
		return $this->memcached->get($queue."_".($id));
	}
	
	/* * 
	 * push : adds an element at the end of the queue
	 * @param string $queue name of the queue
	 * @param object $item the item to store in the queue
	 * @return int the id of the element in the queue or FALSE in case of error
	 * */
	public function push($queue, $item) {
		
		$id = intval($this->memcached->increment($queue."_tail"));
		if($id==0) { // Empty queue: must init
			$this->memcached->add($queue."_tail", 1, $this->ttl); // Init tail
			$this->memcached->add($queue."_head", 0, $this->ttl); // Init head
			$id=1;
		}
					
		if($this->memcached->add($queue."_".$id, $item, $this->ttl) === FALSE) {
			return FALSE;
		}			
		return $id;
	}
	
}

