<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Book;
use App\Models\BookCopy;
use App\Models\BookIssue;
use App\Models\Category;
use App\Models\Fine;
use App\Models\Member;
use App\Models\Publisher;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        /*
         * Demo user accounts.
         */
        $admin = User::updateOrCreate(
            ['email' => 'admin@library.test'],
            [
                'name' => 'Library Administrator',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        $librarian = User::updateOrCreate(
            ['email' => 'librarian@library.test'],
            [
                'name' => 'Library Librarian',
                'password' => Hash::make('password'),
                'role' => 'librarian',
            ]
        );

        $memberUser = User::updateOrCreate(
            ['email' => 'member@library.test'],
            [
                'name' => 'Demo Library Member',
                'password' => Hash::make('password'),
                'role' => 'member',
            ]
        );

        /*
         * Demo member profile.
         */
        $member = Member::updateOrCreate(
            ['member_code' => 'MEM-2026-001'],
            [
                'user_id' => $memberUser->id,
                'phone' => '01700000000',
                'department' => 'Computer Science & Engineering',
                'address' => 'Dhaka, Bangladesh',
                'joined_at' => now()->subMonths(4)->toDateString(),
                'is_active' => true,
            ]
        );

        /*
         * Categories.
         */
        $computerScience = Category::updateOrCreate(
            ['name' => 'Computer Science'],
            ['description' => 'Programming, software engineering, algorithms, and computing books.']
        );

        $literature = Category::updateOrCreate(
            ['name' => 'Literature'],
            ['description' => 'Novels, poetry, stories, and literary works.']
        );

        $business = Category::updateOrCreate(
            ['name' => 'Business & Economics'],
            ['description' => 'Business, management, finance, and economics books.']
        );

        /*
         * Authors.
         */
        $author1 = Author::updateOrCreate(
            ['name' => 'Thomas H. Cormen'],
            ['biography' => 'Computer scientist and co-author of Introduction to Algorithms.']
        );

        $author2 = Author::updateOrCreate(
            ['name' => 'Robert C. Martin'],
            ['biography' => 'Software engineer, author, and advocate of clean code principles.']
        );

        $author3 = Author::updateOrCreate(
            ['name' => 'Humayun Ahmed'],
            ['biography' => 'Renowned Bangladeshi novelist, dramatist, and filmmaker.']
        );

        $author4 = Author::updateOrCreate(
            ['name' => 'Stephen R. Covey'],
            ['biography' => 'American educator, author, and businessman.']
        );

        /*
         * Publisher.
         */
        $publisher = Publisher::updateOrCreate(
            ['name' => 'Demo Academic Publishers'],
            [
                'email' => 'info@demoacademic.test',
                'phone' => '02-9000000',
                'address' => 'Dhaka, Bangladesh',
            ]
        );

        /*
         * Books.
         */
        $algorithmsBook = Book::updateOrCreate(
            ['isbn' => '978-0262033848'],
            [
                'title' => 'Introduction to Algorithms',
                'category_id' => $computerScience->id,
                'publisher_id' => $publisher->id,
                'edition' => '3rd Edition',
                'publication_year' => 2009,
                'description' => 'A comprehensive book on algorithms and data structures.',
            ]
        );

        $algorithmsBook->authors()->syncWithoutDetaching([
            $author1->id,
        ]);

        $cleanCodeBook = Book::updateOrCreate(
            ['isbn' => '978-0132350884'],
            [
                'title' => 'Clean Code',
                'category_id' => $computerScience->id,
                'publisher_id' => $publisher->id,
                'edition' => '1st Edition',
                'publication_year' => 2008,
                'description' => 'A handbook of agile software craftsmanship.',
            ]
        );

        $cleanCodeBook->authors()->syncWithoutDetaching([
            $author2->id,
        ]);

        $novelBook = Book::updateOrCreate(
            ['isbn' => '978-9840000001'],
            [
                'title' => 'Nondito Noroke',
                'category_id' => $literature->id,
                'publisher_id' => $publisher->id,
                'edition' => '1st Edition',
                'publication_year' => 1972,
                'description' => 'A well-known Bangladeshi novel by Humayun Ahmed.',
            ]
        );

        $novelBook->authors()->syncWithoutDetaching([
            $author3->id,
        ]);

        $habitsBook = Book::updateOrCreate(
            ['isbn' => '978-1982137274'],
            [
                'title' => 'The 7 Habits of Highly Effective People',
                'category_id' => $business->id,
                'publisher_id' => $publisher->id,
                'edition' => '30th Anniversary Edition',
                'publication_year' => 2020,
                'description' => 'A book about personal and professional effectiveness.',
            ]
        );

        $habitsBook->authors()->syncWithoutDetaching([
            $author4->id,
        ]);

        /*
         * Physical book copies.
         */
        $algorithmCopy1 = BookCopy::updateOrCreate(
            ['accession_number' => 'LIB-CS-001'],
            [
                'book_id' => $algorithmsBook->id,
                'shelf_location' => 'Shelf A-01',
                'status' => 'available',
            ]
        );

        $algorithmCopy2 = BookCopy::updateOrCreate(
            ['accession_number' => 'LIB-CS-002'],
            [
                'book_id' => $algorithmsBook->id,
                'shelf_location' => 'Shelf A-01',
                'status' => 'issued',
            ]
        );

        $cleanCodeCopy = BookCopy::updateOrCreate(
            ['accession_number' => 'LIB-CS-003'],
            [
                'book_id' => $cleanCodeBook->id,
                'shelf_location' => 'Shelf A-02',
                'status' => 'available',
            ]
        );

        $novelCopy = BookCopy::updateOrCreate(
            ['accession_number' => 'LIB-LIT-001'],
            [
                'book_id' => $novelBook->id,
                'shelf_location' => 'Shelf B-01',
                'status' => 'available',
            ]
        );

        $habitsCopy = BookCopy::updateOrCreate(
            ['accession_number' => 'LIB-BUS-001'],
            [
                'book_id' => $habitsBook->id,
                'shelf_location' => 'Shelf C-01',
                'status' => 'available',
            ]
        );

        /*
         * Active overdue issue record.
         */
        BookIssue::updateOrCreate(
            [
                'member_id' => $member->id,
                'book_copy_id' => $algorithmCopy2->id,
                'returned_at' => null,
            ],
            [
                'issued_by' => $librarian->id,
                'issued_at' => now()->subDays(20)->toDateString(),
                'due_at' => now()->subDays(6)->toDateString(),
                'status' => 'issued',
                'notes' => 'Demo overdue issue record.',
            ]
        );

        /*
         * Returned late book and fine record.
         */
        $returnedIssue = BookIssue::updateOrCreate(
            [
                'member_id' => $member->id,
                'book_copy_id' => $cleanCodeCopy->id,
                'returned_at' => now()->subDays(3)->toDateString(),
            ],
            [
                'issued_by' => $librarian->id,
                'issued_at' => now()->subDays(25)->toDateString(),
                'due_at' => now()->subDays(12)->toDateString(),
                'status' => 'returned',
                'notes' => 'Demo returned late book record.',
            ]
        );

        Fine::updateOrCreate(
            ['book_issue_id' => $returnedIssue->id],
            [
                'amount' => 45,
                'paid_amount' => 0,
                'status' => 'unpaid',
                'notes' => 'Demo fine: 9 overdue days × ৳5.',
            ]
        );
    }
}