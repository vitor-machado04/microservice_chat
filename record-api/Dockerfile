FROM python:3.11-slim

# Define o diretório de trabalho no container
WORKDIR /app

# Instala as dependências do sistema
RUN apt-get update && apt-get install -y \
    gcc \
    default-libmysqlclient-dev \
    pkg-config \
    && rm -rf /var/lib/apt/lists/*

# Copia os arquivos de requisitos primeiro para aproveitar o cache do Docker
COPY requirements.txt .

# Instala as dependências Python
RUN pip install --no-cache-dir -r requirements.txt
RUN pip install --no-cache-dir -r requirements.txt debugpy

# Copia o resto do código da aplicação
COPY . .

# Expõe a porta que a aplicação vai usar
EXPOSE 8000

# Comando para rodar a aplicação
CMD ["uvicorn", "src.main:app", "--host", "0.0.0.0", "--port", "8000", "--reload"] 