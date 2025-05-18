from sqlalchemy import Column, Integer, String, DateTime
from sqlalchemy.sql import func
from ..config.database import Base

class Message(Base):
    __tablename__ = "message"

    message_id = Column(Integer, primary_key=True, index=True)
    message = Column(String(255), nullable=False)
    user_id_send = Column(Integer, nullable=False)
    user_id_receive = Column(Integer, nullable=False)
    created_at = Column(DateTime(timezone=True), server_default=func.now())

    def __repr__(self):
        return f"<Message(message_id={self.message_id}, from={self.user_id_send}, to={self.user_id_receive})>" 