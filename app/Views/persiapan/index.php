<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="flex flex-col w-full gap-4 mt-4">
  
  <!-- Search & Filter Card -->
  <form action="<?= base_url('data-persiapan') ?>" method="GET" class="bg-surface-container p-4 rounded-xl shadow-sm flex flex-col gap-3">
    
    <!-- Row 1: Search Input & Action Buttons -->
    <div class="flex flex-col md:flex-row items-center justify-between gap-3">
      <div class="relative w-full md:flex-1">
        <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-on-surface-variant text-[16px]"></i>
        <input type="text" 
               name="keyword" 
               value="<?= esc($filters['keyword'] ?? '') ?>" 
               placeholder="Cari Nama Pegawai atau NIP..." 
               class="w-full bg-surface-container-highest text-on-surface text-body-md pl-10 pr-4 py-2.5 rounded-lg outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all placeholder:text-on-surface-variant/60"/>
      </div>
      
      <div class="flex items-center gap-2 w-full md:w-auto justify-end">
        <button type="submit" class="bg-primary text-on-primary flex items-center justify-center gap-1.5 px-4 py-2.5 rounded-lg font-label-md hover:bg-primary-container transition-colors shadow-sm whitespace-nowrap">
          <i class="fa-solid fa-filter text-[14px]"></i>
          Cari & Filter
        </button>
        
        <?php if (!empty($filters['unit']) || !empty($filters['progres']) || !empty($filters['tahun']) || !empty($filters['keyword'])): ?>
          <a href="<?= base_url('data-persiapan') ?>" class="bg-surface-container-high text-on-surface flex items-center justify-center gap-1.5 px-3 py-2.5 rounded-lg font-label-md hover:bg-surface-variant transition-colors whitespace-nowrap" title="Reset Filter">
            <i class="fa-solid fa-rotate-left text-[14px]"></i>
            Reset
          </a>
        <?php endif; ?>

        <?php if (can_create_persiapan()): ?>
        <button type="button" onclick="document.getElementById('modalTambah').classList.remove('hidden')" class="bg-secondary text-on-secondary flex items-center justify-center gap-1.5 px-4 py-2.5 rounded-lg font-label-md hover:bg-secondary-container transition-colors shadow-sm whitespace-nowrap">
          <i class="fa-solid fa-plus text-[16px]"></i>
          Tambah
        </button>
        <?php endif; ?>
      </div>
    </div>

    <!-- Row 2: Select Filters (Unit, Progres, Tahun) -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-2 border-t border-outline-variant/20">
      <!-- Filter Unit Kerja -->
      <div class="relative">
        <?php if (is_admin_unit()): ?>
          <div class="flex items-center bg-surface-container-highest text-on-surface text-body-md px-3.5 py-2 rounded-lg gap-2 border border-outline-variant/30" title="Unit Kerja Anda">
            <i class="fa-solid fa-building text-primary text-[14px]"></i>
            <span class="truncate font-medium"><?= esc(session('user_unit') ?? 'Unit Anda') ?></span>
          </div>
        <?php else: ?>
          <select name="unit" onchange="this.form.submit()" class="w-full appearance-none bg-surface-container-highest text-on-surface text-body-md pl-3.5 pr-8 py-2 rounded-lg outline-none focus:ring-2 focus:ring-primary transition-all">
            <option value="">Semua Unit Kerja</option>
            <?php if (!empty($unitList)): ?>
              <?php foreach ($unitList as $u): ?>
                <option value="<?= esc($u['UNIT_NAMA']) ?>" <?= (($filters['unit'] ?? '') === $u['UNIT_NAMA']) ? 'selected' : '' ?>>
                  <?= esc($u['UNIT_NAMA']) ?>
                </option>
              <?php endforeach; ?>
            <?php else: ?>
              <option value="Dinas Pendidikan" <?= (($filters['unit'] ?? '') === 'Dinas Pendidikan') ? 'selected' : '' ?>>Dinas Pendidikan</option>
              <option value="Dinas Kesehatan" <?= (($filters['unit'] ?? '') === 'Dinas Kesehatan') ? 'selected' : '' ?>>Dinas Kesehatan</option>
              <option value="Dinas Pekerjaan Umum" <?= (($filters['unit'] ?? '') === 'Dinas Pekerjaan Umum') ? 'selected' : '' ?>>Dinas Pekerjaan Umum</option>
              <option value="BKPSDM" <?= (($filters['unit'] ?? '') === 'BKPSDM') ? 'selected' : '' ?>>BKPSDM</option>
            <?php endif; ?>
          </select>
          <i class="fa-solid fa-caret-down absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant pointer-events-none text-[14px]"></i>
        <?php endif; ?>
      </div>

      <!-- Filter Progres -->
      <div class="relative">
        <select name="progres" onchange="this.form.submit()" class="w-full appearance-none bg-surface-container-highest text-on-surface text-body-md pl-3.5 pr-8 py-2 rounded-lg outline-none focus:ring-2 focus:ring-primary transition-all">
          <option value="">Semua Progres</option>
          <?php foreach ($refProgres as $p): ?>
            <option value="<?= $p['PPR_KODE'] ?>" <?= (($filters['progres'] ?? '') == $p['PPR_KODE']) ? 'selected' : '' ?>>
              <?= esc($p['PPR_URAIAN']) ?>
            </option>
          <?php endforeach; ?>
        </select>
        <i class="fa-solid fa-caret-down absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant pointer-events-none text-[14px]"></i>
      </div>

      <!-- Filter Tahun Pensiun -->
      <div class="relative">
        <select name="tahun" onchange="this.form.submit()" class="w-full appearance-none bg-surface-container-highest text-on-surface text-body-md pl-3.5 pr-8 py-2 rounded-lg outline-none focus:ring-2 focus:ring-primary transition-all">
          <option value="">Semua Tahun Pensiun</option>
          <?php 
            $allYears = array_unique(array_merge([2024, 2025, 2026, 2027], $yearsList ?? []));
            rsort($allYears);
          ?>
          <?php foreach ($allYears as $y): ?>
            <option value="<?= $y ?>" <?= (($filters['tahun'] ?? '') == $y) ? 'selected' : '' ?>>
              Tahun <?= $y ?>
            </option>
          <?php endforeach; ?>
        </select>
        <i class="fa-solid fa-caret-down absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant pointer-events-none text-[14px]"></i>
      </div>
    </div>
  </form>

  <!-- Active Filters Summary -->
  <div class="flex items-center justify-between px-1 text-label-sm text-on-surface-variant">
    <span>Menampilkan <strong><?= $totalCount ?></strong> data pegawai persiapan pensiun</span>
    <?php if (!empty($filters['keyword'])): ?>
      <span class="bg-primary/10 text-primary px-2 py-0.5 rounded-md">Kata kunci: "<?= esc($filters['keyword']) ?>"</span>
    <?php endif; ?>
  </div>

  <!-- Employee Cards List (Live Data from pensiun_db) -->
  <div class="flex flex-col gap-3">
    <?php if (empty($candidates)): ?>
      <div class="flex flex-col items-center justify-center p-8 text-center bg-surface-container-lowest rounded-xl shadow-sm mt-4">
        <i class="fa-solid fa-magnifying-glass-minus text-[44px] text-surface-tint mb-4 opacity-50"></i>
        <h4 class="text-headline-sm text-on-surface mb-2">Tidak ada data</h4>
        <p class="text-body-md text-on-surface-variant">Tidak ditemukan data pegawai yang sesuai dengan kriteria pencarian / filter.</p>
      </div>
    <?php else: ?>
      <?php foreach ($candidates as $item): ?>
        <div class="bg-surface-container-lowest p-4 rounded-xl shadow-sm flex flex-col gap-3 relative overflow-hidden group">
          <div class="absolute top-0 left-0 w-1.5 h-full bg-primary"></div>
          
          <div class="flex justify-between items-start pl-2">
            <div class="flex flex-col">
              <span class="text-label-sm text-on-surface-variant mb-0.5">NIP. <?= esc($item['NIP']) ?></span>
              <h3 class="text-headline-sm font-headline-sm text-on-surface leading-tight"><?= esc($item['NAMA']) ?></h3>
            </div>

            <?php 
              $badgeClass = 'bg-primary-container text-on-primary';
              $iconClass = 'fa-solid fa-circle-check';
              if (($item['PPR_KODE'] ?? 1) == 1) {
                $badgeClass = 'bg-indigo-tint text-primary-container';
                $iconClass = 'fa-solid fa-clock-rotate-left';
              } elseif (($item['PPR_KODE'] ?? 1) == 2) {
                $badgeClass = 'bg-primary-container text-on-primary';
                $iconClass = 'fa-solid fa-circle-check';
              } elseif (($item['PPR_KODE'] ?? 1) == 3) {
                $badgeClass = 'bg-emerald-100 text-emerald-800';
                $iconClass = 'fa-solid fa-file-circle-check';
              }
            ?>
            <div class="<?= $badgeClass ?> px-2.5 py-1 rounded-full flex items-center gap-1.5 shrink-0">
              <i class="<?= $iconClass ?> text-[13px]"></i>
              <span class="text-label-sm font-label-sm"><?= esc($item['PPR_URAIAN'] ?? 'Pengusulan') ?></span>
            </div>
          </div>

          <div class="grid grid-cols-2 gap-y-2 gap-x-4 pl-2 mt-1">
            <div class="flex flex-col">
              <span class="text-label-sm text-on-surface-variant">Jabatan</span>
              <span class="text-body-sm text-on-surface line-clamp-1"><?= esc($item['JABATAN']) ?></span>
            </div>
            <div class="flex flex-col">
              <span class="text-label-sm text-on-surface-variant">Tgl Pensiun</span>
              <span class="text-body-sm text-on-surface font-medium"><?= date('d M Y', strtotime($item['TGL_PENSIUN'])) ?></span>
            </div>
            <div class="flex flex-col col-span-2">
              <span class="text-label-sm text-on-surface-variant">Unit Kerja</span>
              <span class="text-body-sm text-on-surface font-medium"><?= esc($item['UNIT_NAMA']) ?></span>
            </div>
          </div>

          <div class="flex justify-between items-center mt-2 pt-3 relative">
            <div class="absolute top-0 left-2 right-0 h-px bg-outline-variant/30"></div>
            
            <!-- Tombol Update Progres -->
            <button type="button" onclick="openModalUpdateProgres('<?= $item['PP_ID'] ?>', '<?= esc($item['NAMA'], 'js') ?>')" class="text-primary font-label-md flex items-center gap-1.5 hover:text-primary-container transition-colors py-1.5 px-3 rounded-lg bg-primary/10 hover:bg-primary/20">
              <i class="fa-solid fa-clock-rotate-left text-[14px]"></i>
              Update Progres
            </button>

            <!-- Tombol Lihat Detail -->
            <a href="<?= base_url('data-verifikasi/' . $item['PP_ID']) ?>" class="text-on-surface-variant font-label-md flex items-center gap-1 hover:text-primary transition-colors py-1.5 px-3 rounded-lg hover:bg-surface-container">
              Lihat Detail
              <i class="fa-solid fa-chevron-right text-[14px]"></i>
            </a>
          </div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>

