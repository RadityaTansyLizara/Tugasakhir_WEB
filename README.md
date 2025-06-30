| Variable | Isi |
| -------- | --- |
| Nama | RADITYA TANSY LIZARA  |
| NIM | 312310454 |
| Kelas | TI.23.A5 |
| Mata Kuliah | Pemrograman Web 2 (Tugas akhir UAS) |

## 1. Membuat Tabel `kategori`
```php
CREATE TABLE kategori (
    id_kategori INT(11) AUTO_INCREMENT,
    nama_kategori VARCHAR(100) NOT NULL,
    slug_kategori VARCHAR(100),
    PRIMARY KEY (id_kategori)
);
```
![table_kategori](https://github.com/user-attachments/assets/da0e4b87-039b-4d66-a917-3aeb66f7f576)

## 2. Mengubah Tabel `artikel`
```php
ALTER TABLE artikel
ADD COLUMN id_kategori INT(11),
ADD CONSTRAINT fk_kategori_artikel
FOREIGN KEY (id_kategori) REFERENCES kategori(id_kategori);
```

## 3. Membuat Model `KategoriModel.php`
```php
<?php

namespace App\Models;

use CodeIgniter\Model;

class KategoriModel extends Model
{
    protected $table = 'kategori';
    protected $primaryKey = 'id_kategori';
    protected $useAutoIncrement = true;
    protected $allowedFields = ['nama_kategori', 'slug_kategori'];
}
```

## 4. Memodifikasi Model `ArtikelModel.php`
Silakan tambahkan sebuah metode yang berfungsi untuk mengambil data artikel berikut dengan nama kategori yang terkait.
```php
<?php

namespace App\Models;

use CodeIgniter\Model;

class KategoriModel extends Model
{
    protected $table = 'kategori';
    protected $primaryKey = 'id_kategori';
    protected $allowedFields = ['nama_kategori'];

     public function getAllKategori()
    {
        return $this->findAll();
    }
}
```

## 5. Memodifikasi Controller `Artikel.php`
Gunakan metode `getArtikelDenganKategori()` pada metode `index` dan `admin_index`. Sertakan pula filter berdasarkan `kategori_id`.

Berikut adalah contoh pengambilan data artikel menggunakan operasi join:
```php
<?php

namespace App\Controllers;

use App\Models\ArtikelModel;
use App\Models\KategoriModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Artikel extends BaseController
{
    // Menampilkan daftar artikel
    public function index()
    {
        // Membuat objek model
        $artikelModel = new ArtikelModel();
        $kategoriModel = new KategoriModel();

        // Mengambil data artikel beserta kategori
        $artikel = $artikelModel->getArtikelDenganKategori();

        // Mengambil daftar kategori
        $kategori = $kategoriModel->getAllKategori();

        // Mengirimkan data ke view
        return view('artikel/index', [
            'artikel' => $artikel,
            'kategori' => $kategori
        ]);
    }

    // Menampilkan artikel berdasarkan slug
  public function view($slug)
{
    $model = new ArtikelModel();
    // Gabungkan artikel dengan kategori
    $artikel = $model->select('artikel.*, kategori.nama_kategori')
                     ->join('kategori', 'kategori.id_kategori = artikel.id_kategori', 'left')
                     ->where(['slug' => $slug])
                     ->first();

    if (!$artikel) {
        throw PageNotFoundException::forPageNotFound();
    }

    $title = $artikel['judul'];
    return view('artikel/detail', compact('artikel', 'title'));
}


    // Menampilkan artikel admin dan pencarian
  public function admin_index()
{
    $title = 'Daftar Artikel';
    $q = $this->request->getVar('q') ?? '';  // Ambil pencarian dari URL
    $kategoriId = $this->request->getVar('kategori'); // Ambil kategori dari URL

    // Model Artikel
    $model = new ArtikelModel();

    // Menggabungkan artikel dengan kategori
    $model->select('artikel.*, kategori.nama_kategori')
          ->join('kategori', 'kategori.id_kategori = artikel.id_kategori', 'left');

    // Filter berdasarkan pencarian jika ada
    if (!empty($q)) {
        $model->like('judul', $q)->orLike('isi', $q);
    }

    // Filter berdasarkan kategori jika ada
    if (!empty($kategoriId)) {
        $model->where('artikel.id_kategori', $kategoriId);
    }

    // Ambil artikel dan paginate
    $data = [
        'title'   => $title,
        'q'       => $q,
        'selectedKategori' => $kategoriId,
        'kategori' => (new KategoriModel())->findAll(), // Ambil kategori
        'artikel' => $model->paginate(5),  // Artikel per halaman
        'pager'   => $model->pager,  // Pagination
    ];

    return view('artikel/admin_index', $data);
}



   public function search()
{
    $q = $this->request->getGet('q');  // Ambil query pencarian dari URL
    $kategoriId = $this->request->getGet('kategori');  // Ambil kategori yang dipilih

    // Ambil model artikel
    $artikelModel = new \App\Models\ArtikelModel();
    $query = $artikelModel->where('1=1');  // Mulai query dengan kondisi true

    // Filter berdasarkan kata kunci jika ada
    if ($q) {
        $query = $query->like('judul', $q)->orLike('isi', $q);
    }

    // Filter berdasarkan kategori jika ada
    if ($kategoriId) {
        $query = $query->where('id_kategori', $kategoriId);
    }

    // Ambil artikel yang sesuai dengan filter
    $artikel = $query->findAll();

    // Ambil data kategori untuk dropdown
    $kategoriModel = new \App\Models\KategoriModel();
    $kategori = $kategoriModel->findAll();  // Pastikan kategori diambil dengan benar

    // Kirim data ke view
    return view('artikel/admin_index', [
        'q' => $q,
        'selectedKategori' => $kategoriId,
        'kategori' => $kategori,  // Kirimkan kategori ke view
        'artikel' => $artikel,  // Kirimkan artikel yang ditemukan
    ]);
}




    // Menambahkan artikel baru
    public function add()
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'judul' => 'required',
            'id_kategori' => 'required'
        ]);

        $isDataValid = $validation->withRequest($this->request)->run();

        if ($isDataValid) {
            $file = $this->request->getFile('gambar');
            $namaGambar = '';

            if ($file && $file->isValid()) {
                // Pindahkan file gambar ke folder gambar
                $file->move(ROOTPATH . 'public/gambar');
                $namaGambar = $file->getName();
            }

            $artikel = new ArtikelModel();
            // Pastikan slug unik
            $slug = url_title($this->request->getPost('judul'), '-', true);

            // Insert data artikel
            $artikel->insert([
                'judul'       => $this->request->getPost('judul'),
                'isi'         => $this->request->getPost('isi'),
                'id_kategori' => $this->request->getPost('id_kategori'),
                'slug'        => $slug,
                'gambar'      => $namaGambar,
            ]);
            return redirect()->to('/admin/artikel');
        }

        $title = "Tambah Artikel";
        $kategoriModel = new KategoriModel();
        $kategori = $kategoriModel->findAll();
        return view('artikel/form_add', compact('title', 'kategori'));
    }

    // Mengedit artikel
    public function edit($id)
    {
        $artikel = new ArtikelModel();
        $validation = \Config\Services::validation();
        $validation->setRules([
            'judul' => 'required',
            'id_kategori' => 'required'
        ]);

        $isDataValid = $validation->withRequest($this->request)->run();

        if ($isDataValid) {
            $dataUpdate = [
                'judul'       => $this->request->getPost('judul'),
                'isi'         => $this->request->getPost('isi'),
                'id_kategori' => $this->request->getPost('id_kategori'),
            ];

            $file = $this->request->getFile('gambar');
            if ($file && $file->isValid() && !$file->hasMoved()) {
                $file->move(ROOTPATH . 'public/gambar');
                $dataUpdate['gambar'] = $file->getName();
            }

            // Update artikel
            $artikel->update($id, $dataUpdate);
            return redirect()->to('/admin/artikel');
        }

        // Ambil data artikel untuk diedit
        $data = $artikel->where('id', $id)->first();
        $title = "Edit Artikel";
        $kategoriModel = new KategoriModel();
        $kategori = $kategoriModel->findAll();

        return view('artikel/form_edit', compact('title', 'data', 'kategori'));
    }

    // Menghapus artikel
    public function delete($id)
    {
        $artikel = new ArtikelModel();
        $artikel->delete($id);
        return redirect()->to('/admin/artikel');
    }
}
```

## 6. Memodifikasi View
### a. View artikel/index.php
```php
<?= $this->include('template/header'); ?>

<!-- Menampilkan Daftar Kategori -->
<div class="kategori-container">
  <h3>Kategori</h3>
  <ul>
    <?php if ($kategori): foreach ($kategori as $cat): ?>
    <li><a href="<?= base_url('/kategori/' . $cat['id_kategori']); ?>"><?= $cat['nama_kategori']; ?></a></li>
    <?php endforeach; else: ?>
    <li>No categories found</li>
    <?php endif; ?>
  </ul>
</div>

<!-- Artikel yang sudah ada -->
<div class="article-container">
  <?php if ($artikel): foreach ($artikel as $row): ?>
  <article class="entry">
    <div class="entry-meta">
      <span class="category"><?= $row['nama_kategori'] ?></span>
    </div>
    <h2 class="entry-title">
      <a href="<?= base_url('/artikel/' . $row['slug']); ?>"><?= $row['judul']; ?></a>
    </h2>
    <div class="entry-image">
      <img src="<?= base_url('/gambar/' . $row['gambar']); ?>" alt="<?= $row['judul']; ?>">
    </div>
    <div class="entry-excerpt">
      <p><?= substr($row['isi'], 0, 200); ?>...</p>
      <a href="<?= base_url('/artikel/' . $row['slug']); ?>" class="read-more">Read More</a>
    </div>
  </article>
  <?php endforeach; else: ?>
  <article class="entry">
    <h2 class="entry-title">No articles found.</h2>
  </article>
  <?php endif; ?>
</div>


<?= $this->include('template/footer'); ?>
```

### b. View artikel/admin_index.php
```php
<?= $this->include('template/admin_header'); ?>
<form method="get" class="form-search">
  <input type="text" name="q" value="<?= $q; ?>" placeholder="Cari artikel...">

  <select name="kategori" id="kategori">
    <option value="">Semua Kategori</option>
    <?php if (isset($kategori) && !empty($kategori)): ?>
    <?php foreach ($kategori as $cat): ?>
    <option value="<?= $cat['id_kategori']; ?>" <?= ($selectedKategori == $cat['id_kategori']) ? 'selected' : ''; ?>>
      <?= $cat['nama_kategori']; ?>
    </option>
    <?php endforeach; ?>
    <?php else: ?>
    <option disabled>Tidak ada kategori</option>
    <?php endif; ?>
  </select>

  <input type="submit" value="Cari" class="btn">
</form>

<table class="table">
  <thead>
    <tr>
      <th>ID</th>
      <th>Judul</th>
      <th>Kategori</th>
      <th>Status</th>
      <th>Aksi</th>
    </tr>
  </thead>
  <tbody>
    <?php if($artikel): foreach($artikel as $row): ?>
    <tr>
      <td><?= $row['id']; ?></td>
      <td>
        <b><?= esc($row['judul']); ?></b>
        <p><small><?= esc(substr($row['isi'], 0, 50)); ?>...</small></p>
      </td>
      <td><?= esc($row['nama_kategori'] ?? 'Tidak ada'); ?></td>
      <td><?= esc($row['status']); ?></td>
      <td>
        <a class="btn btn-primary" href="<?= base_url('/admin/artikel/edit/' . $row['id']); ?>">Ubah</a>
        <a class="btn btn-danger" onclick="return confirm('Yakin menghapus data?');"
          href="<?= base_url('/admin/artikel/delete/' . $row['id']); ?>">Hapus</a>
      </td>
    </tr>
    <?php endforeach; else: ?>
    <tr>
      <td colspan="5">Belum ada data.</td>
    </tr>
    <?php endif; ?>
  </tbody>
  <tfoot>
    <tr>
      <th>ID</th>
      <th>Judul</th>
      <th>Kategori</th>
      <th>Status</th>
      <th>Aksi</th>
    </tr>
  </tfoot>
</table>

<!-- Pagination Section -->
<div class="pagination">
  <?= $pager->links() ?>
</div>


<?= $this->include('template/admin_footer'); ?>
```

### c. View artikel/form_add.php dan form_edit.php
`form_add.php`

```php
<div class="container">
  <div class="form-card">
    <div class="form-header">
      <h1 class="form-title"><?= $title ?></h1>
      <p class="form-subtitle">
        <?= isset($artikel['id']) ? 'Edit artikel yang sudah ada' : 'Buat artikel baru untuk dipublikasikan' ?></p>
    </div>

    <form action="" method="post" enctype="multipart/form-data" class="form-grid">
      <div class="form-group">
        <label for="judul" class="form-label">Judul Artikel <span>(wajib)</span></label>
        <input type="text" id="judul" name="judul" class="form-control"
          placeholder="Contoh: Cara Membuat Website Modern"
          value="<?= isset($artikel['judul']) ? esc($artikel['judul']) : '' ?>" required>
      </div>

      <div class="form-group">
        <label for="isi" class="form-label">Isi Artikel <span>(wajib)</span></label>
        <textarea id="isi" name="isi" class="form-control" placeholder="Tulis konten artikel Anda di sini..."
          required><?= isset($artikel['isi']) ? esc($artikel['isi']) : '' ?></textarea>
      </div>

      <div class="form-group">
        <label for="id_kategori" class="form-label">Kategori</label>
        <select id="id_kategori" name="id_kategori" class="form-control">
          <option value="">-- Pilih Kategori --</option>
          <?php foreach($kategori as $k): ?>
          <option value="<?= $k['id_kategori']; ?>"
            <?= (isset($artikel['id_kategori']) && $artikel['id_kategori'] == $k['id_kategori']) ? 'selected' : ''; ?>>
            <?= esc($k['nama_kategori']); ?>
          </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="form-group">
        <label for="status" class="form-label">Status Publikasi</label>
        <select id="status" name="status" class="form-control">
          <option value="draft" <?= (isset($artikel['status']) && $artikel['status'] == 'draft') ? 'selected' : ''; ?>>
            Draft</option>
          <option value="publish"
            <?= (isset($artikel['status']) && $artikel['status'] == 'publish') ? 'selected' : ''; ?>>Publish</option>
        </select>
      </div>

      <div class="form-group">
        <label class="form-label">Gambar Utama</label>
        <div class="file-upload">
          <input type="file" id="gambar" name="gambar" class="file-upload-input" accept="image/*">
          <label for="gambar" class="file-upload-label">
            <div class="file-upload-icon">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
              </svg>
              <div class="file-upload-text">Unggah Gambar</div>
              <div class="file-upload-hint">PNG, JPG maksimal 2MB</div>
            </div>
          </label>
        </div>
      </div>

      <button type="submit" class="btn">
        <svg class="btn-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
          stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
          <polyline points="17 21 17 13 7 13 7 21"></polyline>
          <polyline points="7 3 7 8 15 8"></polyline>
        </svg>
        <?= isset($artikel['id']) ? 'Update Artikel' : 'Simpan Artikel' ?>
      </button>
    </form>
  </div>
</div>
```

`form_edit.php`

```php
<div class="container">
  <form action="" method="post" enctype="multipart/form-data">
    <div class="form-header">
      <h1><?= $title; ?></h1>
      <div class="action-buttons">
        <button type="button" class="btn btn-secondary" onclick="history.back()">
          <i class="fas fa-arrow-left"></i> Kembali
        </button>
        <button type="submit" class="btn btn-primary">
          <i class="fas fa-save"></i> Simpan Artikel
        </button>
      </div>
    </div>

    <div class="form-content">
      <div class="content-main">
        <div class="card">
          <div class="form-group">
            <label for="judul">Judul Artikel</label>
            <input type="text" name="judul" id="judul" value="<?= isset($data['judul']) ? $data['judul'] : ''; ?>"
              placeholder="Masukkan judul artikel" required>
          </div>
        </div>

        <div class="card">
          <div class="form-group">
            <label for="isi">Isi Artikel</label>
            <textarea name="isi" id="isi" placeholder="Tulis isi artikel..."
              required><?= isset($data['isi']) ? $data['isi'] : ''; ?></textarea>
          </div>
        </div>
      </div>

      <div class="content-sidebar">
        <div class="card">
          <div class="form-group">
            <label for="id_kategori">Kategori</label>
            <select name="id_kategori" id="id_kategori" required>
              <option value="">Pilih Kategori</option>
              <?php foreach ($kategori as $cat): ?>
              <option value="<?= $cat['id_kategori']; ?>"
                <?= (isset($data['id_kategori']) && $data['id_kategori'] == $cat['id_kategori']) ? 'selected' : ''; ?>>
                <?= $cat['nama_kategori']; ?>
              </option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <div class="card">
          <div class="form-group">
            <label>Gambar Artikel</label>
            <div class="file-input">
              <div class="file-upload">
                <input type="file" name="gambar" id="gambar" accept="image/*" onchange="previewImage(this)">
                <div class="upload-content">
                  <i class="fas fa-cloud-upload-alt"></i>
                  <p>Klik atau drop gambar di sini</p>
                </div>
              </div>

              <?php if (isset($data['gambar']) && $data['gambar']): ?>
              <div id="image-preview-container">
                <img src="<?= base_url('uploads/' . $data['gambar']); ?>" alt="Gambar Artikel" class="preview-image"
                  id="image-preview">
              </div>
              <?php else: ?>
              <div id="image-preview-container" style="display: none;">
                <img src="" alt="Preview" class="preview-image" id="image-preview">
              </div>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </form>
</div>
```

## 7. Pengujian Fitur
a. Screenshoot Tampilan Daftar Artikel Beserta Kategori:
![image](https://github.com/user-attachments/assets/2d89f3bb-0697-4fdb-96cb-dc42c05175d2)

b. Screenshot Tampilan Tambah Artikel Baru dengan Kategori:
![image](https://github.com/user-attachments/assets/020220cb-9db7-4392-b44f-2a5caecf9e10)

c. Screenshot Tampilan Edit Artikel dan Ubah Kategori:
![image](https://github.com/user-attachments/assets/3865dd9a-df51-4ab4-aab4-5328e6bba8c3)

d. Screenshot Tampilan Setelah Edit Kategori:
![image](https://github.com/user-attachments/assets/8a12d3cd-b4e8-4c25-9fe0-0f3e1f9ab906)

e. Screenshot Tampilan Hapus Artikel:
![image](https://github.com/user-attachments/assets/62023cd6-6b1a-4107-8d5d-3edb17609f3e)

f. Screenshot Tampilan Setelah Hapus Artikel:
![image](https://github.com/user-attachments/assets/6a46f4d1-0599-4f0e-bfa8-733bc1f07051)


## Panduan Awal Menjalankan Web Menggunakan Laragon
### 1. Menyalakan Laragon
Buka aplikasi Laragon
Pastikan Apache dan MySQL sudah berjalan

### 2. Menempatkan Folder Proyek
Salin folder project CodeIgniter 4 ke direktori:






