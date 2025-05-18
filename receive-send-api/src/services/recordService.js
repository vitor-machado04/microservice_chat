const axios = require('axios');
const RECORD_API_URL = process.env.RECORD_API_URL;

exports.saveMessage = async (userIdSend, userIdReceive, message) => {
  try {
    await axios.post(`${RECORD_API_URL}/message`, {
      userIdSend,
      userIdReceive,
      message
    });
  } catch (error) {
    console.error('Error saving message:', error.message);
  }
};

exports.getMessages = async (channel) => {
  try {
    const resp = await axios.get(`${RECORD_API_URL}/message`, {
      params: { channel }
    });
    return resp.data.messages || [];
  } catch (error) {
    return [];
  }
};
