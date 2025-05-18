import redis
import json
from os import getenv
from dotenv import load_dotenv

load_dotenv()

class RedisService:
    def __init__(self):
        self.redis_client = redis.Redis(
            host=getenv("REDIS_HOST", "redis"),
            port=int(getenv("REDIS_PORT", 6379)),
            db=0,
            decode_responses=True
        )

    def get_cache(self, key: str) -> dict:
        """Busca dados do cache"""
        data = self.redis_client.get(key)
        if data:
            return json.loads(data)
        return None

    def set_cache(self, key: str, value: dict, ttl_seconds: int = 60) -> None:
        """Salva dados no cache"""
        self.redis_client.setex(
            key,
            ttl_seconds,
            json.dumps(value)
        )

    def set_message_cache(self, user_id_send: int, user_id_receive: int, message: str) -> bool:
        """
        Cache para evitar duplicação de mensagens em um curto período de tempo
        Key: message:{user_id_send}:{user_id_receive}:{message}
        TTL: 5 segundos
        """
        cache_key = f"message:{user_id_send}:{user_id_receive}:{message}"
        
        # Verifica se a mensagem já existe no cache
        if self.redis_client.get(cache_key):
            return False
            
        # Adiciona a mensagem no cache com TTL de 5 segundos
        self.redis_client.setex(cache_key, 5, "1")
        return True 