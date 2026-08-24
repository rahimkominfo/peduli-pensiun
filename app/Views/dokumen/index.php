<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="flex flex-col w-full gap-4 mt-4">

  <!-- Header & Card Container: Konsep Dokumen -->
  <div class="bg-surface-container-lowest p-5 rounded-2xl shadow-sm flex flex-col gap-4 border border-outline-variant/20">
    
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 pb-3 border-b border-outline-variant/20">
      <div>
        <h1 class="text-headline-md font-headline-md text-primary flex items-center gap-2">
          <i class="fa-solid fa-file-contract text-[22px]"></i>
          KONSEP DOKUMEN
        </h1>
        <p class="text-body-sm text-on-surface-variant mt-0.5">
          Master berkas acuan dan template surat persyaratan pensiun pegawai.
        </p>
      </div>

      <?php if (is_admin_kabupaten_or_special_nip()): ?>
      <div class="flex items-center gap-2 self-start md:self-auto">
        <a href="<?= base_url('dokumen/konsep_akses') ?>" 
           class="bg-primary text-on-primary font-label-md px-4 py-2.5 rounded-lg flex items-center justify-center gap-2 hover:bg-primary-container transition-all shadow-sm"
           title="Ke Halaman Matriks Dokumen & Hak Akses Konsep">
          <i class="fa-solid fa-sliders text-[16px]"></i>
          Form Dokumen (Ke Akses)
        </a>
      </div>
      <?php endif; ?>
    </div>

    <!-- Filter Form -->
    <form action="<?= base_url('dokumen') ?>" method="GET" class="bg-surface-container p-4 rounded-xl flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
      <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2 flex-1">
        <label class="text-label-md font-label-md text-on-surface whitespace-nowrap flex items-center gap-1.5">
          <i class="fa-solid fa-filter text-on-surface-variant text-[14px]"></i>
          Filter Jenis Pensiun:
        </label>
        <div class="relative w-full sm:max-w-xs">
          <select name="ppj_id" class="w-full appearance-none bg-surface-container-lowest text-on-surface text-body-md pl-3.5 pr-8 py-2 rounded-lg outline-none focus:ring-2 focus:ring-primary border border-outline-variant/30 transition-all">
            <option value="">-- Semua Jenis Pensiun --</option>
            <?php foreach ($jenisList as $j): ?>
              <option value="<?= $j['PPJ_ID'] ?>" <?= ((string)$selectedPpjId === (string)$j['PPJ_ID']) ? 'selected' : '' ?>>
                <?= esc($j['PPJ_URAIAN']) ?>
              </option>
            <?php endforeach; ?>
          </select>
          <i class="fa-solid fa-caret-down absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant pointer-events-none text-[14px]"></i>
        </div>
      </div>

      <div class="flex items-center gap-2 shrink-0">
        <button type="submit" class="bg-primary text-on-primary px-5 py-2 rounded-lg font-label-md hover:bg-primary-container transition-all shadow-sm flex items-center justify-center gap-1.5">
          <i class="fa-solid fa-magnifying-glass text-[14px]"></i>
          LIHAT
        </button>
        <?php if (!empty($selectedPpjId)): ?>
          <a href="<?= base_url('dokumen') ?>" class="bg-surface-container-high text-on-surface px-3 py-2 rounded-lg font-label-md hover:bg-surface-variant transition-all flex items-center gap-1">
            <i class="fa-solid fa-rotate-left text-[14px]"></i> Reset
          </a>
        <?php endif; ?>
      </div>
    </form>

    <!-- Tabel Daftar Konsep Dokumen -->
    <div class="overflow-x-auto rounded-xl border border-outline-variant/20 shadow-sm mt-1">
      <table class="w-full text-left border-collapse text-body-md">
        <thead>
          <tr class="bg-surface-container-high text-on-surface text-label-md border-b border-outline-variant/30">
            <th class="py-3 px-4 w-12 text-center">No</th>
            <th class="py-3 px-4 font-semibold">Nama Dokumen</th>
            <th class="py-3 px-4 font-semibold">Nama Konsep</th>
            <th class="py-3 px-4 font-semibold min-w-[300px]">File Dokumen</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-outline-variant/20 bg-surface-container-lowest">
          <?php if (empty($dokumenList)): ?>
            <tr>
              <td colspan="4" class="py-8 text-center text-on-surface-variant">
                <i class="fa-solid fa-folder-open text-[36px] text-outline opacity-40 mb-2"></i>
                <p class="text-body-md">Tidak ada konsep dokumen yang ditemukan untuk filter ini.</p>
              </td>
            </tr>
          <?php else: ?>
            <?php $no = 1; foreach ($dokumenList as $doc): ?>
              <tr class="hover:bg-surface-container-low/50 transition-colors">
                <td class="py-3 px-4 text-center text-on-surface-variant font-medium"><?= $no++ ?>.</td>
                <td class="py-3 px-4 font-medium text-on-surface">
                  <?= esc($doc['NM_DOKUMEN']) ?>
                </td>
                <td class="py-3 px-4 text-on-surface-variant">
                  <?= esc($doc['NM_KONSEP']) ?>
                </td>
                <td class="py-3 px-4">
                  <div class="flex items-center gap-2">
                    <!-- Tombol Download Dokumen (Hanya tampil jika KONSEP_DOKUMEN tidak kosong) -->
                    <?php if (!empty($doc['KONSEP_DOKUMEN'])): ?>
                      <a href="<?= base_url('uploads/konsep/' . $doc['KONSEP_DOKUMEN']) ?>" target="_blank" download
                         class="w-9 h-9 rounded-lg bg-emerald-50 text-emerald-700 hover:bg-emerald-600 hover:text-white flex items-center justify-center transition-all shadow-sm"
                         title="Unduh / Download Dokumen (<?= esc($doc['KONSEP_DOKUMEN']) ?>)">
                        <i class="fa-solid fa-download text-[15px]"></i>
                      </a>
                    <?php endif; ?>

                    <!-- Tombol Edit Dokumen (Hanya Ikon) -->
                    <?php if (is_admin_kabupaten_or_special_nip()): ?>
                    <button type="button" 
                            onclick="openModalEdit('<?= $doc['DOKUMEN_KODE'] ?>', '<?= esc($doc['NM_KONSEP'], 'js') ?>', '<?= esc($doc['KONSEP_DOKUMEN'], 'js') ?>')"
                            class="w-9 h-9 rounded-lg bg-indigo-50 text-primary hover:bg-primary hover:text-white flex items-center justify-center transition-all shadow-sm"
                            title="Edit Dokumen / Ubah File Konsep">
                      <i class="fa-solid fa-pen-to-square text-[15px]"></i>
                    </button>
                    <?php endif; ?>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

  </div>
