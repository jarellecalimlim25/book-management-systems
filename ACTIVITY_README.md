# Book Management System - CRUD + Middleware Activity

A Laravel application demonstrating CRUD operations with Eloquent relationships, authentication, and role-based access control using middleware.

## Features

✅ **Authentication System**
- User registration and login
- Session-based authentication
- Logout functionality

✅ **Role-Based Access Control**
- Admin and User roles
- Middleware for admin-only routes
- Ownership verification for resources

✅ **CRUD Operations**
- Books management with validation
- Users management (admin only)
- Posts management with user-specific viewing
- Courses management with student enrollment

✅ **Eloquent Relationships**
- User → Posts (One-to-Many)
- User → Courses (Many-to-Many)
- Courses → Users (Many-to-Many with pivot table)

✅ **Middleware**
- `AdminOnly` - Restricts access to admin users only
- `CheckOwnership` - Verifies user owns the resource
- Laravel's built-in `auth` and `guest` middleware

## Setup Instructions

### 1. Clone & Install Dependencies

```bash
cd c:\book-management-systems
composer install
npm install
```

### 2. Environment Setup

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` and configure your database:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=book_management
DB_USERNAME=root
DB_PASSWORD=
```

### 3. Database Migrations

```bash
php artisan migrate
```

This will create all tables including the new `role` column in the users table.

### 4. Seed Test Data

```bash
php artisan db:seed
```

This will create:
- **Admin User:** admin@example.com / password
- **Regular Users:** 
  - john@example.com / password
  - jane@example.com / password
- **Sample Books:** 2 books
- **Sample Posts:** 3 posts
- **Sample Courses:** 3 courses with enrollments

### 5. Start Development Server

```bash
php artisan serve
```

Visit: `http://localhost:8000`

## Test Credentials

### Admin Account
- **Email:** admin@example.com
- **Password:** password
- **Access:** Full system access, user management, course management

### Regular User Account
- **Email:** john@example.com
- **Password:** password
- **Access:** Can create posts, view books, limited features

## Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── AuthController.php      # Authentication (login, register, logout)
│   │   ├── BookController.php      # CRUD for books
│   │   ├── UserController.php      # User management (admin only)
│   │   ├── PostController.php      # Post management
│   │   └── CourseController.php    # Course management (admin only)
│   └── Middleware/
│       ├── AdminOnly.php           # Verify admin role
│       └── CheckOwnership.php      # Verify resource ownership
├── Models/
│   ├── User.php                    # User with relationships
│   ├── Book.php                    # Book model
│   ├── Post.php                    # Post model
│   └── Course.php                  # Course model
└── ...
resources/
├── views/
│   ├── layouts/
│   │   ├── app.blade.php          # Main layout
│   │   └── navbar.blade.php       # Navigation bar
│   ├── auth/
│   │   ├── login.blade.php        # Login form
│   │   └── register.blade.php     # Registration form
│   ├── books/                      # Book CRUD views
│   ├── users/                      # User management views (admin only)
│   ├── posts/                      # Post CRUD views
│   ├── courses/                    # Course management views (admin only)
│   └── dashboard.blade.php         # Home page
routes/
└── web.php                         # All application routes
database/
├── migrations/                     # Database schemas
└── seeders/
    └── DatabaseSeeder.php          # Test data seeder
