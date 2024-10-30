<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NotesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $notes = [];
        for ($i = 1; $i <= 100; $i++) {
            $note = [
                'title' => 'Title ' . $i,
                'description' => 'Description ' . $i,
                'user_id' => ($i % 15) + 1,
                'created_at' => now(),
            ];
            $notes[] = $note;
        }
        DB::table('notes')->insert($notes);
    }
}
