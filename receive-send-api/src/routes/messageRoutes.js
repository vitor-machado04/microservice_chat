const express = require('express');
const router = express.Router();
const messageController = require('../controllers/messageController');

router.post('/', messageController.postMessage);
router.post('/worker', messageController.postMessageWorker);
router.get('/', messageController.getMessages);

module.exports = router;
