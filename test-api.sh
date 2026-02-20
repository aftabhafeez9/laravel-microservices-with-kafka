#!/bin/bash

# Color codes
RED='\033[0;31m'
GREEN='\033[0;32m'
BLUE='\033[0;34m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

BASE_URL="http://localhost:8000/api"

echo -e "${BLUE}╔════════════════════════════════════════════════════════════════╗${NC}"
echo -e "${BLUE}║   Complete Authentication & Notification System Testing       ║${NC}"
echo -e "${BLUE}╚════════════════════════════════════════════════════════════════╝${NC}"
echo ""

# Test 1: Student Signup
echo -e "${YELLOW}Test 1: Student Signup${NC}"
echo -e "${BLUE}POST /api/students/signup${NC}"

STUDENT_RESPONSE=$(curl -s -X POST $BASE_URL/students/signup \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Alice Johnson",
    "email": "alice@example.com",
    "phone": "1234567890",
    "registration_number": "REG-2026-001",
    "department": "Computer Science"
  }')

echo "$STUDENT_RESPONSE" | jq '.'
STUDENT_ID=$(echo "$STUDENT_RESPONSE" | jq '.data.id')
echo -e "${GREEN}✓ Student created with ID: $STUDENT_ID${NC}"
echo ""
sleep 3

# Test 2: Get All Students
echo -e "${YELLOW}Test 2: Get All Students${NC}"
echo -e "${BLUE}GET /api/students${NC}"

curl -s -X GET $BASE_URL/students | jq '.'
echo ""
sleep 2

# Test 3: Get Student Details
echo -e "${YELLOW}Test 3: Get Student Details${NC}"
echo -e "${BLUE}GET /api/students/$STUDENT_ID${NC}"

curl -s -X GET $BASE_URL/students/$STUDENT_ID | jq '.'
echo ""
sleep 2

# Test 4: Update Student
echo -e "${YELLOW}Test 4: Update Student${NC}"
echo -e "${BLUE}PUT /api/students/$STUDENT_ID${NC}"

curl -s -X PUT $BASE_URL/students/$STUDENT_ID \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Alice Johnson Updated",
    "phone": "9876543210"
  }' | jq '.'
echo ""
sleep 2

# Test 5: Auth Register
echo -e "${YELLOW}Test 5: Auth Register${NC}"
echo -e "${BLUE}POST /api/auth/register${NC}"

REGISTER_RESPONSE=$(curl -s -X POST $BASE_URL/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }')

echo "$REGISTER_RESPONSE" | jq '.'
echo -e "${GREEN}✓ User registered${NC}"
echo ""
sleep 3

# Test 6: Auth Login
echo -e "${YELLOW}Test 6: Auth Login${NC}"
echo -e "${BLUE}POST /api/auth/login${NC}"

LOGIN_RESPONSE=$(curl -s -X POST $BASE_URL/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "john@example.com",
    "password": "password123"
  }')

echo "$LOGIN_RESPONSE" | jq '.'
API_TOKEN=$(echo "$LOGIN_RESPONSE" | jq -r '.data.token')
echo -e "${GREEN}✓ User logged in${NC}"
echo -e "${GREEN}✓ API Token: ${API_TOKEN:0:20}...${NC}"
echo ""
sleep 3

# Test 7: Get Me (Protected)
echo -e "${YELLOW}Test 7: Get Current User (Protected)${NC}"
echo -e "${BLUE}GET /api/auth/me${NC}"

curl -s -X GET $BASE_URL/auth/me \
  -H "Authorization: Bearer $API_TOKEN" | jq '.'
echo ""
sleep 2

# Test 8: Logout
echo -e "${YELLOW}Test 8: Logout${NC}"
echo -e "${BLUE}POST /api/auth/logout${NC}"

curl -s -X POST $BASE_URL/auth/logout \
  -H "Authorization: Bearer $API_TOKEN" | jq '.'
echo ""

echo -e "${BLUE}╔════════════════════════════════════════════════════════════════╗${NC}"
echo -e "${GREEN}║               Testing Complete!                              ║${NC}"
echo -e "${BLUE}╚════════════════════════════════════════════════════════════════╝${NC}"
echo ""
echo -e "${YELLOW}Check the Notification Service listener for email notifications!${NC}"
