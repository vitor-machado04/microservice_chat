-- Tabela para armazenar as mensagens
CREATE TABLE IF NOT EXISTS message (
    message_id INT AUTO_INCREMENT PRIMARY KEY,
    message VARCHAR(255) NOT NULL,
    user_id_send INT NOT NULL,
    user_id_receive INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_user_send (user_id_send),
    INDEX idx_user_receive (user_id_receive),
    FOREIGN KEY (user_id_send) REFERENCES user(id),
    FOREIGN KEY (user_id_receive) REFERENCES user(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; 