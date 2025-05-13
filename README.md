# NovaBlog

### Simple CRUD web app that allows user to write goofy ahh posts

### P.S.

> This pet-project is still develops so judge gently

1. **Clone the repository** and go to the project root.

```bash
git clone https://github.com/damirTAG/NovaBlog.git
cd NovaBlog
```

2. **Start Docker containers:**

```bash
docker-compose up -d --build
```

3. **Install PHP dependencies (inside the app container):**

```bash
docker exec -it app composer install
```

4. **Copy .env file and generate app key:**

```bash
cp .env.example .env
docker exec -it app php artisan key:generate
```

5. **Run database migrations:**

```bash
docker exec -it app php artisan migrate
```

6. **Install frontend dependencies and run Vite:**

```bash
npm install
npm run dev
```

#### Access the application:

**App: http://localhost**

**Public API Docs: http://localhost/api/documentation**
