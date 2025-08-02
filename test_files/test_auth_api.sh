#!/bin/bash

echo "🚀 Testing Authentication API"
echo "================================"

BASE_URL="http://127.0.0.1:8000/"

echo ""
echo "1️⃣ Testing Login with Admin user:"
echo "POST $BASE_URL/auth/login"

RESPONSE=$(curl -s -X POST "$BASE_URL/auth/login" \
-H "Content-Type: application/json" \
-H "Accept: application/json" \
-d '{
    "email": "admin@domproduct.uz",
    "password": "admin123",
    "device_name": "test-device"
}')

echo "Response:"
echo "$RESPONSE" | jq '.' 2>/dev/null || echo "$RESPONSE"

# Extract token for next requests
TOKEN=$(echo "$RESPONSE" | jq -r '.data.token' 2>/dev/null)

echo ""
echo "2️⃣ Testing Get Current User:"
echo "GET $BASE_URL/auth/user"

if [ "$TOKEN" != "null" ] && [ ! -z "$TOKEN" ]; then
    echo "Using token: ${TOKEN:0:20}..."

    USER_RESPONSE=$(curl -s -X GET "$BASE_URL/auth/user" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer $TOKEN")

    echo "Response:"
    echo "$USER_RESPONSE" | jq '.' 2>/dev/null || echo "$USER_RESPONSE"
else
    echo "❌ No token received from login"
fi

echo ""
echo "3️⃣ Testing Register new user:"
echo "POST $BASE_URL/auth/register"

REGISTER_RESPONSE=$(curl -s -X POST "$BASE_URL/auth/register" \
-H "Content-Type: application/json" \
-H "Accept: application/json" \
-d '{
    "name": "Test User",
    "first_name": "Test",
    "last_name": "User",
    "email": "test@example.com",
    "password": "password123",
    "password_confirmation": "password123",
    "city": "Toshkent",
    "preferred_language": "uz"
}')

echo "Response:"
echo "$REGISTER_RESPONSE" | jq '.' 2>/dev/null || echo "$REGISTER_RESPONSE"

echo ""
echo "✅ Authentication API Test completed!"
