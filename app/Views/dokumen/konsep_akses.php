<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="flex flex-col w-full gap-4 mt-4">

  <!-- Floating Toast Notification for AJAX Updates -->
  <div id="toastNotification" class="fixed top-20 right-4 z-50 transform translate-x-full transition-transform duration-300 opacity-0 bg-slate-900 text-white px-4 py-3 rounded-xl shadow-2xl flex items-center gap-3 border border-slate-700 pointer-events-none">
    <div id="toastIconContainer" class="w-7 h-7 rounded-full bg-emerald-500/20 text-emerald-400 flex items-center justify-center shrink-0">
      <i class="fa-solid fa-circle-check text-[16px]"></i>
    </div>
    <span id="toastMessage" class="text-body-sm font-medium">Akses telah diganti!</span>
  </div>

  <!-- Header & Main Container Card -->
  <div class="bg-surface-container-lowest p-5 rounded-2xl shadow-sm flex flex-col gap-4 border border-outline-variant/20">
    
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 pb-3 border-b border-outline-variant/20">
      <div>
        <div class="flex items-center gap-2 text-label-sm text-on-surface-variant mb-1">
          <a href="<?= base_url('dokumen') ?>" class="hover:text-primary transition-colors">Dokumen Pensiun</a>
          <i class="fa-solid fa-chevron-right text-[10px]"></i>
          <span>Konsep Akses</span>
        </div>
        <h1 class="text-headline-md font-headline-md text-primary flex items-center gap-2">
          <i class="fa-solid fa-sliders text-[22px]"></i>
          DOKUMEN AKSES & MATRIKS SYARAT PENSIUN
        </h1>
        <p class="text-body-sm text-on-surface-variant mt-0.5">
          Kelola master dokumen dan atur matriks syarat berkas untuk tiap jenis pensiun secara real-time.
        </p>
      </div>

      <div class="flex items-center gap-2 self-start md:self-auto">
        <a href="<?= base_url('dokumen') ?>" class="bg-surface-container-high text-on-surface font-label-md px-3.5 py-2.5 rounded-lg flex items-center gap-1.5 hover:bg-surface-variant transition-all">
          <i class="fa-solid fa-arrow-left text-[14px]"></i> Kembali
        </a>
        <button type="button" 
                onclick="openModalTambah()"
                class="bg-secondary text-on-secondary font-label-md px-4 py-2.5 rounded-lg flex items-center justify-center gap-2 hover:bg-secondary-container transition-all shadow-sm cursor-pointer">
          <i class="fa-solid fa-plus text-[16px]"></i>
          TAMBAH DOKUMEN
        </button>
      </div>
    </div>



    <!-- Tabel Matriks Hak Akses Dokumen Per Jenis Pensiun -->
    <div class="overflow-x-auto rounded-xl border border-outline-variant/20 shadow-sm mt-1">
      <table class="w-full text-left border-collapse text-body-md">
        <thead>
          <tr class="bg-surface-container-high text-on-surface text-label-md border-b border-outline-variant/30">
            <th class="py-3.5 px-4 w-12 text-center" rowspan="2">No</th>
            <th class="py-3.5 px-4 font-semibold min-w-[220px]" rowspan="2">Nama Dokumen</th>
            <th class="py-2.5 px-4 text-center font-semibold border-b border-outline-variant/30 bg-surface-container-highest/60" colspan="<?= count($jenisList) ?>">
              AKSI AKSES PER JENIS PENSIUN
            </th>
            <th class="py-3.5 px-4 w-28 text-center font-semibold" rowspan="2">Aksi Master</th>
          </tr>
          <tr class="bg-surface-container-high text-on-surface text-label-sm border-b border-outline-variant/30">
            <?php foreach ($jenisList as $j): ?>
              <th class="py-2 px-3 text-center font-medium min-w-[100px]" title="<?= esc($j['PPJ_URAIAN']) ?>">
                <?php 
                  // Shorten title for display if needed
                  $shortName = $j['PPJ_URAIAN'];
                  if (str_contains($shortName, 'BUP')) $shortName = 'BUP';
                  elseif (str_contains($shortName, 'Janda')) $shortName = 'Janda/Duda';
                  elseif (str_contains($shortName, 'APS')) $shortName = 'APS';
                  elseif (str_contains($shortName, 'Uzur')) $shortName = 'Uzur';
                ?>
                <?= esc($shortName) ?>
              </th>
            <?php endforeach; ?>
          </tr>
        </thead>
        <tbody class="divide-y divide-outline-variant/20 bg-surface-container-lowest">
          <?php if (empty($dokumenList)): ?>
            <tr>
              <td colspan="<?= count($jenisList) + 3 ?>" class="py-8 text-center text-on-surface-variant">
                <i class="fa-solid fa-folder-minus text-[36px] text-outline opacity-40 mb-2"></i>
                <p class="text-body-md">Belum ada data master dokumen.</p>
              </td>
            </tr>
          <?php else: ?>
            <?php $no = 1; foreach ($dokumenList as $doc): ?>
              <tr class="hover:bg-surface-container-low/50 transition-colors">
                <td class="py-3 px-4 text-center text-on-surface-variant font-medium"><?= $no++ ?>.</td>
                <td class="py-3 px-4 font-medium text-on-surface">
                  <div class="flex flex-col">
                    <span class="font-semibold text-on-surface"><?= esc($doc['NM_DOKUMEN']) ?></span>
                    <span class="text-label-sm text-on-surface-variant font-normal">Konsep: <?= esc($doc['NM_KONSEP']) ?></span>
                  </div>
                </td>

                <!-- Checkbox Columns for Each Jenis Pensiun -->
                <?php foreach ($jenisList as $j): ?>
                  <?php $isChecked = isset($matrix[$doc['DOKUMEN_KODE']][$j['PPJ_ID']]); ?>
                  <td class="py-3 px-3 text-center bg-surface-container-low/20">
                    <label class="inline-flex items-center justify-center p-1.5 rounded-lg hover:bg-primary/10 cursor-pointer transition-colors">
                      <input type="checkbox" 
                             class="akses-cb w-4 h-4 text-primary rounded border-outline focus:ring-primary cursor-pointer accent-primary" 
                             data-dokumen="<?= $doc['DOKUMEN_KODE'] ?>" 
                             data-ppj="<?= $j['PPJ_ID'] ?>" 
                             onchange="toggleAkses(this)"
                             <?= $isChecked ? 'checked' : '' ?> />
                    </label>
                  </td>
                <?php endforeach; ?>

                <!-- Aksi Master Column (Delete) -->
                <td class="py-3 px-4 text-center">
                  <a href="<?= base_url('dokumen/delete/' . $doc['DOKUMEN_KODE']) ?>" 
                     onclick="return confirm('Apakah anda yakin ingin menghapus dokumen <?= esc($doc['NM_DOKUMEN'], 'js') ?>?')" 
                     class="w-9 h-9 rounded-lg bg-red-50 text-error hover:bg-error hover:text-white flex items-center justify-center transition-all mx-auto shadow-sm"
                     title="Hapus Dokumen Master">
                    <i class="fas fa-trash font-bold text-[14px]"></i>
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

  </div>
