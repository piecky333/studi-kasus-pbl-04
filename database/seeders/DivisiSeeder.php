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

        // Ensure directory exists in storage/app/public/divisi
        $storagePath = storage_path('app/public/divisi');
        
        if (!File::exists($storagePath)) {
            File::makeDirectory($storagePath, 0755, true);
        }

        foreach ($divisiData as $index => $data) {
            // Download dummy image using Http client
            $imageUrl = 'https://ui-avatars.com/api/?name=' . urlencode($data['nama_divisi']) . '&background=random&size=500&color=fff';
            $imageName = 'divisi-' . ($index + 1) . '.png';
            
            try {
                $response = \Illuminate\Support\Facades\Http::get($imageUrl);
                
                if ($response->successful()) {
                    File::put($storagePath . '/' . $imageName, $response->body());
                    $data['foto_divisi'] = 'divisi/' . $imageName; 
                } else {
                    $data['foto_divisi'] = null;
                    $this->command->warn("Gagal mendownload gambar untuk " . $data['nama_divisi']);
                }
            } catch (\Exception $e) {
                $data['foto_divisi'] = null;
                $this->command->error("Error saat mendownload gambar: " . $e->getMessage());
            }

            divisi::create($data);
        }
    }
}
