<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use App\Models\Category;
use App\Models\Book;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $admin1 = User::factory()->create([
            'name' => 'Admin Example',
            'email' => 'admin@email.com',
            'role' => UserRole::Admin,
            'password' => Hash::make("123456789"),
        ]);

        $staff1 = User::factory()->create([
            'name' => 'Staff Example',
            'email' => 'staff@email.com',
            'role' => UserRole::Staff,
            'password' => Hash::make("123456789"),
        ]);

        $student1 = User::factory()->create([
            'name' => 'Student Example',
            'email' => 'student@email.com',
            'role' => UserRole::Student,
            'password' => Hash::make("123456789"),
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
