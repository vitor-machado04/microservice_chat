from fastapi import FastAPI, Depends
from pydantic import BaseModel
from typing import List
from sqlalchemy.orm import Session
from sqlalchemy import text
from .config.database import get_db
from .services.message_service import MessageService
from .services.redis_service import RedisService

app = FastAPI(title="Record API")

class Message(BaseModel):
    message: str
    user_id_send: int
    user_id_receive: int

class MessageResponse(BaseModel):
    message_id: int
    message: str
    user_id_send: int
    user_id_receive: int
    created_at: str

    class Config:
        from_attributes = True

@app.get("/health")
async def health_check(db: Session = Depends(get_db)):
    try:
        # Testa conexão com o banco
        db.execute(text("SELECT 1"))
        
        # Testa conexão com Redis
        redis = RedisService()
        redis.set_cache("health_check", "ok")
        redis.get_cache("health_check")
        
        return {
            "status": "healthy",
            "database": "connected",
            "redis": "connected"
        }
    except Exception as e:
        return {
            "status": "unhealthy",
            "error": str(e)
        }

@app.post("/message", response_model=MessageResponse)
async def create_message(message: Message, db: Session = Depends(get_db)):
    service = MessageService(db)
    return service.create_message(
        message=message.message,
        user_id_send=message.user_id_send,
        user_id_receive=message.user_id_receive
    )

@app.get("/message", response_model=List[MessageResponse])
async def get_messages(user_id: int, db: Session = Depends(get_db)):
    service = MessageService(db)
    return service.get_messages_by_user(user_id) 