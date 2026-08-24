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
     * Memeriksa apakah user login adalah admin_kabupaten = TRUE atau NIP = 198611052009042003
     *
     * @return bool
     */
    function is_admin_kabupaten_or_special_nip(): bool
    {
        $adminKabupaten = session('admin_kabupaten');
        $nip = (string) (session('user_nip') ?? session('nip') ?? '');

        // Bersihkan spasi jika ada
        $nipClean = str_replace(' ', '', $nip);

        return ((int)$adminKabupaten === 1 || $adminKabupaten === true || $adminKabupaten === '1' || $nipClean === '198611052009042003');
    }
}

if (!function_exists('can_access_persiapan')) {
    function can_access_persiapan(): bool
    {
        return is_admin_kabupaten_or_special_nip();
    }
}

if (!function_exists('can_approve_dokumen')) {
    function can_approve_dokumen(): bool
    {
        return is_admin_kabupaten_or_special_nip();
    }
}

if (!function_exists('can_delete_pensiun')) {
    function can_delete_pensiun(): bool
    {
        return is_admin_kabupaten_or_special_nip();
    }
}


