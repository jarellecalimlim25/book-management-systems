# Architecture & Feature Reference Guide

## Application Architecture

```
┌─────────────────────────────────────────────────────┐
│                   PRESENTATION LAYER                 │
│             (Blade Views & Templates)               │
├─────────────────────────────────────────────────────┤
│  Login │ Register │ Dashboard │ Books │ Posts │     │
│  Users │ Courses  │ Navigation │ Profile             │
├─────────────────────────────────────────────────────┤
│              ROUTING & MIDDLEWARE LAYER              │
├─────────────────────────────────────────────────────┤
│  routes/web.php                                      │
│  ├── Guest Routes (login, register)                 │
│  ├── Protected Routes (auth middleware)             │
│  └── Admin Routes (admin.only middleware)           │
├─────────────────────────────────────────────────────┤
│              CONTROLLER LAYER (Business Logic)       │
├─────────────────────────────────────────────────────┤
│  ├── AuthController (Authentication)                │
│  ├── BookController (CRUD)                          │
│  ├── UserController (Admin only)                    │
│  ├── PostController (User-specific + Ownership)     │
│  └── CourseController (Admin only + Enrollment)     │
├─────────────────────────────────────────────────────┤
│              MIDDLEWARE LAYER (Authorization)        │
├─────────────────────────────────────────────────────┤
│  ├── auth (Laravel built-in)                        │
│  ├── guest (Laravel built-in)                       │
│  ├── AdminOnly (Custom - Role check)                │
│  └── CheckOwnership (Custom - Resource ownership)   │
├─────────────────────────────────────────────────────┤
│              MODEL LAYER (Data & Relations)          │
├─────────────────────────────────────────────────────┤
│  ├── User (with role field)                         │
│  ├── Book                                            │
│  ├── Post (with user_id FK)                         │
│  ├── Course                                          │
│  └── Profile                                         │
├─────────────────────────────────────────────────────┤
│              DATABASE LAYER (Persistence)            │
├─────────────────────────────────────────────────────┤
│  ├── users (with role: enum(user|admin))            │
│  ├── books                                           │
│  ├── posts (with user_id foreign key)               │
│  ├── courses                                         │
│  ├── course_user (pivot table)                      │
│  ├── profiles                                        │
│  └── Migrations & Seeders                           │
└─────────────────────────────────────────────────────┘
```

---

## Request Flow Diagram

### 1. User Registration Flow
```
User clicks Register
         ↓
GET /register (AuthController@showRegisterForm)
         ↓
Shows registration form
         ↓
User fills and submits
         ↓
POST /register (AuthController@register)
         ↓
Validates input (name, email, password)
         ↓
Creates User with role='user'
         ↓
Auto-login and redirect to home
```

### 2. Admin Access Flow
```
User tries to access /users
         ↓
auth middleware (✓ checks if logged in)
         ↓
admin.only middleware (✓ checks if role===admin)
         ↓
UserController@index executed
         ↓
Show users management page
```

### 3. Post Creation & Edit Flow
```
User creates post (POST /posts)
         ↓
PostController@store
         ↓
Associates post with auth()->user()->id
         ↓
User tries to edit own post ✓
         ↓
PostController@edit (no check needed - they own it)

User tries to edit OTHERS post ✗
         ↓
PostController@edit
         ↓
Checks: if auth()->user()->id !== $post->user_id && auth()->user()->role !== 'admin'
         ↓
abort(403) - Unauthorized
```

---

## Access Control Matrix

| Route | Guest | User | Admin | Check |
|-------|-------|------|-------|-------|
| GET /login | ✅ | ❌ | ❌ | guest |
| GET /register | ✅ | ❌ | ❌ | guest |
| GET / (dashboard) | ❌ | ✅ | ✅ | auth |
| POST /logout | ❌ | ✅ | ✅ | auth |
| GET /books | ❌ | ✅ | ✅ | auth |
| POST /books | ❌ | ✅ | ✅ | auth |
| GET /posts | ❌ | ✅* | ✅** | auth |
| POST /posts | ❌ | ✅ | ✅ | auth |
| PUT /posts/{id} | ❌ | ✅*** | ✅ | auth + ownership |
| DELETE /posts/{id} | ❌ | ✅*** | ✅ | auth + ownership |
| GET /users | ❌ | ❌ | ✅ | auth + admin.only |
| POST /users | ❌ | ❌ | ✅ | auth + admin.only |
| PUT /users/{id} | ❌ | ❌ | ✅ | auth + admin.only |
| DELETE /users/{id} | ❌ | ❌ | ✅ | auth + admin.only |
| GET /courses | ❌ | ❌ | ✅ | auth + admin.only |
| POST /courses | ❌ | ❌ | ✅ | auth + admin.only |

