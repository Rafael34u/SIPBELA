<?php

namespace App\Imports;

use App\Models\Perpustakaan\PerpusMasterSiswa;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImport implements ToModel, WithHeadingRow
{
    /**
     * Menyimpan data dari Excel/CSV ke tabel master siswa dan tabel users.
     */
    public function model(array $row)
    {
        $nis = isset($row['nis']) ? trim($row['nis']) : null;
        $nama = isset($row['nama']) ? trim($row['nama']) : null;

        if ($nis && $nama) {
            $master = PerpusMasterSiswa::updateOrCreate(
                ['nis' => $nis],
                [
                    'nama' => $nama,
                    'jurusan' => $row['jurusan'] ?? null,
                    'kelas' => $row['kelas'] ?? null,
                ]
            );

            // Auto-create/update user account
            User::updateOrCreate(
                ['nis' => $nis],
                [
                    'name'     => $nama,
                    'username' => $nis, // default username = NIS
                    'email'    => $nis . '@siswa.sch.id', // default email
                    'jurusan'  => $row['jurusan'] ?? '-',
                    'kelas'    => $row['kelas'] ?? '-',
                    'password' => Hash::make($nis), // default password = NIS
                    'role'     => 'siswa',
                ]
            );

            return $master;
        }

        return null;
    }
}
