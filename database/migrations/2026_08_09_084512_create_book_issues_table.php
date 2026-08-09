<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('book_issues', function (Blueprint $table) {
            $table->id();

            $table->foreignId('member_id')
                ->constrained()
                ->restrictOnDelete();

            $table->foreignId('book_copy_id')
                ->constrained()
                ->restrictOnDelete();

            $table->foreignId('issued_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->date('issued_at');
            $table->date('due_at');
            $table->date('returned_at')->nullable();

            $table->enum('status', [
                'issued',
                'returned',
                'overdue',
            ])->default('issued');

            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('book_issues');
    }
};