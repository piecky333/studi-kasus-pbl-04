<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\admin\divisi;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\File;

class DivisiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key checks to allow truncation
        Schema::disableForeignKeyConstraints();
        divisi::truncate();
        Schema::enableForeignKeyConstraints();

        $divisiData = [
            [
                'nama_divisi' => 'Divisi 1',
                'isi_divisi' => 'Deskripsi lengkap mengenai kegiatan dan tanggung jawab Divisi 1.',
            ],
            [
                'nama_divisi' => 'Divisi 2',
                'isi_divisi' => 'Deskripsi lengkap mengenai kegiatan dan tanggung jawab Divisi 2.',
            ],
            [
                'nama_divisi' => 'Divisi 3',
                'isi_divisi' => 'Deskripsi lengkap mengenai kegiatan dan tanggung jawab Divisi 3.',
            ],
            [
                'nama_divisi' => 'Divisi 4',
                'isi_divisi' => 'Deskripsi lengkap mengenai kegiatan dan tanggung jawab Divisi 4.',
            ],
        ];

        // Ensure directory exists in public/storage/divisi
        // Note: Assumes php artisan storage:link has been run, or we write directly to public if needed.
        // Using public_path('storage/divisi') writes to the symlink destination or creates the folder if no symlink.
        $path = public_path('storage/divisi');
        
        if (!File::exists($path)) {
            File::makeDirectory($path, 0755, true);
        }

        foreach ($divisiData as $index => $data) {
            // Download dummy image
            $imageUrl = 'https://ui-avatars.com/api/?name=' . urlencode($data['nama_divisi']) . '&background=random&size=500&color=fff';
            $imageName = 'divisi-' . ($index + 1) . '.png';
            $imageContent = @file_get_contents($imageUrl);

            if ($imageContent) {
                File::put($path . '/' . $imageName, $imageContent);
                // Store path relative to public/storage (which is usually mapped to storage/app/public)
                // If we use the 'public' disk, it would be 'divisi/' . $imageName
                $data['foto_divisi'] = 'divisi/' . $imageName; 
            } else {
                $data['foto_divisi'] = null;
            }

            divisi::create($data);
        }
    }
}
