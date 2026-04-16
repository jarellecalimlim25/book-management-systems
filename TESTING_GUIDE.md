# Complete Testing Guide

## 🧪 Testing Your Application

This guide walks you through testing every feature of your Laravel application.

---

## Part 1: Initial Setup & Server Start

### Step 1.1: Start the Application
```bash
cd c:\book-management-systems
php artisan serve
```

**Expected Output:**
```
INFO  Server running on [http://127.0.0.1:8000].
```

### Step 1.2: Access Application
Open browser and go to: **http://localhost:8000**

**Expected Result:** Should redirect to login page (since not authenticated)

---

## Part 2: Authentication Testing

### Test 2.1: Registration
1. Click "Register" link or go to `http://localhost:8000/register`
2. Fill form:
   - Name: Test User
   - Email: test@example.com
   - Password: password123
   - Confirm Password: password123
3. Click Register

**Expected Result:**
- ✅ Account created
- ✅ Automatically logged in
- ✅ Redirected to dashboard
- ✅ Username displayed in navbar

### Test 2.2: Login Flow
1. Click Logout button in navbar
2. Go to login page: `http://localhost:8000/login`
3. Enter credentials:
   - Email: admin@example.com
   - Password: password
4. Click Login

**Expected Result:**
- ✅ Successfully logged in
- ✅ Redirected to dashboard
- ✅ Admin name displays in navbar

### Test 2.3: Invalid Credentials
1. Go to login page
2. Enter wrong email or password
3. Click Login

**Expected Result:**
- ✅ Error message: "The provided credentials do not match our records"
- ✅ Stays on login page
- ✅ Form cleared

### Test 2.4: Logout
1. Click Logout button

**Expected Result:**
- ✅ Session ended
- ✅ Redirected to login page
- ✅ Cannot access protected pages without logging in

---

## Part 3: Role-Based Navigation Testing

### Test 3.1: Admin Navigation
1. Login as: admin@example.com / password
2. Look at navbar

**Expected Result - Admin sees:**
- ✅ Dashboard
- ✅ Books
- ✅ Posts
- ✅ **Users** ← (not visible to regular users)
- ✅ **Courses** ← (not visible to regular users)
- ✅ Admin name with "(admin)" badge

### Test 3.2: Regular User Navigation
1. Logout
2. Login as: john@example.com / password
3. Look at navbar

**Expected Result - Regular user sees:**
- ✅ Dashboard
- ✅ Books
- ✅ Posts
- ❌ NO Users link
- ❌ NO Courses link
- ✅ Username with "(user)" badge

---

## Part 4: Book Management Testing

### Test 4.1: View All Books
1. Click Books link (both admin and user can access)
2. View: `http://localhost:8000/books`

**Expected Result:**
- ✅ List of books displayed in table
- ✅ Columns: Title, Author, Publication Year, ISBN, Pages, Actions
- ✅ Pagination shows available books
- ✅ View/Edit/Delete buttons visible

### Test 4.2: Create Book
1. Click "Add New Book" button
2. Fill form:
   - Title: My First Book
   - Author: Test Author
   - Publication Year: 2024
   - ISBN: 123-456-7890 (unique value)
   - Pages: 250
3. Click "Create Book"

**Expected Result:**
- ✅ Book created successfully
- ✅ Success message shown
- ✅ Redirected to book list
- ✅ New book appears in table

### Test 4.3: View Book Details
1. Click View button next to any book
2. View: `http://localhost:8000/books/{id}`

**Expected Result:**
- ✅ Book details displayed
- ✅ Edit and Delete buttons available
- ✅ Back to Books link available

### Test 4.4: Edit Book
1. Click Edit button on book details
2. Change title to: "Updated Book Title"
3. Click "Update Book"

**Expected Result:**
- ✅ Changes saved
- ✅ Success message shown
- ✅ New title displayed in list

### Test 4.5: Delete Book
1. Click View on any book
2. Click Delete button
3. Confirm deletion in popup

**Expected Result:**
- ✅ Book deleted
- ✅ Success message shown
- ✅ Book no longer in list

---

## Part 5: Post Management Testing

### Test 5.1: Create Post (as User)
1. Login as: john@example.com / password
2. Click Posts link
3. Click "Create Post" button
4. Fill form:
   - Title: My First Post
   - Content: This is a test post about my experience
5. Click "Create Post"

**Expected Result:**
- ✅ Post created
- ✅ Associated with john@example.com
- ✅ Appears in john's post list
- ✅ Success message shown