*Users see only their own posts  
**Admins see all posts  
***Only if user owns the post

---

## Database Relationships Diagram

```
                    ┌──────────────────┐
                    │      users       │
                    ├──────────────────┤
                    │ id (PK)          │
                    │ name             │
                    │ email (UNIQUE)   │
                    │ password         │
                    │ role (enum)      │ ◄─── NEW FIELD
                    │ timestamps       │
                    └────────┬─────────┘
                             │
                 ┌───────────┼───────────┐
                 │           │           │
                 │ 1:1       │ 1:M       │ M:M
                 ▼           ▼           ▼
            ┌────────┐  ┌─────────┐  ┌──────────┐
            │profiles│  │  posts  │  │ courses  │
            └────────┘  ├─────────┤  └────┬─────┘
                        │ id      │       │
                        │ user_id │◄──────┤
                        │ title   │       │ M:M pivot
                        │ content │       │
                        └─────────┘  ┌─────────────┐
                                     │course_user  │
                                     └─────────────┘

Relationships:
- User (1) ─→ (M) Posts (hasMany)
- User (1) ─→ (1) Profile (hasOne)
- User (M) ←─→ (M) Courses (belongsToMany)
- Post ─→ User (belongsTo)
- Course ←─→ Users (belongsToMany)
```

---

## Eager Loading Examples

### Problem: N+1 Query Issue
```php
// BAD: Causes N+1 queries (1 for posts + N for each user)
$posts = Post::all();
foreach ($posts as $post) {
    echo $post->user->name; // Extra query each iteration!
}

// GOOD: Eager load user data (1 query for posts + 1 for users)
$posts = Post::with('user')->all();
foreach ($posts as $post) {
    echo $post->user->name; // No extra queries!
}

// BETTER: Eager load with pagination
$posts = Post::with('user')->paginate(10);
```

### In Controllers
```php
// PostController@index
$posts = Post::with('user')->paginate(10); // Eager load

// CourseController@show
$users = $course->users()->paginate(5); // Loaded via relationship
```

---

## Middleware Chain Execution

### Example: Admin Course Management
```
Request → /courses/{id}/edit
  │
  ├─ Route Middleware: 'auth' (check if logged in)
  │  ├─ If not logged in → Redirect to login
  │  └─ If logged in → Continue
  │
  ├─ Route Middleware: 'admin.only' (check if admin)
  │  ├─ If not admin → abort(403)
  │  └─ If admin → Continue
  │
  ├─ Controller: CourseController@edit
  │  ├─ Find course
  │  └─ Return edit view
  │
  └─ Response to user
```

---

## Code Examples

### 1. Using Middleware in Routes
```php
// Admin only route
Route::middleware('admin.only')->group(function () {
    Route::resource('users', UserController::class);
});

// Auth required route
Route::middleware('auth')->group(function () {
    Route::resource('posts', PostController::class);
});

// Guest only route
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm']);
});
```

### 2. Checking Role in Controllers
```php
public function destroy(Post $post)
{
    // Check ownership and admin status
    if (auth()->user()->id !== $post->user_id && 
        auth()->user()->role !== 'admin') {
        abort(403, 'Unauthorized');
    }
    
    $post->delete();
    return back()->with('success', 'Post deleted');
}
```

