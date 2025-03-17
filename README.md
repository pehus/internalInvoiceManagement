# 🛠 IIM Invoice System

This project is a Symfony application for managing invoices, running in **Docker**.  
It includes an API for CRUD operations on invoices and supports testing with **PHPUnit**.

---

## 📌 1. Requirements
Before you start, make sure you have installed:
- [Docker](https://www.docker.com/get-started) (including `docker-compose`)
- [PHP](https://www.php.net/) 8.1+
- [Composer](https://getcomposer.org/)
- [Symfony CLI](https://symfony.com/download)

---

## 🚀 2. Starting the Application

### **🔹 2.1 Clone the Repository**
```sh
git clone https://github.com/pehus/internalInvoiceManagement
```
## 🛠 3 Start Docker Containers
```sh
cd internalInvoiceManagement/docker/apache-php
docker-compose up -d
```

## 🌐 4. Running the Application
http://localhost/api/invoice


install packages:
```sh
composer i
```

request get all invoices:
```
GET: http://localhost/api/invoice
X-API-KEY: your-secret-api-key
```
request get one invoice:
```
GET: http://localhost/api/invoice/1
X-API-KEY: your-secret-api-key
```
request create invoice:
```
POST: http://localhost/api/invoice
X-API-KEY: your-secret-api-key

json body:
{
    "clientName": "Petr Husar",
    "statusId": 1,
    "items": [{
        "name": "Item 1",
        "quantity": 1,
        "unitPrice": 144.00
    }]
}
```
request payment add:
```
POST: http://localhost/api/invoice
X-API-KEY: your-secret-api-key

json body:
{
    "paymentMethodId": 1,
    "amount": 100.00
}
```

## ✅ 5. Running Tests
```sh
php bin/phpunit
```



