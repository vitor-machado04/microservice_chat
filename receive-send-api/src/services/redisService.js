const redis = require('redis');
const client = redis.createClient({
  url: `redis://${process.env.REDIS_HOST}:${process.env.REDIS_PORT}`
});
client.connect();

exports.enqueueMessage = async (queueName, message) => {
  await client.rPush(queueName, JSON.stringify(message));
};

exports.dequeueAllMessages = async (queueName) => {
  const messages = [];
  let msg;
  do {
    msg = await client.lPop(queueName);
    if (msg) messages.push(JSON.parse(msg));
  } 
  while (msg !== null);
  return messages;
};

exports.ping = async () => {
  const pong = await client.ping();
  if (pong !== 'PONG') {
    throw new Error('Redis não respondeu PONG');
  }
};
