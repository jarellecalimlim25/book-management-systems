# Quick Start Guide - 5 Minutes to Running Application

## Step 1: Verify Installation ✅
- Dependencies installed via `composer install` ✓
- All routes registered ✓
- Controllers created ✓
- Middleware registered ✓

## Step 2: Setup Database (Choose One Option)

### Option A: Use SQLite (Easiest - No Database Setup)
```bash
cd c:\book-management-systems

# Create SQLite database file
if (!(Test-Path "database/database.sqlite")) { 
    New-Item -Path "database/database.sqlite" -ItemType File 
}

# Update .env
# DB_CONNECTION=sqlite
# (Comment out or remove other DB_* settings)

# Run migrations
php artisan migrate

# Seed test data
php artisan db:seed
```

### Option B: Use MySQL
1. Create database:
```sql
CREATE DATABASE book_management CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

2. Update `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=book_management
DB_USERNAME=root
DB_PASSWORD=yourpassword
```

3. Run migrations:
```bash
php artisan migrate
php artisan db:seed
```

## Step 3: Start Application
```bash
php artisan serve
```

Visit: **http://localhost:8000**

## Step 4: Test Login
Use these credentials to test:

| Account | Email | Password |
|---------|-------|----------|
| Admin | admin@example.com | password |
| User 1 | john@example.com | password |
| User 2 | jane@example.com | password |

## Features to Test

### As Admin (admin@example.com)
- ✅ See all navigation items
- ✅ Manage Users (`/users`)
- ✅ Manage Courses (`/courses`)
- ✅ Manage Posts
- ✅ Manage Books

### As Regular User (john@example.com)
- ✅ See Dashboard
- ✅ View Books
- ✅ Create Posts
- ✅ Edit own posts only
- ❌ Cannot see Users menu
- ❌ Cannot see Courses menu
- ❌ Cannot access `/users` (403 error)

### Test Ownership Verification
1. Login as john@example.com
2. Create a new post
3. Logout and login as jane@example.com
4. Try to edit john's post - should get 403 error
5. Try to delete john's post - should get 403 error

## Troubleshooting

### Issue: "Failed to open stream: No such file or directory"
**Solution:** Run `composer install`

### Issue: "Class not found" error
**Solution:** 
```bash
php artisan config:clear
php artisan cache:clear
```

### Issue: Database connection error
**Solution:** Check `.env` database settings match your database

### Issue: Middleware not working / 403 errors don't appear
**Solution:**
```bash
php artisan route:clear
php artisan route:cache
```

### Issue: "The role column doesn't exist"
**Solution:**
```bash
php artisan migrate
php artisan db:seed
```

## File Structure Summary

**Controllers (app/Http/Controllers/)**
- `AuthController.php` - Login, Register, Logout
- `BookController.php` - Book CRUD
- `UserController.php` - User management (admin only)
- `PostController.php` - Post CRUD with ownership check
- `CourseController.php` - Course management (admin only)

**Middleware (app/Http/Middleware/)**
- `AdminOnly.php` - Checks if user is admin
- `CheckOwnership.php` - Verifies resource ownership

**Views (resources/views/)**
- `layouts/app.blade.php` - Main layout with navbar
- `auth/login.blade.php` - Login page
- `auth/register.blade.php` - Registration page
- `books/`, `posts/`, `users/`, `courses/` - CRUD views

**Routes (routes/web.php)**
- Public: Login, Register
- Protected: Books, Posts
- Admin Only: Users, Courses

## Key Implementation Details

### 1. Authentication
```php
// Check if authenticated
auth()->check()

// Get current user
auth()->user()

// Get current user's role
auth()->user()->role
```

### 2. Role-Based Views
```blade
@if (auth()->user()->role === 'admin')
    <!-- Admin only content -->
@endif
```

### 3. Ownership Check
```blade
@if (auth()->user()->id === $post->user_id || auth()->user()->role === 'admin')
    <!-- Can edit/delete -->
@endif
```

### 4. Middleware Protection
```php
// Routes requiring admin role
Route::middleware('admin.only')->group(function () {
    Route::resource('users', UserController::class);
});

// Routes requiring authentication
Route::middleware('auth')->group(function () {
    Route::resource('posts', PostController::class);
});
```

## Next Steps After Verification

1. ✅ All CRUD operations working
2. ✅ Authentication working
3. ✅ Middleware restricting access
4. ✅ Role-based UI working
5. 📝 **Optional:** Add more features
   - Email verification
   - Soft deletes
   - Activity logging
   - API routes

## Submission Checklist

- [ ] Application runs without errors
- [ ] Can login with test credentials
- [ ] Admin can access user management
- [ ] Regular users cannot access admin routes
- [ ] Can create/edit/delete own posts
- [ ] Cannot edit/delete others' posts
- [ ] Edit/Delete buttons hidden for non-owners
- [ ] Database populated with test data

## Support Files

- `ACTIVITY_README.md` - Complete documentation
- `app/` - Application code
- `database/` - Migrations and seeders
- `resources/views/` - Blade templates

---

**Ready to submit?** Go to: https://forms.gle/ot9sd73pb5SEfKMT7