### Test 5.2: View Posts (User Perspective)
1. Still logged as john@example.com
2. Click Posts link (shows only john's posts)

**Expected Result:**
- ✅ Only john's posts displayed
- ✅ Other users' posts NOT visible
- ✅ Edit/Delete buttons visible for john's posts

### Test 5.3: Edit Own Post
1. Click Edit button on own post
2. Change title
3. Click "Update Post"

**Expected Result:**
- ✅ Post updated
- ✅ Changes saved and visible

### Test 5.4: Cannot Edit Other's Post
1. Logout
2. Login as: jane@example.com / password
3. Go to: `http://localhost:8000/posts/{john's-post-id}/edit`
   (Replace with actual john's post ID)

**Expected Result:**
- ✅ Get 403 Unauthorized error
- ✅ Cannot view edit page
- ✅ Message: "Unauthorized. You can only access your own resources."

### Test 5.5: View Posts (Admin Perspective)
1. Logout
2. Login as: admin@example.com / password
3. Click Posts link

**Expected Result:**
- ✅ ALL posts visible (both john's and jane's)
- ✅ Admin can see everyone's posts
- ✅ Edit/Delete buttons available for all posts

### Test 5.6: Admin Can Edit Others' Posts
1. As admin, click Edit on john's post
2. Make changes
3. Update

**Expected Result:**
- ✅ Admin can edit anyone's post
- ✅ Changes saved

---

## Part 6: Access Control Testing (Most Important!)

### Test 6.1: Regular User Cannot Access Users Page
1. Login as: john@example.com / password
2. Try to manually visit: `http://localhost:8000/users`

**Expected Result:**
- ❌ 403 Forbidden error
- ❌ Cannot access user management
- ✅ Message: "Unauthorized. Admin access required."

### Test 6.2: Regular User Cannot Access Courses Page
1. Still logged as john@example.com
2. Try to visit: `http://localhost:8000/courses`

**Expected Result:**
- ❌ 403 Forbidden error
- ✅ Message: "Unauthorized. Admin access required."

### Test 6.3: Admin Can Access Users Page
1. Logout
2. Login as: admin@example.com / password
3. Click "Users" link in navbar
4. Visit: `http://localhost:8000/users`

**Expected Result:**
- ✅ User list displayed
- ✅ All 3 users visible
- ✅ Can see role (admin/user)
- ✅ Create User button available

### Test 6.4: Admin Can Access Courses Page
1. Click "Courses" link
2. Visit: `http://localhost:8000/courses`

**Expected Result:**
- ✅ Course list displayed
- ✅ All courses visible with enrollment counts
- ✅ Create Course button available

---

## Part 7: User Management Testing (Admin Only)

### Test 7.1: Create New User
1. As admin, go to Users page
2. Click "Create User" button
3. Fill form:
   - Name: New Admin User
   - Email: newadmin@example.com
   - Password: password
   - Confirm: password
   - Role: Admin
4. Click "Create User"

**Expected Result:**
- ✅ User created with admin role
- ✅ Success message shown
- ✅ New user appears in list

### Test 7.2: View User Details
1. Click View button next to admin user
2. View: `http://localhost:8000/users/{id}`

**Expected Result:**
- ✅ User details displayed
- ✅ Email shown
- ✅ Role shown (with color badge)
- ✅ Posts count
- ✅ Courses enrolled

### Test 7.3: Edit User Role
1. Click Edit button
2. Change role from admin to user
3. Click "Update User"

**Expected Result:**
- ✅ Role changed
- ✅ Success message shown
- ✅ Badge color updated in list

### Test 7.4: Delete User
1. Click View on any user
2. (Note: May need to add delete to show page, or go back to list)
3. Click Delete

**Expected Result:**
- ✅ User deleted
- ✅ Success message
- ✅ User removed from list

---

## Part 8: Course Management Testing (Admin Only)

### Test 8.1: Create Course
1. As admin, go to Courses page
2. Click "Create Course" button
3. Enter: PHP Advanced Topics
4. Click "Create Course"

**Expected Result:**
- ✅ Course created
- ✅ Added to course list
- ✅ Shows 0 enrolled students

### Test 8.2: View Course with Enrollments
1. Click View button on course
2. View: `http://localhost:8000/courses/{id}`

**Expected Result:**
- ✅ Course name displayed
- ✅ Enrollment section visible
- ✅ Current students listed (if any)
- ✅ Enrollment form available

### Test 8.3: Enroll User in Course
1. Scroll down on course page
2. In "Enroll New User" section:
   - Select: john@example.com
3. Click "Enroll User"

**Expected Result:**
- ✅ john enrolled in course
- ✅ User appears in enrolled list
- ✅ Success message shown

### Test 8.4: Remove User from Course
1. On enrolled user row
2. Click "Remove" button

**Expected Result:**
- ✅ User removed from course
- ✅ Success message
- ✅ User no longer in list

### Test 8.5: Edit Course
1. Click Edit button
2. Change name to: Advanced Web Development
3. Click "Update Course"

**Expected Result:**
- ✅ Name changed
- ✅ Returned to course list
- ✅ New name displayed

### Test 8.6: Delete Course
1. Go to Courses list
2. Click Delete button on any course

**Expected Result:**
- ✅ Course deleted
- ✅ Removed from list
- ✅ Success message

---

## Part 9: Middleware Verification

### Test 9.1: Auth Middleware
1. Logout (browser clear cookies or use incognito)
2. Try to access: `http://localhost:8000/books`

**Expected Result:**
- ✅ Redirected to login page
- ✅ Cannot access protected routes without login

### Test 9.2: Guest Middleware
1. Login as admin
2. Try to access: `http://localhost:8000/login`

**Expected Result:**
- ✅ Redirected to dashboard (already authenticated)
- ✅ Cannot access login while logged in

### Test 9.3: Admin Middleware
1. Login as: john@example.com
2. Open browser console (F12)
3. In address bar type: `http://localhost:8000/users`

**Expected Result:**
- ✅ 403 Forbidden error
- ✅ Message about admin access required

---

## Part 10: Data Relationships Testing

### Test 10.1: User with Posts
1. As admin, go to Users page
2. Click View on john@example.com
3. Scroll down to "Posts by John Doe"

**Expected Result:**
- ✅ All posts created by john shown
- ✅ Post titles displayed
- ✅ Links to view individual posts work

### Test 10.2: User with Courses
1. Still on john's user page
2. Scroll to "Enrolled Courses"

**Expected Result:**
- ✅ Courses john is enrolled in shown
- ✅ Enrollment date displayed
- ✅ Links to courses work

### Test 10.3: Eager Loading Verification
1. Open browser DevTools → Network tab
2. Go to `/posts` page

**Expected Result:**
- ✅ Posts load with user data
- ✅ No excessive database queries
- ✅ Authors displayed correctly without extra queries

---

## Part 11: Form Validation Testing

### Test 11.1: Book Form Validation
1. Go to create book page
2. Leave fields empty
3. Click "Create Book"

**Expected Result:**
- ✅ Error messages shown for required fields
- ✅ Form not submitted
- ✅ Data preserved in form

### Test 11.2: Duplicate ISBN
1. Try to create book with existing ISBN
2. Use ISBN from already created book

**Expected Result:**
- ✅ Error: "ISBN already exists"
- ✅ Form not submitted

### Test 11.3: Invalid Year
1. Try to create book with:
   - Publication Year: 9999 (future)
2. Or year: 999 (past)

**Expected Result:**
- ✅ Validation error shown
- ✅ Year must be between 1000 and current year

---

## Part 12: Error Handling Testing

### Test 12.1: 404 Not Found
1. Try to access non-existent post: `http://localhost:8000/posts/99999`

**Expected Result:**
- ✅ 404 error page

### Test 12.2: 403 Forbidden
1. As john, try: `http://localhost:8000/users`

**Expected Result:**
- ✅ 403 error: "Admin access required"

### Test 12.3: 405 Method Not Allowed
1. Try invalid method (e.g., try to POST to GET route)

**Expected Result:**
- ✅ 405 error

---

## Summary Checklist

After completing all tests, verify:

- [ ] **Authentication:** Login/Logout working
- [ ] **Authorization:** Admin-only routes blocked for users
- [ ] **CRUD:** All Create/Read/Update/Delete operations work
- [ ] **Relationships:** Posts show authors, users show posts/courses
- [ ] **Middleware:** Custom middleware blocking unauthorized access
- [ ] **UI:** Role-based buttons showing/hiding correctly
- [ ] **Validation:** Form validation preventing invalid data
- [ ] **Error Handling:** Appropriate error messages displayed
- [ ] **Performance:** Pages load with eager-loaded relationships
- [ ] **Data Integrity:** Related data stays consistent

---

**If all tests pass, your application is ready for submission!** ✅
