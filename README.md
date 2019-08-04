# memqueue
Simple PHP Task queue with Memcached backend.


You can use a Memcached server as backend for a message queue with this very simple class.




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

