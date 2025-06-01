from sqlalchemy import create_engine
from sqlalchemy.ext.declarative import declarative_base
from sqlalchemy.orm import sessionmaker
from os import getenv
from dotenv import load_dotenv

load_dotenv()

# Configuração do banco de dados
MYSQL_USER = getenv("MYSQL_USER", "user")
MYSQL_PASSWORD = getenv("MYSQL_PASSWORD", "password")
MYSQL_HOST = getenv("MYSQL_HOST", "db")
MYSQL_PORT = getenv("MYSQL_PORT", "3306")
MYSQL_DATABASE = getenv("MYSQL_DATABASE", "chat_app")

# String de conexão do MySQL
SQLALCHEMY_DATABASE_URL = f"mysql://{MYSQL_USER}:{MYSQL_PASSWORD}@{MYSQL_HOST}:{MYSQL_PORT}/{MYSQL_DATABASE}"

# Criação do engine do SQLAlchemy
engine = create_engine(
    SQLALCHEMY_DATABASE_URL,
    pool_pre_ping=True  # Verifica a conexão antes de usar
)

SessionLocal = sessionmaker(autocommit=False, autoflush=False, bind=engine)

Base = declarative_base()

def get_db():
    db = SessionLocal()
    try:
        yield db
    finally:
        db.close() 

from src.models.user import User
from src.models.message import Message  
Base.metadata.create_all(bind=engine)        