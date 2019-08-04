<?php

/**
 * Simple implementation of a Message Queue 
 * Test/Example
 * 
 * In this sample I show a simple use case where both queue push and pop are in the same process, 
 * but usually you will have at least 2 different processes, one that pushes data into the queue
 * and another that pops data from the queue  
 * 
 * @author Maurizio Giunti https://www.mauriziogiunti.it / https://codeguru.it
 * @license MIT
 *  
 * */	

include_once('memqueue.php');

$qn='testQueue'; // You can have multiple queues with different names

$mq=new Memqueue('127.0.0.1',11211); // Create queue manager object and connect to Memcached backend

// Push some data into the queue - Data can be any object
$mq->push($qn,'1');
$mq->push($qn,'2');
$mq->push($qn,'3');

// Retrieve data from the queue
while(!$mq->is_empty($qn)) {
	$r=$mq->pop($qn);
	echo "> ".$r."\n";
}