```

## Routes & Access Control

### Public Routes (Guest Only)
- `GET /login` - Login page
- `POST /login` - Submit login
- `GET /register` - Registration page
- `POST /register` - Submit registration

### Protected Routes (Authenticated Users)

#### All Users
- `GET /` - Dashboard
- `POST /logout` - Logout
- `GET /books` - List books
- `GET /books/create` - Create book form
- `POST /books` - Store book
- `GET /books/{id}` - View book
- `GET /books/{id}/edit` - Edit book form
- `PUT /books/{id}` - Update book
- `DELETE /books/{id}` - Delete book
- `GET /posts` - List user's posts
- `GET /posts/create` - Create post form
- `POST /posts` - Store post
- `GET /posts/{id}` - View post
- `GET /posts/{id}/edit` - Edit post form
- `PUT /posts/{id}` - Update post
- `DELETE /posts/{id}` - Delete post

#### Admin Only
- `GET /users` - List all users
- `GET /users/create` - Create user form
- `POST /users` - Store user
- `GET /users/{id}` - View user
- `GET /users/{id}/edit` - Edit user form
- `PUT /users/{id}` - Update user
- `DELETE /users/{id}` - Delete user
- `GET /courses` - List courses
- `GET /courses/create` - Create course form
- `POST /courses` - Store course
- `GET /courses/{id}` - View course with enrollments
- `GET /courses/{id}/edit` - Edit course form
- `PUT /courses/{id}` - Update course
- `DELETE /courses/{id}` - Delete course
- `POST /courses/{id}/enroll` - Enroll user in course
- `DELETE /courses/{id}/users/{user}` - Remove user from course

## Middleware Explanation

### `AdminOnly` Middleware
```php
// Routes requiring admin role
Route::middleware('admin.only')->group(function () {
    Route::resource('users', UserController::class);
    Route::resource('courses', CourseController::class);
});
```

### Built-in Middleware
- `auth` - Verifies user is authenticated
- `guest` - Only allows unauthenticated users
- `verified` - Checks if email is verified (if using verification)

## UI Features

✅ **Navigation Bar**
- Dynamic menu based on user role
- Shows current user name and role
- Logout button

✅ **Access Control**
- Edit/Delete buttons hidden for non-owners
- Admin-only pages restricted
- 403 errors for unauthorized access

✅ **Form Validation**
- Server-side validation
- Error message display
- Old input preservation

✅ **Eager Loading**
- Posts load with user information
- Courses load with enrollment counts
- Prevents N+1 queries

## Key Code Examples

### Checking User Role in Views
```blade
@if (auth()->user()->role === 'admin')
    <a href="{{ route('users.index') }}">Manage Users</a>
@endif
```

### Checking Ownership
```blade
@if (auth()->user()->id === $post->user_id || auth()->user()->role === 'admin')
    <a href="{{ route('posts.edit', $post) }}">Edit</a>
@endif
```

### Middleware in Routes
```php
Route::middleware('admin.only')->group(function () {
    Route::resource('users', UserController::class);
});
```

### Eager Loading in Controllers
```php
$posts = Post::with('user')->paginate(10);
```

## Common Issues & Solutions

### Issue: 403 Unauthorized Error
- Check if user is authenticated
- Verify user role (admin vs user)
- Confirm ownership of resource

### Issue: Middleware not working
- Ensure middleware is registered in `bootstrap/app.php`
- Check route grouping syntax
- Clear route cache: `php artisan route:clear`

### Issue: Posts not showing relationship
- Check if `Post->user()` relationship is defined
- Verify `user_id` foreign key exists in posts table
- Use eager loading: `Post::with('user')->get()`

## Activity Completion Checklist

- ✅ Build CRUD + UI for all entities
- ✅ Implement full CRUD (Create, Read, Update, Delete)
- ✅ Use Eloquent relationships
- ✅ Apply eager loading for related data
- ✅ Implement authentication middleware
- ✅ Create AdminOnly middleware
- ✅ Create ownership verification middleware
- ✅ Hide restricted actions in UI
- ✅ Role-based navigation
- ✅ Test data seeder

## Submission

Submit your work through the Google Form: https://forms.gle/ot9sd73pb5SEfKMT7

Include:
- GitHub repository link (or project folder)
- Screenshot of login page
- Screenshot of admin dashboard showing user management
- Screenshot of post creation with role-based edit/delete buttons
- Brief explanation of middleware implementation

## Additional Notes

- Database uses cascading deletes for referential integrity
- Passwords are hashed with bcrypt
- Session-based authentication (no JWT)
- Responsive design with simple CSS
- No external admin panels or packages

---

**Created:** April 16, 2026  
**Laravel Version:** 12.x  
**PHP Version:** 8.1+