</div>

<?php if (can_create_persiapan()): ?>
<!-- Modal Tambah Data Pegawai -->
<div id="modalTambah" class="fixed inset-0 z-50 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4 hidden">
  <div class="bg-surface-container-lowest rounded-2xl shadow-2xl w-full max-w-md p-6 flex flex-col gap-4 max-h-[90vh] overflow-y-auto">
    <div class="flex justify-between items-center border-b pb-3 border-outline-variant/30">
      <h3 class="text-headline-sm font-bold text-on-surface">Tambah Pegawai Pensiun</h3>
      <button onclick="document.getElementById('modalTambah').classList.add('hidden')" class="text-on-surface-variant hover:text-on-surface">
        <i class="fa-solid fa-xmark text-[18px]"></i>
      </button>
    </div>

    <form action="<?= base_url('data-persiapan/create') ?>" method="POST" class="flex flex-col gap-4">
      <?= csrf_field() ?>

      <div class="flex flex-col gap-1">
        <label class="text-label-sm font-label-sm text-on-surface">Nomor Induk Pegawai (NIP)</label>
        <input type="text" name="nip" placeholder="Masukkan 18 digit NIP" required class="bg-surface-container-low px-3 py-2.5 rounded-lg outline-none text-body-md focus:ring-2 focus:ring-primary"/>
      </div>

      <?php if (is_admin_unit()): ?>
      <div class="flex flex-col gap-1">
        <label class="text-label-sm font-label-sm text-on-surface">Unit Kerja</label>
        <div class="bg-surface-container-high px-3 py-2.5 rounded-lg text-body-md text-on-surface-variant flex items-center gap-2 border border-outline-variant/30">
          <i class="fa-solid fa-building text-primary text-[14px]"></i>
          <span><?= esc(session('user_unit') ?? 'Unit Anda') ?></span>
        </div>
      </div>
      <?php endif; ?>

      <div class="flex flex-col gap-1">
        <label class="text-label-sm font-label-sm text-on-surface">Tanggal Pensiun</label>
        <input type="date" name="tgl_pensiun" required class="bg-surface-container-low px-3 py-2.5 rounded-lg outline-none text-body-md focus:ring-2 focus:ring-primary"/>
      </div>

      <div class="flex flex-col gap-1">
        <label class="text-label-sm font-label-sm text-on-surface">Jenis Pensiun</label>
        <select name="ppj_id" required class="bg-surface-container-low px-3 py-2.5 rounded-lg outline-none text-body-md focus:ring-2 focus:ring-primary">
          <?php foreach ($jenisList as $j): ?>
            <option value="<?= $j['PPJ_ID'] ?>"><?= esc($j['PPJ_URAIAN']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="flex justify-end gap-2 mt-4 pt-3 border-t border-outline-variant/30">
        <button type="button" onclick="document.getElementById('modalTambah').classList.add('hidden')" class="px-4 py-2 rounded-lg bg-surface-container-high text-on-surface hover:bg-surface-variant font-label-md">
          Batal
        </button>
        <button type="submit" class="px-5 py-2 rounded-lg bg-primary text-on-primary font-label-md hover:bg-primary-container shadow-sm">
          Simpan Data
        </button>
      </div>
    </form>
  </div>
</div>
<?php endif; ?>

<!-- Modal Update Progres Pegawai -->
<div id="modalUpdateProgres" class="fixed inset-0 z-50 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4 hidden">
  <div class="bg-surface-container-lowest rounded-2xl shadow-2xl w-full max-w-md p-6 flex flex-col gap-4">
    <div class="flex justify-between items-center border-b pb-3 border-outline-variant/30">
      <h3 class="text-headline-sm font-bold text-on-surface" id="modalProgresTitle">Update Status Progres</h3>
      <button onclick="document.getElementById('modalUpdateProgres').classList.add('hidden')" class="text-on-surface-variant hover:text-on-surface">
        <i class="fa-solid fa-xmark text-[18px]"></i>
      </button>
    </div>

    <form id="formUpdateProgres" action="" method="POST" class="flex flex-col gap-4">
      <?= csrf_field() ?>

      <div class="flex flex-col gap-1">
        <label class="text-label-sm font-label-sm text-on-surface">Tahap Progres Baru</label>
        <select name="ppr_kode" required class="bg-surface-container-low px-3 py-2 rounded-lg outline-none text-body-md focus:ring-2 focus:ring-primary">
          <?php foreach ($refProgres as $p): ?>
            <option value="<?= $p['PPR_KODE'] ?>"><?= esc($p['PPR_URAIAN']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="flex flex-col gap-1">
        <label class="text-label-sm font-label-sm text-on-surface">Catatan / Keterangan Progres</label>
        <textarea name="keterangan" rows="3" placeholder="Masukkan keterangan detail progres..." class="bg-surface-container-low px-3 py-2 rounded-lg outline-none text-body-md focus:ring-2 focus:ring-primary"></textarea>
      </div>

      <div class="flex justify-end gap-2 mt-2 pt-3 border-t border-outline-variant/30">
        <button type="button" onclick="document.getElementById('modalUpdateProgres').classList.add('hidden')" class="px-4 py-2 rounded-lg bg-surface-container-high text-on-surface hover:bg-surface-variant font-label-md">
          Batal
        </button>
        <button type="submit" class="px-5 py-2 rounded-lg bg-primary text-on-primary font-label-md hover:bg-primary-container shadow-sm">
          Simpan Status Progres
        </button>
      </div>
    </form>
  </div>
</div>

<script>
  function openModalUpdateProgres(ppId, nama) {
    document.getElementById('formUpdateProgres').action = '<?= base_url('data-persiapan') ?>/' + ppId + '/update-progres';
    document.getElementById('modalProgresTitle').innerText = 'Update Progres: ' + nama;
    document.getElementById('modalUpdateProgres').classList.remove('hidden');
  }
</script>

<?= $this->endSection() ?>
