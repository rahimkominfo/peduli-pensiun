<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="flex flex-col w-full pb-8 gap-6 pt-2">
  
  <!-- Back Button, Page Title & Delete Action -->
  <div class="flex items-center justify-between gap-2">
    <div class="flex items-center gap-2">
      <a href="<?= can_access_persiapan() ? base_url('data-persiapan') : base_url('dashboard') ?>" class="w-10 h-10 flex items-center justify-center text-on-surface hover:bg-surface-container rounded-full transition-colors" title="<?= can_access_persiapan() ? 'Kembali ke Data Persiapan' : 'Kembali ke Dashboard' ?>">
        <i class="fa-solid fa-arrow-left text-[18px]"></i>
      </a>
      <h1 class="text-headline-sm font-headline-sm">Detail Dokumen & Verifikasi</h1>
    </div>

    <?php if (can_delete_pensiun()): ?>
    <!-- Tombol Hapus Data Pensiun -->
    <button onclick="document.getElementById('modalHapus').classList.remove('hidden')" class="bg-red-50 text-error border border-red-200 hover:bg-error hover:text-on-error flex items-center justify-center gap-1.5 px-3 py-2 rounded-lg font-label-md transition-colors shadow-sm whitespace-nowrap">
      <i class="fa-solid fa-trash-can text-[14px]"></i>
      Hapus Data Pensiun
    </button>
    <?php endif; ?>
  </div>

  <?php if (!empty($unitCandidates) && count($unitCandidates) > 1): ?>
  <!-- Switcher Pegawai dalam Unit -->
  <div class="bg-surface-container-lowest rounded-xl shadow-sm p-3.5 flex flex-col sm:flex-row sm:items-center justify-between gap-2.5 border border-outline-variant/30">
    <label for="pilihPegawai" class="text-label-md font-semibold text-on-surface flex items-center gap-2 whitespace-nowrap">
      <i class="fa-solid fa-users text-primary text-[15px]"></i>
      <span>Pilih Pegawai (<?= esc($candidate['UNIT_NAMA']) ?>):</span>
    </label>
    <div class="flex-1 sm:max-w-md w-full">
      <select id="pilihPegawai" onchange="if(this.value) window.location.href='<?= base_url('data-verifikasi') ?>/' + this.value" class="w-full bg-surface-container-low border border-outline-variant/50 text-body-sm font-medium text-on-surface rounded-lg px-3 py-2 outline-none focus:ring-2 focus:ring-primary/20 cursor-pointer transition-all">
        <?php foreach ($unitCandidates as $uc): ?>
          <option value="<?= $uc['PP_ID'] ?>" <?= (int)$uc['PP_ID'] === (int)$candidate['PP_ID'] ? 'selected' : '' ?>>
            <?= esc($uc['NAMA']) ?> - NIP: <?= esc($uc['NIP']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
  </div>
  <?php endif; ?>

  <!-- Profile Card (Live Data from pensiun_db) -->
  <div class="bg-surface-container rounded-xl shadow-sm p-4 flex gap-4 items-center relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-r from-primary/5 to-transparent"></div>
    <div class="w-16 h-16 rounded-full overflow-hidden shrink-0 relative shadow-sm border-2 border-surface bg-primary/10 flex items-center justify-center">
      <i class="fa-solid fa-user text-primary text-[28px]"></i>
    </div>
    <div class="flex flex-col flex-1 relative z-10">
      <h2 class="text-headline-sm font-headline-sm text-on-surface truncate"><?= esc($candidate['NAMA']) ?></h2>
      <p class="text-body-sm font-body-sm text-on-surface-variant truncate">NIP: <?= esc($candidate['NIP']) ?></p>
      <div class="flex flex-wrap items-center gap-2 mt-1">
        <span class="inline-flex items-center gap-1 bg-primary/10 text-primary px-2 py-0.5 rounded-full text-label-sm font-label-sm">
          <i class="fa-solid fa-building text-[13px]"></i>
          <?= esc($candidate['UNIT_NAMA']) ?>
        </span>
        <span class="text-label-sm text-on-surface-variant"><?= esc($candidate['JABATAN']) ?> (<?= esc($candidate['PANGKAT']) ?>)</span>
      </div>
    </div>
  </div>

  <!-- Form: Pembaruan Kontak -->
  <div class="bg-surface-container-lowest rounded-xl shadow-sm p-4 flex flex-col gap-4">
    <div class="flex items-center gap-2">
      <div class="w-8 h-8 rounded-full bg-secondary-container text-on-secondary-container flex items-center justify-center shrink-0">
        <i class="fa-solid fa-address-book text-[16px]"></i>
      </div>
      <h3 class="text-headline-md font-headline-md text-on-surface text-[18px]">Pembaruan Kontak</h3>
    </div>
    
    <form action="<?= base_url('data-verifikasi/' . $candidate['PP_ID'] . '/update-contact') ?>" method="POST" class="flex flex-col gap-3">
      <?= csrf_field() ?>
      <div class="flex flex-col gap-1">
        <label class="text-label-sm font-label-sm text-on-surface">Nomor HP (WhatsApp) Pegawai</label>
        <div class="flex items-center bg-surface-container-low rounded-lg px-3 py-2 focus-within:ring-2 focus-within:ring-primary/20 transition-all">
          <i class="fa-solid fa-mobile-screen-button text-on-surface-variant text-[16px] mr-2"></i>
          <input class="bg-transparent w-full outline-none text-body-md font-body-md text-on-surface placeholder:text-on-surface-variant/50" 
                 name="no_hp" type="tel" value="<?= esc($candidate['NO_HP'] ?? '') ?>" placeholder="081234567890"/>
        </div>
      </div>

      <div class="flex flex-col gap-1">
        <label class="text-label-sm font-label-sm text-on-surface">Nomor HP Suami / Istri</label>
        <div class="flex items-center bg-surface-container-low rounded-lg px-3 py-2 focus-within:ring-2 focus-within:ring-primary/20 transition-all">
          <i class="fa-solid fa-phone text-on-surface-variant text-[16px] mr-2"></i>
          <input class="bg-transparent w-full outline-none text-body-md font-body-md text-on-surface placeholder:text-on-surface-variant/50" 
                 name="no_hp_si" type="tel" value="<?= esc($candidate['NO_HP_SI'] ?? '') ?>" placeholder="081234567890"/>
        </div>
      </div>
      
      <div class="flex flex-col gap-1">
        <label class="text-label-sm font-label-sm text-on-surface">Email Aktif</label>
        <div class="flex items-center bg-surface-container-low rounded-lg px-3 py-2 focus-within:ring-2 focus-within:ring-primary/20 transition-all">
          <i class="fa-solid fa-envelope text-on-surface-variant text-[16px] mr-2"></i>
          <input class="bg-transparent w-full outline-none text-body-md font-body-md text-on-surface placeholder:text-on-surface-variant/50" 
                 name="email" type="email" value="<?= esc($candidate['EMAIL']) ?>" placeholder="nama@domain.go.id"/>
        </div>
      </div>
      
      <button type="submit" class="mt-2 w-full bg-primary text-on-primary rounded-lg py-3 flex items-center justify-center gap-2 active:scale-[0.98] transition-transform hover:bg-primary-container">
        <i class="fa-solid fa-floppy-disk text-[16px]"></i>
        <span class="text-label-md font-label-md">SIMPAN KONTAK</span>
      </button>
    </form>
  </div>

  <!-- Checklist Verifikasi Dokumen -->
  <div class="bg-surface-container-lowest rounded-xl shadow-sm p-4 flex flex-col gap-4">
    <div class="flex items-center gap-2">
      <div class="w-8 h-8 rounded-full bg-primary text-on-primary flex items-center justify-center shrink-0">
        <i class="fa-solid fa-list-check text-[16px]"></i>
      </div>
      <h3 class="text-headline-md font-headline-md text-on-surface text-[18px]">Checklist Verifikasi Dokumen</h3>
    </div>

    <div class="flex flex-col gap-3 mt-2">
      <?php foreach ($dokumenList as $doc): ?>
        <?php 
          $hasFile = !empty($doc['FILE_DOKUMEN']);
          $status = (int)($doc['APPROVE'] ?? 0);
        ?>

        <?php if ($hasFile): ?>
          <!-- Document Card (Uploaded) -->
          <div class="rounded-xl p-3.5 flex flex-col gap-3 transition-colors border <?= $status === 1 ? 'bg-emerald-50/50 border-emerald-200' : ($status === 2 ? 'bg-red-50/50 border-red-200' : 'bg-surface-container-low border-outline-variant/30') ?>">
            <div class="flex justify-between items-start gap-2">
              <div class="flex flex-col">
                <span class="text-label-md font-label-md text-on-surface font-semibold"><?= esc($doc['NM_DOKUMEN']) ?></span>
                <span class="text-body-sm text-on-surface-variant text-[12px]">File: <?= esc($doc['FILE_DOKUMEN']) ?></span>
                
                <?php if ($status === 2 && !empty($doc['KET_PENOLAKAN'])): ?>
                  <div class="mt-1.5 p-2 bg-red-100/80 rounded-lg border border-red-200 text-error text-body-sm text-[12px] flex items-center gap-1.5">
                    <i class="fa-solid fa-circle-exclamation text-[14px]"></i>
                    <span><strong>Catatan Penolakan:</strong> <?= esc($doc['KET_PENOLAKAN']) ?></span>
                  </div>
                <?php endif; ?>
              </div>

              <!-- Status Badge -->
              <?php if ($status === 1): ?>
                <div class="bg-emerald-100 text-emerald-800 px-2.5 py-1 rounded-md flex items-center gap-1 shrink-0">
                  <i class="fa-solid fa-circle-check text-[13px]"></i>
                  <span class="text-[11px] font-bold uppercase tracking-wider">Disetujui</span>
                </div>
              <?php elseif ($status === 2): ?>
                <div class="bg-red-100 text-red-800 px-2.5 py-1 rounded-md flex items-center gap-1 shrink-0">
                  <i class="fa-solid fa-circle-xmark text-[13px]"></i>
                  <span class="text-[11px] font-bold uppercase tracking-wider">Ditolak</span>
                </div>
              <?php else: ?>
                <div class="bg-amber-100 text-amber-800 px-2.5 py-1 rounded-md flex items-center gap-1 shrink-0">
                  <i class="fa-solid fa-hourglass-half text-[13px]"></i>
                  <span class="text-[11px] font-bold uppercase tracking-wider">Pending</span>
                </div>
              <?php endif; ?>
            </div>

            <!-- Action Toolbar (Lihat, Edit, Jempol Atas / Thumbs Up, Jempol Bawah / Thumbs Down) -->
            <div class="flex items-center justify-between gap-2 pt-2 border-t border-outline-variant/20">
              <div class="flex items-center gap-2">
                <!-- Tombol Lihat -->
                <?php
                  $candidateUnit = $candidate['UNIT_ID'] ?? '';
                  $docFile = $doc['FILE_DOKUMEN'];
                  $docUrl = base_url('uploads/dokumen/' . ($candidateUnit ? $candidateUnit . '/' : '') . $docFile);
                  if (!empty($candidateUnit) && !file_exists(FCPATH . 'uploads/dokumen/' . $candidateUnit . '/' . $docFile) && file_exists(FCPATH . 'uploads/dokumen/' . $docFile)) {
                      $docUrl = base_url('uploads/dokumen/' . $docFile);
                  }
                ?>
                <a href="<?= $docUrl ?>" target="_blank" class="bg-surface-container-high text-on-surface px-3 py-1.5 rounded-lg text-label-sm font-label-sm flex items-center gap-1.5 hover:bg-surface-variant transition-colors shadow-sm">
                  <i class="fa-solid fa-eye text-[14px]"></i>
                  <span>Lihat</span>
                </a>

                <!-- Tombol Edit / Unggah Ulang File (sembunyikan jika status sudah Disetujui) -->
                <?php if ($status !== 1): ?>
                <button type="button" onclick="openUploadModal('<?= $doc['DOKJES_ID'] ?>', '<?= esc($doc['NM_DOKUMEN'], 'js') ?>')" class="bg-surface-container-high text-on-surface px-3 py-1.5 rounded-lg text-label-sm font-label-sm flex items-center gap-1.5 hover:bg-surface-variant transition-colors shadow-sm">
                  <i class="fa-solid fa-pen-to-square text-[14px]"></i>
                  <span>Edit</span>
                </button>
                <?php endif; ?>
              </div>

              <?php if (can_approve_dokumen()): ?>
              <!-- Approve Actions (Thumbs Up / Thumbs Down) -->
              <div class="flex items-center gap-2">
                <!-- Tombol Thumbs Up (Jempol Atas Hijau -> Setujui) -->
                <form action="<?= base_url('data-verifikasi/' . $candidate['PP_ID'] . '/approve-dokumen') ?>" method="POST" class="inline">
                  <?= csrf_field() ?>
                  <input type="hidden" name="dokumen_id" value="<?= $doc['DOKUMEN_ID'] ?>"/>
                  <input type="hidden" name="approve_status" value="1"/>
                  <button type="submit" title="Setujui Dokumen (Jempol Atas)" class="w-9 h-9 rounded-lg flex items-center justify-center transition-all shadow-sm <?= ($status === 1) ? 'bg-emerald-600 text-white font-bold ring-2 ring-emerald-600/30' : 'bg-emerald-100 hover:bg-emerald-600 hover:text-white text-emerald-700' ?>">
                    <i class="fa-solid fa-thumbs-up text-[16px]"></i>
                  </button>
                </form>

                <!-- Tombol Thumbs Down (Jempol Bawah Merah -> Tolak + Input Alasan) -->
                <button type="button" onclick="openTolakModal('<?= $doc['DOKUMEN_ID'] ?>', '<?= esc($doc['NM_DOKUMEN'], 'js') ?>', '<?= esc($doc['KET_PENOLAKAN'] ?? '', 'js') ?>')" title="Tolak Dokumen (Jempol Bawah)" class="w-9 h-9 rounded-lg flex items-center justify-center transition-all shadow-sm <?= ($status === 2) ? 'bg-red-600 text-white font-bold ring-2 ring-red-600/30' : 'bg-red-100 hover:bg-red-600 hover:text-white text-error' ?>">
                  <i class="fa-solid fa-thumbs-down text-[16px]"></i>
                </button>
              </div>
              <?php endif; ?>
            </div>
          </div>

        <?php else: ?>
          <!-- Not Uploaded Document -->
          <div class="bg-surface-container-low rounded-lg p-3.5 flex flex-col gap-3 border border-dashed border-outline-variant/50">
            <div class="flex justify-between items-start gap-2">
              <div class="flex flex-col">
                <span class="text-label-md font-label-md text-on-surface font-semibold"><?= esc($doc['NM_DOKUMEN']) ?></span>
                <span class="text-body-sm text-on-surface-variant text-[12px]"><?= esc($doc['KONSEP_DOKUMEN'] ?: 'Silakan unggah dokumen pendukung.') ?></span>
              </div>
              <div class="bg-surface-variant text-on-surface-variant px-2.5 py-1 rounded-md flex items-center gap-1 shrink-0">
                <i class="fa-regular fa-hourglass-half text-[13px]"></i>
                <span class="text-[11px] font-bold uppercase tracking-wider">Belum Ada</span>
              </div>
            </div>
            <div class="flex gap-2 pt-1">
              <button onclick="openUploadModal('<?= $doc['DOKJES_ID'] ?>', '<?= esc($doc['NM_DOKUMEN'], 'js') ?>')" class="flex-1 bg-primary text-on-primary py-2 rounded-lg text-label-sm font-label-sm flex items-center justify-center gap-1.5 hover:bg-primary-container transition-colors shadow-sm">
                <i class="fa-solid fa-camera text-[14px]"></i> Unggah Dokumen
              </button>
            </div>
          </div>
        <?php endif; ?>

      <?php endforeach; ?>
    </div>
  </div>

  <!-- Timeline Progres -->
  <div class="bg-surface-container-lowest rounded-xl shadow-sm p-4 flex flex-col gap-4">
    <div class="flex items-center gap-2">
      <div class="w-8 h-8 rounded-full bg-tertiary-container text-on-tertiary-container flex items-center justify-center shrink-0">
        <i class="fa-solid fa-bars-staggered text-[16px]"></i>
      </div>
      <h3 class="text-headline-md font-headline-md text-on-surface text-[18px]">Timeline Progres</h3>
    </div>

    <div class="relative pl-6 mt-2 flex flex-col gap-6">
      <div class="absolute left-[11px] top-2 bottom-2 w-0.5 bg-outline-variant/50"></div>
      
      <?php if (empty($timeline)): ?>
        <p class="text-body-sm text-on-surface-variant">Belum ada catatan progres.</p>
      <?php else: ?>
        <?php foreach ($timeline as $index => $step): ?>
          <div class="relative flex flex-col gap-1">
            <div class="absolute -left-[30px] w-6 h-6 rounded-full bg-secondary text-on-secondary flex items-center justify-center z-10 shadow-sm">
              <i class="fa-solid fa-check text-[12px]"></i>
            </div>
            <h4 class="text-label-md font-label-md text-on-surface leading-tight"><?= esc($step['PPR_URAIAN'] ?? 'Progres') ?></h4>
            <p class="text-body-sm font-body-sm text-on-surface-variant text-[12px]">
              <?= date('d M Y, H:i', strtotime($step['PPR_TGL'])) ?> - <?= esc($step['PPR_KETERANGAN']) ?>
            </p>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>

</div>

<?php if (can_approve_dokumen()): ?>
<!-- Modal Form Input Alasan Penolakan (Jempol Bawah) -->
<div id="modalTolak" class="fixed inset-0 z-50 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4 hidden">
  <div class="bg-surface-container-lowest rounded-2xl shadow-2xl w-full max-w-md p-6 flex flex-col gap-4">
    <div class="flex justify-between items-center border-b pb-3 border-outline-variant/30">
      <h3 class="text-headline-sm font-bold text-error flex items-center gap-2" id="tolakTitle">
        <i class="fa-solid fa-thumbs-down text-[20px]"></i> Alasan Penolakan Dokumen
      </h3>
      <button onclick="document.getElementById('modalTolak').classList.add('hidden')" class="text-on-surface-variant hover:text-on-surface">
        <i class="fa-solid fa-xmark text-[18px]"></i>
      </button>
    </div>

    <form action="<?= base_url('data-verifikasi/' . $candidate['PP_ID'] . '/approve-dokumen') ?>" method="POST" class="flex flex-col gap-4">
      <?= csrf_field() ?>
      <input type="hidden" name="dokumen_id" id="tolakDokumenId" value=""/>
      <input type="hidden" name="approve_status" value="2"/>

      <div class="flex flex-col gap-1">
        <label class="text-label-sm font-label-sm text-on-surface">Alasan Penolakan / Catatan Revisi</label>
        <textarea name="ket_penolakan" id="tolakKetPenolakan" rows="3" required placeholder="Tuliskan alasan dokumen ditolak atau instruksi perbaikan..." class="bg-surface-container-low px-3 py-2 rounded-lg outline-none text-body-md focus:ring-2 focus:ring-error"></textarea>
      </div>

      <div class="flex justify-end gap-2 mt-2 pt-3 border-t border-outline-variant/30">
        <button type="button" onclick="document.getElementById('modalTolak').classList.add('hidden')" class="px-4 py-2 rounded-lg bg-surface-container-high text-on-surface hover:bg-surface-variant font-label-md">
          Batal
        </button>
        <button type="submit" class="px-5 py-2 rounded-lg bg-error text-on-error font-label-md hover:bg-red-700 shadow-sm flex items-center gap-1.5">
          <i class="fa-solid fa-paper-plane text-[14px]"></i> Simpan Penolakan
        </button>
      </div>
    </form>
  </div>
</div>
<?php endif; ?>

<?php if (can_delete_pensiun()): ?>
<!-- Modal Konfirmasi Hapus Data Pensiun -->
<div id="modalHapus" class="fixed inset-0 z-50 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4 hidden">
  <div class="bg-surface-container-lowest rounded-2xl shadow-2xl w-full max-w-md p-6 flex flex-col gap-4">
    <div class="flex justify-between items-center border-b pb-3 border-outline-variant/30">
      <h3 class="text-headline-sm font-bold text-error flex items-center gap-2">
        <i class="fa-solid fa-triangle-exclamation"></i> Hapus Data Pensiun
      </h3>
      <button onclick="document.getElementById('modalHapus').classList.add('hidden')" class="text-on-surface-variant hover:text-on-surface">
        <i class="fa-solid fa-xmark text-[18px]"></i>
      </button>
    </div>

    <p class="text-body-md text-on-surface">
      Apakah Anda yakin ingin menghapus data pensiun untuk <strong><?= esc($candidate['NAMA']) ?></strong> (NIP: <?= esc($candidate['NIP']) ?>)?
      <br/><span class="text-body-sm text-error font-medium mt-1 block">Tindakan ini tidak dapat dibatalkan. Seluruh berkas dan riwayat progres yang terkait akan terhapus.</span>
    </p>

    <form action="<?= base_url('data-verifikasi/' . $candidate['PP_ID'] . '/delete') ?>" method="POST" class="flex justify-end gap-2 mt-2 pt-3 border-t border-outline-variant/30">
      <?= csrf_field() ?>
      <button type="button" onclick="document.getElementById('modalHapus').classList.add('hidden')" class="px-4 py-2 rounded-lg bg-surface-container-high text-on-surface hover:bg-surface-variant font-label-md">
        Batal
      </button>
      <button type="submit" class="px-5 py-2 rounded-lg bg-error text-on-error font-label-md hover:bg-red-700 shadow-sm flex items-center gap-1.5">
        <i class="fa-solid fa-trash-can text-[14px]"></i> Ya, Hapus Data
      </button>
    </form>
  </div>
</div>
<?php endif; ?>


<!-- Modal Upload Dokumen -->
<div id="modalUpload" class="fixed inset-0 z-50 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4 hidden">
  <div class="bg-surface-container-lowest rounded-2xl shadow-2xl w-full max-w-md p-6 flex flex-col gap-4">
    <div class="flex justify-between items-center border-b pb-3 border-outline-variant/30">
      <h3 class="text-headline-sm font-bold text-on-surface" id="uploadTitle">Unggah Dokumen</h3>
      <button onclick="document.getElementById('modalUpload').classList.add('hidden')" class="text-on-surface-variant hover:text-on-surface">
        <i class="fa-solid fa-xmark text-[18px]"></i>
      </button>
    </div>

    <form action="<?= base_url('data-verifikasi/' . $candidate['PP_ID'] . '/upload-dokumen') ?>" method="POST" enctype="multipart/form-data" class="flex flex-col gap-4">
      <?= csrf_field() ?>
      <input type="hidden" name="dokjes_id" id="uploadDokjesId" value=""/>

      <div class="flex flex-col gap-1">
        <label class="text-label-sm font-label-sm text-on-surface">Pilih Berkas (PDF / JPG / PNG)</label>
        <input type="file" name="file_dokumen" required accept=".pdf,.png,.jpg,.jpeg" class="bg-surface-container-low px-3 py-2 rounded-lg outline-none text-body-md"/>
      </div>

      <div class="flex justify-end gap-2 mt-2 pt-3 border-t border-outline-variant/30">
        <button type="button" onclick="document.getElementById('modalUpload').classList.add('hidden')" class="px-4 py-2 rounded-lg bg-surface-container-high text-on-surface hover:bg-surface-variant font-label-md">
          Batal
        </button>
        <button type="submit" class="px-5 py-2 rounded-lg bg-primary text-on-primary font-label-md hover:bg-primary-container shadow-sm">
          Unggah File
        </button>
      </div>
    </form>
  </div>
</div>

<script>
  function openUploadModal(dokjesId, docName) {
    document.getElementById('uploadDokjesId').value = dokjesId;
    document.getElementById('uploadTitle').innerText = 'Unggah / Edit: ' + docName;
    document.getElementById('modalUpload').classList.remove('hidden');
  }

  function openTolakModal(dokumenId, docName, existingReason = '') {
    document.getElementById('tolakDokumenId').value = dokumenId;
    document.getElementById('tolakTitle').innerText = 'Penolakan: ' + docName;
    document.getElementById('tolakKetPenolakan').value = existingReason;
    document.getElementById('modalTolak').classList.remove('hidden');
  }
</script>

<?= $this->endSection() ?>
