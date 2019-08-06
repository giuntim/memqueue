# memqueue
Simple PHP message queue with Memcached backend.

You can use a Memcached server as backend for a message queue with this very simple class.
Class also allows consumers to post replies to message posters.

## How to push data into the queue

```
$mq = new Memqueue('memacached.server.address',11211);
$mq->push('queueName',$data);

```

## How to pop data from the queue

```
$mq = new Memqueue('memacached.server.address',11211);
$data = $mq->pop('queueName');

```

## Pop data from queue and post a reply

```
$mq = new Memqueue('memacached.server.address',11211);
$data = $mq->pop('queueName',$mid);
// process the data
// ...
// Send back a reply
$mq->reply('queueName, $mid, $reply);

```

## Send a message, wait for processing and get a reply

```
$mq = new Memqueue('memacached.server.address',11211);
$mid = $mq->push('queueName',$data);

// Wait for execution
while(! $mq->is_processed('queueName', $mid)) {
  sleep(1);
}

$reply = $mq->getReply('queueName', $mid);

```
