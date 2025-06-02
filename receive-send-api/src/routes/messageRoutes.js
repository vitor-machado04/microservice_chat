const express = require('express');
const router = express.Router();
const messageController = require('../controllers/messageController');
const redisService = require('../services/redisService');

router.post('/', messageController.postMessage);
router.post('/worker', messageController.postMessageWorker);
router.get('/', messageController.getMessages);
router.get('/health', async (req, res) => {
  try {
    await redisService.ping();
    res.json({ status: 'ok', redis: 'connected' });
  } 
  catch (error) {
    res.status(500).json({ status: 'error', redis: 'disconnected', error: error.message });
  }
});

module.exports = router;
