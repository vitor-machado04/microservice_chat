const axios = require('axios');
const AUTH_API_URL = process.env.AUTH_API_URL;

exports.verifyAuth = async (token, userId) => {
  try {
    const resp = await axios.post(`${AUTH_API_URL}/verify-token`, { userId }, {
      headers: { Authorization: token }
    });
    return resp.data;
  } catch (error) {
    return { auth: false };
  }
};

exports.getAllUsers = async () => {
  try {
    const resp = await axios.get(`${AUTH_API_URL}/users`);
    return resp.data.users;
  } catch (error) {
    return [];
  }
};
