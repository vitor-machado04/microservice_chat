const authService = require('../services/authService');
const redisService = require('../services/redisService');
const recordService = require('../services/recordService');

exports.postMessage = async (req, res) => {
  const token = req.headers.authorization;
  const { userIdSend, userIdReceive, message } = req.body;

  const authResp = await authService.verifyAuth(token, userIdSend);
  if (!authResp.auth) return res.status(401).json({ msg: 'not auth' });

  await redisService.enqueueMessage(`${userIdSend}${userIdReceive}`, { message });

  await recordService.saveMessage(userIdSend, userIdReceive, message);

  return res.json({ message: 'message sended with success' });
};

exports.postMessageWorker = async (req, res) => {
  const token = req.headers.authorization;
  const { userIdSend, userIdReceive } = req.body;

  const authResp = await authService.verifyAuth(token, userIdSend);
  if (!authResp.auth) return res.status(401).json({ msg: 'not auth' });

  // Lê mensagens da fila Redis e salva no banco
  const channel = `${userIdSend}${userIdReceive}`;
  const msgs = await redisService.dequeueAllMessages(channel);

  for (const msg of msgs) {
    await recordService.saveMessage(userIdSend, userIdReceive, msg.message);
  }

  return res.json({ msg: 'ok' });
};

exports.getMessages = async (req, res) => {
  const token = req.headers.authorization;
  const email = req.query.email;
  const userId = req.query.user;

  const authResp = await authService.verifyAuth(token, userId);
  if (!authResp.auth) return res.status(401).json({ msg: 'not auth' });

  // Busca todos usuários da Auth-API
  const users = await authService.getAllUsers(email);

  // Para cada usuário, busca mensagens da tabela message (Record-API)
  const messages = [];
  for (const user of users) {
    const msgs = await recordService.getMessages(user.user_id); // ← CORRETO: user_id individual
    messages.push(...msgs.map(m => ({ userId: user.user_id, msg: m.message })));
  }

  return res.json(messages);
};
