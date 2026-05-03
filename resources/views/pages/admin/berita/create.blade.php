@extends('layouts.admin')

@section('title', 'Tambah Berita')

@section('content')
<div class="container-fluid px-4 mt-6">
    {{-- Header --}}
    <div class="flex justify-between items-center mb-6">
        <div>
            <h4 class="text-2xl font-bold text-gray-800">Tambah Berita</h4>
            <p class="text-sm text-gray-500 mt-1">Buat berita atau pengumuman baru untuk organisasi.</p>
        </div>
        <a href="{{ route('admin.berita.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
            Kembali
        </a>
    </div>

    {{-- Form Card --}}
    <div class="bg-white rounded-xl shadow-[10px_10px_15px_-3px_rgba(0,0,0,0.1)] border border-gray-100 overflow-hidden max-w-4xl mx-auto mb-10">
        <div class="p-8">
            <form action="{{ route('admin.berita.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="space-y-6">
                    {{-- Judul --}}
                    <div>
                        <label for="judul_berita" class="block text-sm font-semibold text-gray-700 mb-2">Judul Berita</label>
                        <input type="text" id="judul_berita" name="judul_berita"
                               class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 @error('judul_berita') border-red-500 focus:ring-red-500 @enderror"
                               placeholder="Masukkan judul berita yang menarik..." value="{{ old('judul_berita') }}" required>
                        @error('judul_berita')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Kategori & Gambar --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="kategori" class="block text-sm font-semibold text-gray-700 mb-2">Kategori</label>
                            <select id="kategori" name="kategori"
                                    class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white @error('kategori') border-red-500 @enderror" required>
                                <option value="">-- Pilih Kategori --</option>
                                <option value="kegiatan" {{ old('kategori') == 'kegiatan' ? 'selected' : '' }}>Kegiatan HIMA-TI</option>
                                <option value="prestasi" {{ old('kategori') == 'prestasi' ? 'selected' : '' }}>Prestasi Mahasiswa</option>
                            </select>
                            @error('kategori')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="gambar_berita" class="block text-sm font-semibold text-gray-700 mb-2">Gambar Utama (Opsional)</label>
                            <input type="file" id="gambar_berita" name="gambar_berita"
                                   class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 file:mr-4 file:py-1 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 @error('gambar_berita') border-red-500 @enderror">
                            <p class="mt-1 text-[10px] text-gray-400 italic">*Format: JPG, PNG (Max. 2MB)</p>
                            @error('gambar_berita')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Isi Berita --}}
                    <div>
                        <label for="isi_berita" class="block text-sm font-semibold text-gray-700 mb-2">Konten Berita</label>
                        <div class="rounded-lg overflow-hidden border border-gray-300">
                            <textarea id="isi_berita" name="isi_berita" rows="10">{{ old('isi_berita') }}</textarea>
                        </div>
                        @error('isi_berita')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Tombol Simpan --}}
                <div class="flex items-center justify-end pt-8 mt-8 border-t border-gray-100">
                    <button type="submit" class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg shadow-lg shadow-blue-200 transition-all duration-200 transform hover:-translate-y-1 flex items-center">
                        <i class="fas fa-paper-plane mr-2"></i> Publikasikan Berita 
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/35.3.2/classic/ckeditor.js"></script>
<script>
    ClassicEditor
        .create(document.querySelector('#isi_berita'), {
            toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote'],
        })
        .catch(error => {
            console.error(error);
        });
</script>
<style>
    .ck-editor__editable {
        min-height: 300px;
    }
</style>
@endsection
