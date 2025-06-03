#!/bin/bash

echo "=== Construindo os containers ==="
docker-compose build

echo "=== Subindo os containers ==="
docker-compose up -d

wait_for_service() {
  local url=$1
  local name=$2
  local retries=10
  local wait=3

  echo "Aguardando $name ficar disponível em $url..."
  until curl -s "$url" >/dev/null; do
    ((retries--))
    if [ $retries -le 0 ]; then
      return 1
    fi
    sleep $wait
  done
  echo "$name está disponível!"
  return 0
}

wait_for_service "http://127.0.0.1:3000/message/health" "receive-send-api-node"
wait_for_service "http://127.0.0.1:8001/health" "record-api"
wait_for_service "http://127.0.0.1:8000/health" "auth-api-php"

echo ""
echo "=== Testando endpoints /health ==="

test_endpoint() {
  local name=$1
  local url=$2
  local expected_status=$3

  echo -n "Testando $name ($url)... "

  http_code=$(curl -m 15 -s -o response.tmp -w "%{http_code}" "$url")
  response=$(cat response.tmp)
  rm response.tmp

  echo "Resposta: $response"

  if [[ "$http_code" == "200" && "$response" == *"\"status\":\"$expected_status\""* ]]; then
    echo "OK"
  else
    echo "FALHOU (HTTP $http_code)"
  fi
}

test_endpoint "auth-api-php" "http://127.0.0.1:8000/health" "healthy"
test_endpoint "receive-send-api-node" "http://127.0.0.1:3000/message/health" "ok"
test_endpoint "record-api" "http://127.0.0.1:8001/health" "healthy"

echo ""
echo "=== STATUS DOS CONTAINERS ==="
docker-compose ps

echo ""
echo "Deploy finalizado."
