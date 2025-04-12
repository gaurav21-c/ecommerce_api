# E-Commerce API (Laravel + Docker)

## 🚀 Project Setup
This project is a Laravel-based E-commerce API running inside a Docker environment.

### 📦 Prerequisites
Make sure you have the following installed:
- [Docker](https://www.docker.com/get-started)
- [Docker Compose](https://docs.docker.com/compose/install/)
- [Git](https://git-scm.com/)

---
## 🛠 Installation & Setup

### 1️⃣ **Clone the Repository**
```sh
git clone https://github.com/gaurav21-c/ecommerce-api.git
cd ecommerce-api
```

### 2️⃣ **Create the `.env` File**
Copy the example `.env` file:
```sh
cp .env.example .env
```
Modify database credentials in `.env`:
```ini
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ecommerce_db
DB_USERNAME=Gaurav
DB_PASSWORD=Abc@1234
```

### 3️⃣ **Start Docker Containers**
```sh
docker-compose up -d
```
This will start:
- Laravel App (`app`)
- MySQL Database (`db`)

### 4️⃣ **Install Dependencies**
```sh
docker-compose exec app composer install
```

### 5️⃣ **Generate Application Key**
```sh
docker-compose exec app php artisan key:generate
```

### 6️⃣ **Run Migrations**
```sh
docker-compose exec app php artisan migrate
```
(Optional) Seed the database:
```sh
docker-compose exec app php artisan db:seed
```

---
## 📡 Running the API
After setup, your API will be available at:
👉 **http://localhost:8000**

If using Laravel's built-in server:
```sh
docker-compose exec app php artisan serve --host=0.0.0.0 --port=8000
```

---
## 🔍 Useful Docker Commands
| Command | Description |
|---------|-------------|
| `docker ps` | Check running containers |
| `docker-compose down` | Stop and remove containers |
| `docker-compose logs app` | View Laravel logs |
| `docker-compose exec db mysql -u root -p` | Access MySQL shell |


---
## 📜 API Documentation
Auto-generated API docs are available at:

👉 http://127.0.0.1:8000/docs

This route lists all endpoints, parameters, and example responses.

---
## 🙌 Contributing
Feel free to submit issues or pull requests. Happy coding! 🎉

