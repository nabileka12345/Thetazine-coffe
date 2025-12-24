#!/bin/bash

# Test Register - Should create user with role 'user'
echo "=== TEST 1: Register New User ==="
curl -X POST http://localhost:8000/api/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Test User New",
    "email": "testuser_'$(date +%s)'@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }' | jq '.'

echo -e "\n\n=== TEST 2: Login as Regular User ==="
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "testuser@example.com",
    "password": "password123"
  }' | jq '.'

echo -e "\n\n=== TEST 3: Login as Admin ==="
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "admin@thetazine.com",
    "password": "admin123"
  }' | jq '.'

echo -e "\n\n=== TEST 4: Get Profile (Verify Role) ==="
TOKEN=$(curl -s -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "admin@thetazine.com",
    "password": "admin123"
  }' | jq -r '.authorization.token')

curl -X GET http://localhost:8000/api/auth/profile \
  -H "Authorization: Bearer $TOKEN" | jq '.'
