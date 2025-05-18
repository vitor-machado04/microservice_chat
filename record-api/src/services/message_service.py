from sqlalchemy.orm import Session
from ..models.message import Message
from .redis_service import RedisService

class MessageService:
    def __init__(self, db: Session):
        self.db = db
        self.redis = RedisService()

    def create_message(self, message: str, user_id_send: int, user_id_receive: int) -> Message:
        # Cria a mensagem
        db_message = Message(
            message=message,
            user_id_send=user_id_send,
            user_id_receive=user_id_receive
        )
        self.db.add(db_message)
        self.db.commit()
        self.db.refresh(db_message)

        # Salva no cache
        cache_key = f"message:{db_message.message_id}"
        self.redis.set_cache(cache_key, {
            "message_id": db_message.message_id,
            "message": db_message.message,
            "user_id_send": db_message.user_id_send,
            "user_id_receive": db_message.user_id_receive,
            "created_at": str(db_message.created_at)
        })

        return db_message

    def get_messages_by_user(self, user_id: int) -> list[Message]:
        # Tenta buscar do cache
        cache_key = f"user_messages:{user_id}"
        cached_data = self.redis.get_cache(cache_key)
        
        if cached_data:
            return cached_data

        # Se não está em cache, busca do banco
        messages = self.db.query(Message).filter(
            (Message.user_id_send == user_id) | 
            (Message.user_id_receive == user_id)
        ).all()

        # Salva no cache
        messages_data = [{
            "message_id": msg.message_id,
            "message": msg.message,
            "user_id_send": msg.user_id_send,
            "user_id_receive": msg.user_id_receive,
            "created_at": str(msg.created_at)
        } for msg in messages]
        
        self.redis.set_cache(cache_key, messages_data)

        return messages 