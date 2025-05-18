from sqlalchemy import create_engine
from sqlalchemy.ext.declarative import declarative_base
from sqlalchemy.orm import sessionmaker
from os import getenv
from dotenv import load_dotenv

load_dotenv()

# Configuração do banco de dados
MYSQL_USER = getenv("MYSQL_USER", "root")
MYSQL_PASSWORD = getenv("MYSQL_PASSWORD", "root")
MYSQL_HOST = getenv("MYSQL_HOST", "localhost")
MYSQL_PORT = getenv("MYSQL_PORT", "3306")
MYSQL_DATABASE = getenv("MYSQL_DATABASE", "record_db")

# String de conexão do MySQL
SQLALCHEMY_DATABASE_URL = f"mysql://{MYSQL_USER}:{MYSQL_PASSWORD}@{MYSQL_HOST}:{MYSQL_PORT}/{MYSQL_DATABASE}"

# Criação do engine do SQLAlchemy
engine = create_engine(
    SQLALCHEMY_DATABASE_URL,
    pool_pre_ping=True  # Verifica a conexão antes de usar
)

# Sessão do banco de dados
SessionLocal = sessionmaker(autocommit=False, autoflush=False, bind=engine)

# Base para os modelos
Base = declarative_base()

# Função para obter a sessão do banco de dados
def get_db():
    db = SessionLocal()
    try:
        yield db
    finally:
        db.close() 