</div>

<?php if (is_admin_kabupaten_or_special_nip()): ?>
<!-- Modal Edit Dokumen (#editfiledokumen) -->
<div id="editfiledokumen" class="fixed inset-0 z-50 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4 hidden">
  <div class="bg-surface-container-lowest rounded-2xl shadow-2xl w-full max-w-md p-6 flex flex-col gap-4 border border-outline-variant/20 animate-in fade-in zoom-in duration-150">
    <div class="flex justify-between items-center border-b pb-3 border-outline-variant/30">
      <h3 class="text-headline-sm font-bold text-on-surface flex items-center gap-2">
        <i class="fa-solid fa-file-pen text-primary text-[20px]"></i>
        UBAH DOKUMEN
      </h3>
      <button type="button" onclick="closeModalEdit()" class="text-on-surface-variant hover:text-error transition-colors p-1">
        <i class="fa-solid fa-xmark text-[20px]"></i>
      </button>
    </div>

    <form action="<?= base_url('dokumen/edit_konsep') ?>" method="POST" enctype="multipart/form-data" class="flex flex-col gap-4">
      <?= csrf_field() ?>
      <input type="hidden" name="DOKUMEN_KODE" id="editDokumenKode" value=""/>
      <input type="hidden" name="KONSEP_DOKUMEN" id="editKonsepDokumenLama" value=""/>

      <div class="flex flex-col gap-1.5">
        <label class="text-label-sm font-label-sm text-on-surface">Nama File / Nama Konsep</label>
        <input type="text" 
               name="NM_KONSEP" 
               id="editNmKonsep" 
               required 
               placeholder="Masukkan Nama Konsep..."
               class="bg-surface-container-low px-3.5 py-2.5 rounded-lg outline-none text-body-md focus:ring-2 focus:ring-primary border border-outline-variant/30 transition-all"/>
      </div>

      <div class="flex flex-col gap-1.5">
        <label class="text-label-sm font-label-sm text-on-surface">File Dokumen Baru (Optional / Timpa File Lama)</label>
        <input type="file" 
               name="file_konsep" 
               accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png"
               class="bg-surface-container-low px-3 py-2 rounded-lg outline-none text-body-sm border border-outline-variant/30 cursor-pointer file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-label-sm file:font-semibold file:bg-primary file:text-on-primary hover:file:bg-primary-container"/>
        <span class="text-[12px] text-on-surface-variant italic">
          *max upload 10mb (pdf, doc, docx, xls, xlsx, jpg, jpeg, png)*
        </span>
      </div>

      <div id="fileExistingNotice" class="text-body-sm text-on-surface-variant bg-surface-container-low p-2.5 rounded-lg border border-outline-variant/20 text-[13px] hidden">
        <span class="font-medium text-on-surface">File saat ini:</span> <span id="existingFileName" class="text-primary font-mono"></span>
      </div>

      <div class="flex justify-end gap-2.5 mt-2 pt-3 border-t border-outline-variant/30">
        <button type="button" onclick="closeModalEdit()" class="px-4 py-2.5 rounded-lg bg-surface-container-high text-on-surface hover:bg-surface-variant font-label-md transition-colors">
          Close
        </button>
        <button type="submit" class="px-5 py-2.5 rounded-lg bg-primary text-on-primary font-label-md hover:bg-primary-container transition-colors shadow-sm flex items-center gap-1.5">
          <i class="fa-solid fa-save text-[14px]"></i>
          EDIT FILE
        </button>
      </div>
    </form>
  </div>
</div>

<script>
  function openModalEdit(kode, nmKonsep, fileLama) {
    document.getElementById('editDokumenKode').value = kode;
    document.getElementById('editKonsepDokumenLama').value = fileLama;
    document.getElementById('editNmKonsep').value = nmKonsep;
    
    const notice = document.getElementById('fileExistingNotice');
    const fileNameSpan = document.getElementById('existingFileName');
    if (fileLama && fileLama.trim() !== '') {
      fileNameSpan.innerText = fileLama;
      notice.classList.remove('hidden');
    } else {
      notice.classList.add('hidden');
    }

    document.getElementById('editfiledokumen').classList.remove('hidden');
  }

  function closeModalEdit() {
    document.getElementById('editfiledokumen').classList.add('hidden');
  }
</script>
<?php endif; ?>

<?= $this->endSection() ?>
