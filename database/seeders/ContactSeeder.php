<?php

namespace Database\Seeders;

use App\Models\Contact;
use Illuminate\Database\Seeder;

class ContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ([
            [
                'name' => 'Pelanggan Contoh',
                'email' => 'pelanggan@example.com',
                'subject' => 'Pertanyaan jam operasional',
                'message' => 'Halo, boleh tahu jam operasional hari ini?',
            ],
            [
                'name' => 'Pemesan Contoh',
                'email' => 'pemesan@example.com',
                'subject' => 'Informasi pesanan',
                'message' => 'Apakah tersedia pilihan untuk pesanan takeaway?',
            ],
        ] as $contact) {
            Contact::updateOrCreate(
                ['email' => $contact['email'], 'subject' => $contact['subject']],
                [...$contact, 'is_read' => false],
            );
        }
    }
}
