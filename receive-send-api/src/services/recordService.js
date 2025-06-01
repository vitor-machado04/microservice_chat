const axios = require('axios');
let RECORD_API_URL = process.env.RECORD_API_URL;

exports.saveMessage = async (userIdSend, userIdReceive, message) => {
  try {
    const response = await axios.post(`${RECORD_API_URL}/message`, {
      message,
      user_id_send: userIdSend,
      user_id_receive: userIdReceive
    });

    console.log('[RecordAPI] Mensagem salva com sucesso:');
    console.log('Status:', response.status);
    console.log('Dados:', response.data);
  } 
  catch (error) {
    console.error('[RecordAPI] Erro ao salvar mensagem:');

    if (error.response) {
      console.error('Status:', error.response.status);
      console.error('Dados:', error.response.data);
    } else {
      console.error('Erro:', error.message);
    }
  }
};

exports.getMessages = async (userId) => {
  try {
    const resp = await axios.get(`${RECORD_API_URL}/message`, {
      params: { user_id: userId }
    });
    return resp.data || [];
  } catch (error) {
    console.error('Erro ao buscar mensagens:', error.message);
    return [];
  }
};
