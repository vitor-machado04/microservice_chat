require('dotenv').config();
const express = require('express');
const messageRoutes = require('./routes/messageRoutes');

const app = express();
app.use(express.json());

app.use('/message', messageRoutes);

module.exports = app;
