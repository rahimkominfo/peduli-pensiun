<?php

/**
 * The goal of this file is to allow developers a location
 * where they can overwrite core procedural functions and
 * replace them with their own. This file is loaded during
 * the bootstrap process and is called during the framework's
 * execution.
 *
 * This can be looked at as a `master helper` file that is
 * loaded early on, and may also contain additional functions
 * that you'd like to use throughout your entire application
 *
 * @see: https://codeigniter.com/user_guide/extending/common.html
 */

if (!function_exists('is_admin_kabupaten_or_special_nip')) {
    /**
     * Memeriksa apakah user login adalah admin_kabupaten = TRUE (atau > 0) atau NIP = 198611052009042003
     *
     * @return bool
     */
    function is_admin_kabupaten_or_special_nip(): bool
    {
        $adminKabupaten = session('admin_kabupaten');
        $nip = (string) (session('user_nip') ?? session('nip') ?? '');

        // Bersihkan spasi jika ada
        $nipClean = str_replace(' ', '', $nip);

        return ((int)$adminKabupaten > 0 || $adminKabupaten === true || $adminKabupaten === '1' || $nipClean === '198611052009042003');
    }
}

if (!function_exists('is_admin_kabupaten')) {
    /**
     * Memeriksa apakah user login adalah admin kabupaten
     *
     * @return bool
     */
    function is_admin_kabupaten(): bool
    {
        return is_admin_kabupaten_or_special_nip();
    }
}

if (!function_exists('is_verifikator')) {
    /**
     * Memeriksa apakah user login adalah verifikator (NIP = 198305192015051001)
     *
     * @return bool
     */
    function is_verifikator(): bool
    {
        $nip = (string) (session('user_nip') ?? session('nip') ?? '');
        $nipClean = trim(str_replace(' ', '', $nip));

        return ($nipClean === '198305192015051001');
    }
}

if (!function_exists('is_admin_unit')) {
    /**
     * Memeriksa apakah user login adalah admin unit (dan bukan admin kabupaten atau verifikator)
     *
     * @return bool
     */
    function is_admin_unit(): bool
    {
        if (is_admin_kabupaten() || is_verifikator()) {
            return false;
        }

        $adminUnit = session('admin_unit');
        return ((int)$adminUnit > 0 || $adminUnit === true || $adminUnit === '1');
    }
}

if (!function_exists('get_user_pensiun_data')) {
    /**
     * Mengambil data pensiun milik user login (berdasarkan NIP di tabel PP_DATA)
     *
     * @return array|null
     */
    function get_user_pensiun_data(): ?array
    {
        $nip = trim(str_replace(' ', '', (string)(session('user_nip') ?? session('nip') ?? '')));
        if (empty($nip)) {
            return null;
        }

        if (session()->has('my_pensiun_data')) {
            $cached = session('my_pensiun_data');
            if (is_array($cached) && !empty($cached['PP_ID']) && (string)($cached['NIP'] ?? '') === $nip) {
                return $cached;
            }
        }

        $ppDataModel = new \App\Models\PpDataModel();
        $pensiun = $ppDataModel->where('NIP', $nip)->first();

        if ($pensiun) {
            session()->set('my_pensiun_data', $pensiun);
            return $pensiun;
        }

        return null;
    }
}

if (!function_exists('has_data_pensiun')) {
    /**
     * Memeriksa apakah user login memiliki data pensiun di tabel PP_DATA
     *
     * @return bool
     */
    function has_data_pensiun(): bool
    {
        return (get_user_pensiun_data() !== null);
    }
}

if (!function_exists('can_access_verifikasi')) {
    /**
     * Memeriksa apakah user login dapat mengakses halaman data-verifikasi
     * (admin_unit > 0, admin_kabupaten > 0, NIP khusus, verifikator, atau terdapat NIP di PP_DATA)
     *
     * @return bool
     */
    function can_access_verifikasi(): bool
    {
        $adminUnit = (int) (session('admin_unit') ?? 0);
        $adminKabupaten = (int) (session('admin_kabupaten') ?? 0);

        return ($adminUnit > 0 || $adminKabupaten > 0 || is_admin_kabupaten_or_special_nip() || is_verifikator() || has_data_pensiun());
    }
}

if (!function_exists('can_access_candidate')) {
    /**
     * Memeriksa apakah user login dapat mengakses data kandidat pensiun tertentu
     * - Admin kabupaten / NIP khusus / Verifikator: seluruh pegawai
     * - Admin unit: seluruh pegawai dalam unit miliknya
     * - Pegawai bersangkutan: data miliknya sendiri (NIP)
     *
     * @param array|object $candidate
     * @return bool
     */
    function can_access_candidate($candidate): bool
    {
        if (empty($candidate)) {
            return false;
        }

        $candidate = (array) $candidate;

        if (is_admin_kabupaten() || is_verifikator()) {
            return true;
        }

        if (is_admin_unit()) {
            $userUnitId = (int) (session('unit_id') ?? 0);
            $candidateUnitId = (int) ($candidate['UNIT_ID'] ?? 0);
            if ($userUnitId > 0 && $candidateUnitId === $userUnitId) {
                return true;
            }
        }

        // Cek kecocokan NIP untuk pegawai pemilik data pensiun
        $userNip = trim(str_replace(' ', '', (string)(session('user_nip') ?? session('nip') ?? '')));
        $candNip = trim(str_replace(' ', '', (string)($candidate['NIP'] ?? '')));

        if (!empty($userNip) && !empty($candNip) && $userNip === $candNip) {
            return true;
        }

        return false;
    }
}

if (!function_exists('can_access_unit')) {
    /**
     * Memeriksa apakah user login dapat mengakses data dengan UNIT_ID tertentu
     * - Admin kabupaten / NIP khusus / Verifikator dapat mengakses seluruh unit
     * - Admin unit hanya dapat mengakses unit (unit_id) nya sendiri
     *
     * @param int|string $unitId
     * @return bool
     */
    function can_access_unit($unitId): bool
    {
        if (is_admin_kabupaten() || is_verifikator()) {
            return true;
        }

        if (is_admin_unit()) {
            $userUnitId = (int) (session('unit_id') ?? 0);
            return ($userUnitId > 0 && (int)$unitId === $userUnitId);
        }

        return false;
    }
}

if (!function_exists('can_access_persiapan')) {
    /**
     * Memeriksa apakah user login dapat mengakses halaman data-persiapan
     * (admin_unit > 0, admin_kabupaten > 0, NIP khusus, atau verifikator)
     *
     * @return bool
     */
    function can_access_persiapan(): bool
    {
        $adminUnit = (int) (session('admin_unit') ?? 0);
        $adminKabupaten = (int) (session('admin_kabupaten') ?? 0);

        return ($adminUnit > 0 || $adminKabupaten > 0 || is_admin_kabupaten_or_special_nip() || is_verifikator());
    }
}

if (!function_exists('can_create_persiapan')) {
    /**
     * Memeriksa apakah user login dapat menambah usulan baru di data persiapan
     * Verifikator (NIP 198305192015051001) tidak dapat menambah data (tombol disembunyikan)
     *
     * @return bool
     */
    function can_create_persiapan(): bool
    {
        if (is_verifikator()) {
            return false;
        }

        return can_access_persiapan();
    }
}

if (!function_exists('can_approve_dokumen')) {
    function can_approve_dokumen(): bool
    {
        return (is_admin_kabupaten_or_special_nip() || is_verifikator());
    }
}

if (!function_exists('can_delete_pensiun')) {
    function can_delete_pensiun(): bool
    {
        return is_admin_kabupaten_or_special_nip();
    }
}



