<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use App\Models\DataBorrow;
use App\Models\Category;
use App\Models\Book;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Create multiple Admin users
        $admin1 = User::factory()->create([
            'name' => 'Admin Example',
            'email' => 'admin@email.com',
            'role' => UserRole::Admin,
            'position' => 'System Administrator',
            'active' => true,
            'password' => Hash::make("123456789"),
        ]);

        $admin2 = User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'superadmin@email.com',
            'role' => UserRole::Admin,
            'position' => 'Super Administrator',
            'active' => true,
            'password' => Hash::make("123456789"),
        ]);

        // Create multiple Staff users
        $staff1 = User::factory()->create([
            'name' => 'Staff Example',
            'email' => 'staff@email.com',
            'role' => UserRole::Staff,
            'position' => 'Librarian',
            'active' => true,
            'password' => Hash::make("123456789"),
        ]);

        $staff2 = User::factory()->create([
            'name' => 'Assistant Librarian',
            'email' => 'assistant@email.com',
            'role' => UserRole::Staff,
            'position' => 'Assistant Librarian',
            'active' => true,
            'password' => Hash::make("123456789"),
        ]);

        // Create multiple Student users
        $student1 = User::factory()->create([
            'name' => 'Student Example',
            'email' => 'student@email.com',
            'role' => UserRole::Student,
            'position' => 'Grade 10',
            'active' => true,
            'password' => Hash::make("123456789"),
        ]);

        $student2 = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john.doe@email.com',
            'role' => UserRole::Student,
            'position' => 'Grade 11',
            'active' => true,
            'password' => Hash::make("123456789"),
        ]);

        $student3 = User::factory()->create([
            'name' => 'Jane Smith',
            'email' => 'jane.smith@email.com',
            'role' => UserRole::Student,
            'position' => 'Grade 12',
            'active' => true,
            'password' => Hash::make("123456789"),
        ]);

        // Create multiple Teacher users
        $teacher1 = User::factory()->create([
            'name' => 'Teacher Example',
            'email' => 'teacher@email.com',
            'role' => UserRole::Teacher,
            'position' => 'Mathematics Teacher',
            'active' => true,
            'password' => Hash::make("123456789"),
        ]);

        $teacher2 = User::factory()->create([
            'name' => 'Science Teacher',
            'email' => 'science.teacher@email.com',
            'role' => UserRole::Teacher,
            'position' => 'Science Teacher',
            'active' => true,
            'password' => Hash::make("123456789"),
        ]);

        // Create multiple Guest users
        $guest1 = User::factory()->create([
            'name' => 'Guest Example',
            'email' => 'guest@email.com',
            'role' => UserRole::Guest,
            'position' => 'Visitor',
            'active' => true,
            'password' => Hash::make("123456789"),
        ]);

        $guest2 = User::factory()->create([
            'name' => 'External Researcher',
            'email' => 'researcher@email.com',
            'role' => UserRole::Guest,
            'position' => 'Researcher',
            'active' => true,
            'password' => Hash::make("123456789"),
        ]);

        // Create DataBorrow records for users who can borrow books (Students, Teachers, Staff)
        DataBorrow::create([
            'user_id' => $student1->id,
            'name_borrower' => $student1->name,
            'type' => 'Student',
            'class' => 'Grade 10',
            'position' => $student1->position,
            'no_hp' => '081234567890',
            'gender' => 'Male',
        ]);

        DataBorrow::create([
            'user_id' => $student2->id,
            'name_borrower' => $student2->name,
            'type' => 'Student',
            'class' => 'Grade 11',
            'position' => $student2->position,
            'no_hp' => '081234567891',
            'gender' => 'Female',
        ]);

        DataBorrow::create([
            'user_id' => $student3->id,
            'name_borrower' => $student3->name,
            'type' => 'Student',
            'class' => 'Grade 12',
            'position' => $student3->position,
            'no_hp' => '081234567892',
            'gender' => 'Female',
        ]);

        DataBorrow::create([
            'user_id' => $teacher1->id,
            'name_borrower' => $teacher1->name,
            'type' => 'Teacher',
            'class' => 'Mathematics',
            'position' => $teacher1->position,
            'no_hp' => '081234567893',
            'gender' => 'Male',
        ]);

        DataBorrow::create([
            'user_id' => $teacher2->id,
            'name_borrower' => $teacher2->name,
            'type' => 'Teacher',
            'class' => 'Science',
            'position' => $teacher2->position,
            'no_hp' => '081234567894',
            'gender' => 'Female',
        ]);

        DataBorrow::create([
            'user_id' => $staff1->id,
            'name_borrower' => $staff1->name,
            'type' => 'Staff',
            'class' => 'Library',
            'position' => $staff1->position,
            'no_hp' => '081234567895',
            'gender' => 'Female',
        ]);

        DataBorrow::create([
            'user_id' => $staff2->id,
            'name_borrower' => $staff2->name,
            'type' => 'Staff',
            'class' => 'Library',
            'position' => $staff2->position,
            'no_hp' => '081234567896',
            'gender' => 'Male',
        ]);

        // Create Categories
        $categories = [
            [
                'category' => 'Fiction',
                'created_by' => $admin1->id,
                'updated_by' => $admin1->id,
            ],
            [
                'category' => 'Non-Fiction',
                'created_by' => $admin1->id,
                'updated_by' => $admin1->id,
            ],
            [
                'category' => 'Science',
                'created_by' => $admin1->id,
                'updated_by' => $admin1->id,
            ],
            [
                'category' => 'Technology',
                'created_by' => $admin1->id,
                'updated_by' => $admin1->id,
            ],
            [
                'category' => 'History',
                'created_by' => $admin1->id,
                'updated_by' => $admin1->id,
            ],
            [
                'category' => 'Mathematics',
                'created_by' => $admin1->id,
                'updated_by' => $admin1->id,
            ],
            [
                'category' => 'Literature',
                'created_by' => $admin1->id,
                'updated_by' => $admin1->id,
            ],
            [
                'category' => 'Reference',
                'created_by' => $admin1->id,
                'updated_by' => $admin1->id,
            ],
        ];

        foreach ($categories as $categoryData) {
            Category::create($categoryData);
        }

        // Get created categories for book seeding
        $fictionCategory = Category::where('category', 'Fiction')->first();
        $nonFictionCategory = Category::where('category', 'Non-Fiction')->first();
        $scienceCategory = Category::where('category', 'Science')->first();
        $technologyCategory = Category::where('category', 'Technology')->first();
        $historyCategory = Category::where('category', 'History')->first();
        $mathCategory = Category::where('category', 'Mathematics')->first();
        $literatureCategory = Category::where('category', 'Literature')->first();
        $referenceCategory = Category::where('category', 'Reference')->first();

        // Create Books
        $books = [
            [
                'title_book' => 'The Great Gatsby',
                'author' => 'F. Scott Fitzgerald',
                'publisher' => 'Scribner',
                'category' => $fictionCategory->category,
                'stock' => 5,
                'created_by' => $staff1->id,
                'updated_by' => $staff1->id,
            ],
            [
                'title_book' => 'To Kill a Mockingbird',
                'author' => 'Harper Lee',
                'publisher' => 'J.B. Lippincott & Co.',
                'category' => $fictionCategory->category,
                'stock' => 3,
                'created_by' => $staff1->id,
                'updated_by' => $staff1->id,
            ],
            [
                'title_book' => '1984',
                'author' => 'George Orwell',
                'publisher' => 'Secker & Warburg',
                'category' => $fictionCategory->category,
                'stock' => 4,
                'created_by' => $staff1->id,
                'updated_by' => $staff1->id,
            ],
            [
                'title_book' => 'Sapiens: A Brief History of Humankind',
                'author' => 'Yuval Noah Harari',
                'publisher' => 'Harper',
                'category' => $historyCategory->category,
                'stock' => 6,
                'created_by' => $staff1->id,
                'updated_by' => $staff1->id,
            ],
            [
                'title_book' => 'Educated',
                'author' => 'Tara Westover',
                'publisher' => 'Random House',
                'category' => $nonFictionCategory->category,
                'stock' => 4,
                'created_by' => $staff1->id,
                'updated_by' => $staff1->id,
            ],
            [
                'title_book' => 'The Code Book',
                'author' => 'Simon Singh',
                'publisher' => 'Anchor Books',
                'category' => $technologyCategory->category,
                'stock' => 3,
                'created_by' => $staff1->id,
                'updated_by' => $staff1->id,
            ],
            [
                'title_book' => 'A Brief History of Time',
                'author' => 'Stephen Hawking',
                'publisher' => 'Bantam Books',
                'category' => $scienceCategory->category,
                'stock' => 5,
                'created_by' => $staff1->id,
                'updated_by' => $staff1->id,
            ],
            [
                'title_book' => 'Calculus: Early Transcendentals',
                'author' => 'James Stewart',
                'publisher' => 'Cengage Learning',
                'category' => $mathCategory->category,
                'stock' => 2,
                'created_by' => $staff1->id,
                'updated_by' => $staff1->id,
            ],
            [
                'title_book' => 'The Elements of Style',
                'author' => 'William Strunk Jr. and E.B. White',
                'publisher' => 'Allyn & Bacon',
                'category' => $referenceCategory->category,
                'stock' => 7,
                'created_by' => $staff1->id,
                'updated_by' => $staff1->id,
            ],
            [
                'title_book' => 'Pride and Prejudice',
                'author' => 'Jane Austen',
                'publisher' => 'T. Egerton',
                'category' => $literatureCategory->category,
                'stock' => 4,
                'created_by' => $staff1->id,
                'updated_by' => $staff1->id,
            ],
            [
                'title_book' => 'Introduction to Algorithms',
                'author' => 'Thomas H. Cormen et al.',
                'publisher' => 'MIT Press',
                'category' => $technologyCategory->category,
                'stock' => 3,
                'created_by' => $staff1->id,
                'updated_by' => $staff1->id,
            ],
            [
                'title_book' => 'The Immortal Life of Henrietta Lacks',
                'author' => 'Rebecca Skloot',
                'publisher' => 'Crown Publishing',
                'category' => $scienceCategory->category,
                'stock' => 4,
                'created_by' => $staff1->id,
                'updated_by' => $staff1->id,
            ],
        ];

        foreach ($books as $bookData) {
            Book::create($bookData);
        }
    }
}
