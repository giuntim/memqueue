<?php

/**
 * Simple implementation of a Message Queue 
 * Test/Example
 * 
 * In this sample I show a simple use case where both queue push and pop are in the same process, 
 * but usually you will have at least 2 different processes, one that pushes data into the queue
 * and another that pops data from the queue.
 * Also here I use simple strings as message contents, but any kind of valid PHP object can be used.  
 * 
 * @author Maurizio Giunti https://www.mauriziogiunti.it / https://codeguru.it
 * @license MIT
 *  
 * */	

include_once('memqueue.php');

$qn='testQueue'; // You can have multiple queues with different names

$mq=new Memqueue('127.0.0.1',11211); // Create queue manager object and connect to Memcached backend

// Push some data into the queue - Data can be any object
$id1=$mq->push($qn,'*Message 1*');
$id2=$mq->push($qn,'*Message 2*');
$id3=$mq->push($qn,'*Message 3*');

// Retrieve all data from the queue
while(!$mq->is_empty($qn)) {
	$r=$mq->pop($qn,$id);
	echo "> ".$r."\n";

	// Post a reply for the executed message
	$mq->reply($qn,$id,"This is reply to message $id");	

	// Has message been received 
	echo "Has *Message 2* been processed?  > ". ($mq->is_processed($qn, $id2) ? "Processed" : "Not processed") ."\n";

}

$r=$mq->getReply($qn,$id2);
echo "Reply to message $id2: $r \n";