### 3. Conditional UI in Blade
```blade
{{-- Show admin menu --}}
@if (auth()->user()->role === 'admin')
    <a href="{{ route('users.index') }}">Manage Users</a>
@endif

{{-- Show edit button only to owner or admin --}}
@if (auth()->user()->id === $post->user_id || 
     auth()->user()->role === 'admin')
    <a href="{{ route('posts.edit', $post) }}">Edit</a>
@endif

{{-- Show role badge --}}
<span class="badge @if (auth()->user()->role === 'admin') bg-danger @else bg-primary @endif">
    {{ auth()->user()->role }}
</span>
```

### 4. Eager Loading in Controllers
```php
// Load posts with authors
$posts = Post::with('user')->paginate(10);

// Load courses with enrolled users
$courses = Course::with('users')->get();

// Load multiple relationships
$users = User::with(['posts', 'courses', 'profile'])->get();
```

---

## Feature Checklist

### ✅ Authentication & Authorization
- [x] User registration
- [x] User login
- [x] User logout
- [x] Password hashing (bcrypt)
- [x] Session-based authentication
- [x] Admin role support
- [x] User role support

### ✅ CRUD Operations
- [x] Books (Create, Read, Update, Delete)
- [x] Users (Create, Read, Update, Delete) - Admin only
- [x] Posts (Create, Read, Update, Delete)
- [x] Courses (Create, Read, Update, Delete) - Admin only

### ✅ Access Control
- [x] Auth middleware on protected routes
- [x] Guest middleware on public routes
- [x] Admin-only middleware
- [x] Ownership verification
- [x] 403 error responses for unauthorized access

### ✅ Eloquent Features
- [x] One-to-Many relationships (User → Posts)
- [x] One-to-One relationships (User → Profile)
- [x] Many-to-Many relationships (User ↔ Courses)
- [x] Belongs-To relationships (Post → User)
- [x] Eager loading (with() method)

### ✅ UI/UX
- [x] Responsive layouts
- [x] Navigation bar
- [x] Role-based menu
- [x] Form validation
- [x] Error messages
- [x] Success messages
- [x] Hidden action buttons for non-owners
- [x] User info in navbar

### ✅ Database
- [x] Migrations for all tables
- [x] Foreign key constraints
- [x] Cascading deletes
- [x] Role column in users table
- [x] Seeders for test data
- [x] Proper indexing

---

## Testing Tips

### Test Admin Access
```bash
# Login: admin@example.com / password
# Should see:
# - Users link in navbar
# - Courses link in navbar
# - Can access /users
# - Can access /courses
# - Can edit any post
```

### Test User Access
```bash
# Login: john@example.com / password
# Should see:
# - No Users link
# - No Courses link
# - Cannot access /users (403)
# - Cannot access /courses (403)
# - Can create posts
# - Can edit own posts
# - Cannot edit others' posts
```

### Test Ownership
```bash
# Login as john@example.com
# Create a post
# Copy edit link: /posts/{id}/edit
# Logout and login as jane@example.com
# Try to visit john's post edit link
# Should get 403 Unauthorized error
```

---

## Common Debugging Commands

```bash
# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Check routes
php artisan route:list

# Check database
php artisan migrate:status

# Reset database
php artisan migrate:reset
php artisan migrate
php artisan db:seed

# Test tinker shell
php artisan tinker
# Then in shell:
# $user = User::first();
# $user->role
```

---

## Deployment Checklist

- [ ] Database migration run on server
- [ ] .env file configured properly
- [ ] Application key generated
- [ ] Permissions set correctly (storage/, bootstrap/cache/)
- [ ] Database seeder run (optional)
- [ ] Cache cleared before going live
- [ ] HTTPS configured
- [ ] Email notifications working (if enabled)
- [ ] Error logging working
- [ ] Backups configured

---

## Performance Optimization Tips

1. **Always use eager loading:**
   ```php
   $posts = Post::with('user')->get();
   ```

2. **Use pagination for large datasets:**
   ```php
   $posts = Post::with('user')->paginate(10);
   ```

3. **Cache frequently accessed data:**
   ```php
   Cache::remember('courses', now()->addHour(), function () {
       return Course::all();
   });
   ```

4. **Use database indexing for search fields:**
   - email field is already unique
   - Consider adding indexes for foreign keys

---

**Status:** ✅ Complete and Ready for Deployment
