# 🎬 MovieWorld

A lightweight social platform where users can post their favorite movies, like or hate others', and see what the community is into.

Built with **Vanilla PHP**, **MySQL**, and **JavaScript** — fully Dockerized for easy setup and deployment.

---

## 🌟 Features

- 📝 **User signup & login** (with hashed passwords)
- 🎥 **Post new movies** (title & description)
- 👍 **Like / Hate other users' movies**
- 🚫 Users **can't vote on their own posts**
- 🔁 **Toggle vote or remove** with one click
- 📊 **Sort by likes, hates, or date** (asc/desc)
- 🎯 Real-time UI updates
- 🐳 **Dockerized environment**
- 🔒 API secured by session-based middleware

---

## 🛠️ Tech Stack

| Layer       | Stack                 |
|-------------|------------------------|
| Backend     | PHP 8.1 (Vanilla, OOP) |
| Frontend    | HTML, CSS, JS (Vanilla) |
| Database    | MySQL 8                |
| Auth        | PHP Sessions           |
| Dev Env     | Docker + Docker Compose |
| Web Server  | Apache (via PHP image) |

---

## ⚙️ Setup Instructions

### ✅ Requirements

- Docker & Docker Compose

---

### 📁 1. Clone the project

```bash
git clone https://github.com/your-username/movieworld.git
cd movieworld
```

---

### 📝 2. Set up environment variables

Copy the `.env.example` and adjust if needed:

```bash
cp .env.example .env
```

**.env.example**

```env
# MySQL Root (admin) access
DB_ROOT_PASS=root

# Application DB
DB_NAME=movieworld
DB_USER=appuser
DB_PASS=apppass
```

---

### 🐳 3. Start the application

```bash
docker-compose up --build
```

- App runs at: `http://localhost:8080`
- MySQL is exposed on: `localhost:3306` (default MySQL port)

---

## 🛢️ Database Schema (Auto-Created)

The schema is automatically initialized on the first container run using:

```text
/docker/mysql/init.sql
```

### 🔹 Tables

- `users` – stores user credentials (hashed passwords)
- `movies` – title, description, timestamp, and user ID
- `votes` – tracks each user's `like` or `hate` for each movie

All foreign keys cascade deletes properly.

---

## 🔐 Authentication

- Passwords are hashed using `password_hash()`
- Login/signup handled via modal popup
- Successful login sets `$_SESSION['user_id']` & `$_SESSION['username']`
- Middleware (`requireAuth()`) protects all private API routes

---

## 📦 Project Structure

```text
├── public/           # Frontend files (HTML, CSS, JS)
├── api/              # Backend API routing and middleware
├── config/           # Database configuration
├── src/              # Controllers, models, and DB logic
├── docker/           # Database initialization script

```

---

### ✅ API Response Status Codes

| Endpoint            | Method | Description                 | Expected Response                           |
|---------------------|--------|-----------------------------|---------------------------------------------|
| `/api/auth/signup`  | POST   | Register new user           | `201 Created`                               |
| `/api/auth/login`   | POST   | Authenticate user           | `200 OK` or `401 Unauthorized`              |
| `/api/auth/logout`  | POST   | Destroy session             | `200 OK`                                    |
| `/api/movies`       | GET    | Get movie list              | `200 OK` + JSON list of movies              |
| `/api/movies`       | POST   | Add a new movie             | `201 Created` or `401 Unauthorized`         |
| `/api/votes`        | POST   | Like, hate, or toggle vote  | `200 OK` or `401 Unauthorized`              |


### 🚀 Future Improvements

- 💅 **Frontend Enhancements**
  - Use [Vue.js](https://vuejs.org/) for a more dynamic, reactive user interface
  - Integrate [Tailwind CSS](https://tailwindcss.com/) to improve layout and styling with utility-first classes

- 🔐 **Authentication**
  - Replace custom session-based login with [Auth0](https://auth0.com/) for secure OAuth authentication (Google, GitHub, etc.)

- ✅ **Testing & CI**
  - Add unit tests using PHPUnit for backend logic
  - Set up [GitHub Actions](https://github.com/features/actions) for:
    - Automated testing on every push
    - Docker image builds
    - Static analysis (e.g. PHPStan)
    - Taint analysis (e.g. Psalm)
    - Linting (e.g. Codesniffer) 

- 🗑️ **Content Management**
  - Allow users to delete their own movie posts
  - Add the ability to leave comments on movies

- 📈 **Scalability & UX**
  - Implement pagination to handle large movie lists efficiently
  - Make the UI fully responsive for mobile users

- 🚀 **CI/CD Deployment**
  - Use GitHub Actions to automate build and deploy steps

## 👨‍💻 Author

Made by Aristodimos Avdeliodis

---

## 📜 License

MIT — open source, free to use and modify.