</div>

<!-- Modal Tambah Dokumen (#exampleModal) -->
<div id="exampleModal" class="fixed inset-0 z-50 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4 hidden">
  <div class="bg-surface-container-lowest rounded-2xl shadow-2xl w-full max-w-md p-6 flex flex-col gap-4 border border-outline-variant/20 animate-in fade-in zoom-in duration-150">
    <div class="flex justify-between items-center border-b pb-3 border-outline-variant/30">
      <h3 class="text-headline-sm font-bold text-on-surface flex items-center gap-2">
        <i class="fa-solid fa-folder-plus text-secondary text-[20px]"></i>
        TAMBAH DOKUMEN
      </h3>
      <button type="button" onclick="closeModalTambah()" class="text-on-surface-variant hover:text-error transition-colors p-1">
        <i class="fa-solid fa-xmark text-[20px]"></i>
      </button>
    </div>

    <form action="<?= base_url('dokumen/tambah_konsep') ?>" method="POST" enctype="multipart/form-data" class="flex flex-col gap-4">
      <?= csrf_field() ?>

      <div class="flex flex-col gap-1.5">
        <label class="text-label-sm font-label-sm text-on-surface">Nama Master Dokumen</label>
        <input type="text" 
               name="nm_dokumen" 
               required 
               placeholder="Contoh: Surat Keterangan Bebas Temuan"
               class="bg-surface-container-low px-3.5 py-2.5 rounded-lg outline-none text-body-md focus:ring-2 focus:ring-primary border border-outline-variant/30 transition-all"/>
      </div>

      <div class="flex flex-col gap-1.5">
        <label class="text-label-sm font-label-sm text-on-surface">Nama File Konsep / Judul Template</label>
        <input type="text" 
               name="nm_konsep" 
               placeholder="Contoh: Format Surat Bebas Temuan BKPSDMA"
               class="bg-surface-container-low px-3.5 py-2.5 rounded-lg outline-none text-body-md focus:ring-2 focus:ring-primary border border-outline-variant/30 transition-all"/>
      </div>

      <div class="flex flex-col gap-1.5">
        <label class="text-label-sm font-label-sm text-on-surface">Upload File Template (PDF / Office / Image)</label>
        <input type="file" 
               name="file_konsep" 
               accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png"
               class="bg-surface-container-low px-3 py-2 rounded-lg outline-none text-body-sm border border-outline-variant/30 cursor-pointer file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-label-sm file:font-semibold file:bg-secondary file:text-on-secondary hover:file:bg-secondary-container"/>
        <span class="text-[12px] text-on-surface-variant italic">
          *max upload 10mb (pdf, doc, docx, xls, xlsx, jpg, jpeg, png)*
        </span>
      </div>

      <div class="flex justify-end gap-2.5 mt-2 pt-3 border-t border-outline-variant/30">
        <button type="button" onclick="closeModalTambah()" class="px-4 py-2.5 rounded-lg bg-surface-container-high text-on-surface hover:bg-surface-variant font-label-md transition-colors">
          Close
        </button>
        <button type="submit" class="px-5 py-2.5 rounded-lg bg-secondary text-on-secondary font-label-md hover:bg-secondary-container transition-colors shadow-sm flex items-center gap-1.5">
          <i class="fa-solid fa-plus text-[14px]"></i>
          TAMBAH DOKUMEN
        </button>
      </div>
    </form>
  </div>
</div>

<script>
  function openModalTambah() {
    document.getElementById('exampleModal').classList.remove('hidden');
  }

  function closeModalTambah() {
    document.getElementById('exampleModal').classList.add('hidden');
  }

  let toastTimeout;
  function showToast(message, isSuccess = true) {
    const toast = document.getElementById('toastNotification');
    const toastMsg = document.getElementById('toastMessage');
    const iconContainer = document.getElementById('toastIconContainer');
    
    toastMsg.innerText = message;
    
    if (isSuccess) {
      iconContainer.className = 'w-7 h-7 rounded-full bg-emerald-500/20 text-emerald-400 flex items-center justify-center shrink-0';
      iconContainer.innerHTML = '<i class="fa-solid fa-circle-check text-[16px]"></i>';
    } else {
      iconContainer.className = 'w-7 h-7 rounded-full bg-red-500/20 text-red-400 flex items-center justify-center shrink-0';
      iconContainer.innerHTML = '<i class="fa-solid fa-circle-xmark text-[16px]"></i>';
    }

    toast.classList.remove('translate-x-full', 'opacity-0');
    toast.classList.add('translate-x-0', 'opacity-100');

    clearTimeout(toastTimeout);
    toastTimeout = setTimeout(() => {
      toast.classList.remove('translate-x-0', 'opacity-100');
      toast.classList.add('translate-x-full', 'opacity-0');
    }, 3000);
  }

  function toggleAkses(checkbox) {
    const dokumenKode = checkbox.getAttribute('data-dokumen');
    const ppjId = checkbox.getAttribute('data-ppj');
    
    const formData = new FormData();
    formData.append('dokumen_kode', dokumenKode);
    formData.append('ppj_id', ppjId);
    
    // Send CSRF Token if present
    const csrfToken = document.querySelector('input[name="<?= csrf_token() ?>"]');
    if (csrfToken) {
      formData.append('<?= csrf_token() ?>', csrfToken.value);
    }

    fetch('<?= base_url('dokumen/ganti_akses') ?>', {
      method: 'POST',
      body: formData,
      headers: {
        'X-Requested-With': 'XMLHttpRequest'
      }
    })
    .then(response => response.json())
    .then(data => {
      if (data.status === 'success') {
        showToast(data.message || 'Akses telah diganti!', true);
      } else {
        showToast(data.message || 'Gagal mengubah akses.', false);
        checkbox.checked = !checkbox.checked; // revert
      }
    })
    .catch(err => {
      console.error('AJAX Error:', err);
      showToast('Terjadi kesalahan koneksi server.', false);
      checkbox.checked = !checkbox.checked; // revert
    });
  }
</script>

<?= $this->endSection() ?>
