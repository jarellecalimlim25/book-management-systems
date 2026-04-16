<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Book;
use App\Models\Post;
use App\Models\Course;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create regular users
        $user1 = User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);

        $user2 = User::create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);

        // Create books
        Book::create([
            'title' => 'Laravel for Beginners',
            'author' => 'Taylor Otwell',
            'publication_year' => 2020,
            'isbn' => '978-1234567890',
            'pages' => 400,
        ]);

        Book::create([
            'title' => 'PHP Advanced Concepts',
            'author' => 'Mark Zandstra',
            'publication_year' => 2021,
            'isbn' => '978-0987654321',
            'pages' => 500,
        ]);

        // Create posts
        Post::create([
            'user_id' => $admin->id,
            'title' => 'Getting Started with Laravel',
            'content' => 'This is an introduction to the Laravel framework and how to use it effectively.',
        ]);

        Post::create([
            'user_id' => $user1->id,
            'title' => 'My First Post',
            'content' => 'This is my first post on the platform. I am excited to share my thoughts and experiences.',
        ]);

        Post::create([
            'user_id' => $user2->id,
            'title' => 'Web Development Tips',
            'content' => 'Here are some useful tips for web development that I have learned over the years.',
        ]);

        // Create courses
        $course1 = Course::create(['name' => 'PHP Fundamentals']);
        $course2 = Course::create(['name' => 'Laravel Masterclass']);
        $course3 = Course::create(['name' => 'Database Design']);

        // Enroll users in courses with timestamps
        $now = now();
        $admin->courses()->attach([
            $course1->id => ['created_at' => $now, 'updated_at' => $now],
            $course2->id => ['created_at' => $now, 'updated_at' => $now],
            $course3->id => ['created_at' => $now, 'updated_at' => $now],
        ]);
        $user1->courses()->attach([
            $course1->id => ['created_at' => $now, 'updated_at' => $now],
            $course2->id => ['created_at' => $now, 'updated_at' => $now],
        ]);
        $user2->courses()->attach([
            $course1->id => ['created_at' => $now, 'updated_at' => $now],
        ]);
    }
}

