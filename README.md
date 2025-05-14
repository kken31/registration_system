Project Documentation: Basic User Registration System
Overview: A simple web application enabling users to register, log in, and manage user profiles (create, read, update, delete).

1. Architecture:
•	Backend: RESTful API using Laravel Lumen 8
•	Frontend: PHP with Laravel 8
•	Authentication: Token-based (JWT) via Lumen API

2. Backend – Laravel Lumen 8 API
Responsibilities:
•	User CRUD
•	JWT Authentication
•	API for frontend
Technologies: Laravel Lumen 8, tymon/jwt-auth, Eloquent ORM
Setup:
•	.env: DB settings, JWT_SECRET
•	bootstrap/app.php: Enable Eloquent, Facades, JWT, CORS
•	config/auth.php: Configure JWT guard/provider
•	config/cors.php: Allow frontend domain
Components:
•	User Model: Implements JWT interfaces, $fillable, $hidden, JWT methods
•	Migration: Users table schema
•	AuthController: register(), login(), logout(), me(), refresh()
•	UserController: index(), store(), show(), update(), destroy()
API Endpoints (Base: http://localhost:8000/api):
•	POST /auth/register
•	POST /auth/login
•	POST /auth/logout
•	POST /auth/refresh
•	GET /auth/me
•	GET /users, POST /users, GET /users/{id}, PUT /users/{id}, DELETE /users/{id}

3. Frontend – Laravel 8 Application
Responsibilities:
•	UI for auth & user management
•	Interact with API
•	Store token in session
Technologies: PHP 7.3+, Laravel 8, Blade, Laravel HTTP Client, Sessions
Setup: .env: APP_URL, API_BASE_URL
Components:
•	FrontendAuthController: Shows forms, handles login/register via API, stores token, manages logout
•	FrontendUserController: Uses session token, makes CRUD API calls, returns views
•	Middleware: Checks for session token, redirects if missing
Views: Auth (login/register), Users (list/create/edit), Layout
Routes: Auth routes (login/register), User CRUD (protected)
Workflow:
1.	User accesses frontend
2.	Registers or logs in
3.	Token stored in session
4.	CRUD actions include token
5.	API validates, returns data
   
4. Troubleshooting
•	CORS Errors: Verify config/cors.php allows frontend origin
•	Token Issues: Ensure token is properly stored/sent
•	Illegal Offset Type: Likely config/auth.php issue (non-string defaults.guard, invalid guard/provider)
Solution: Trace errors, use dd(), logs, inspect config files

