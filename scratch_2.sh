#!/bin/bash

# Test CreateOrderController with extraHot=false (default)
echo "Testing with extraHot=false:"
curl -X POST http://localhost:8080/api/orders \
  -H "Content-Type: application/json" \
  -d '{"extraHot": false}'

# Test CreateOrderController with extraHot=true
#echo "Testing with extraHot=true:"
#curl -X POST http://localhost:8080/api/orders \
#  -H "Content-Type: application/json" \
#  -d '{"extraHot": true}'
