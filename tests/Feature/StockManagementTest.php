<?php

namespace Tests\Feature;

use App\Models\Obat;
use App\Models\User;
use App\Models\Poli;
use App\Models\JadwalPeriksa;
use App\Models\DaftarPoli;
use App\Models\Pasien;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StockManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_add_stock_to_existing_drug(): void
    {
        $user = User::create([
            'nama' => 'Admin Test',
            'alamat' => 'Test',
            'no_ktp' => '1234567890123456',
            'no_hp' => '081234567890',
            'email' => 'admin-test@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $obat = Obat::create([
            'nama_obat' => 'Paracetamol',
            'kemasan' => 'Tablet',
            'harga' => 5000,
            'stok' => 3,
        ]);

        $response = $this->actingAs($user)->post("/admin/obat/{$obat->id}/stock-adjust", [
            'action' => 'add',
            'jumlah' => 5,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('obat', [
            'id' => $obat->id,
            'stok' => 8,
        ]);
    }

    public function test_doctor_can_prescribe_drug_and_deducts_stock(): void
    {
        $poli = Poli::create([
            'nama_poli' => 'Poli Umum',
            'keterangan' => 'Poli Umum',
        ]);

        $dokter = User::create([
            'nama' => 'Dokter Test',
            'alamat' => 'Test',
            'no_ktp' => '1111111111111111',
            'no_hp' => '081111111111',
            'email' => 'dokter-test@example.com',
            'password' => bcrypt('password'),
            'role' => 'dokter',
            'id_poli' => $poli->id,
        ]);

        $userPasien = User::create([
            'nama' => 'Pasien Test',
            'alamat' => 'Test',
            'no_ktp' => '2222222222222222',
            'no_hp' => '082222222222',
            'email' => 'pasien-test@example.com',
            'password' => bcrypt('password'),
            'role' => 'pasien',
        ]);

        $pasienObj = new Pasien([
            'nama' => 'Pasien Test',
            'alamat' => 'Test',
            'no_ktp' => '2222222222222222',
            'no_hp' => '082222222222',
            'no_rm' => 'RM-001',
        ]);
        $pasienObj->id = $userPasien->id;
        $pasienObj->save();

        $jadwal = JadwalPeriksa::create([
            'id_dokter' => $dokter->id,
            'id_poli' => $poli->id,
            'hari' => 'Senin',
            'jam_mulai' => '08:00:00',
            'jam_selesai' => '12:00:00',
        ]);

        $antrian = DaftarPoli::create([
            'id_pasien' => $userPasien->id,
            'id_jadwal' => $jadwal->id,
            'keluhan' => 'Sakit kepala',
            'no_antrian' => 1,
            'status' => 'proses',
        ]);

        $obat = Obat::create([
            'nama_obat' => 'Paracetamol',
            'kemasan' => 'Tablet',
            'harga' => 5000,
            'stok' => 10,
        ]);

        $response = $this->actingAs($dokter)->post('/dokter/periksa-pasien/store', [
            'id_daftar_poli' => $antrian->id,
            'catatan' => 'Diagnosa sakit kepala biasa',
            'id_obat' => [$obat->id],
            'jumlah' => [
                $obat->id => 3,
            ],
        ]);

        $response->assertRedirect(route('periksa-pasien.index'));
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('obat', [
            'id' => $obat->id,
            'stok' => 7,
        ]);
    }

    public function test_doctor_cannot_prescribe_drug_if_stock_is_insufficient(): void
    {
        $poli = Poli::create([
            'nama_poli' => 'Poli Umum',
            'keterangan' => 'Poli Umum',
        ]);

        $dokter = User::create([
            'nama' => 'Dokter Test',
            'alamat' => 'Test',
            'no_ktp' => '1111111111111111',
            'no_hp' => '081111111111',
            'email' => 'dokter-test@example.com',
            'password' => bcrypt('password'),
            'role' => 'dokter',
            'id_poli' => $poli->id,
        ]);

        $userPasien = User::create([
            'nama' => 'Pasien Test',
            'alamat' => 'Test',
            'no_ktp' => '2222222222222222',
            'no_hp' => '082222222222',
            'email' => 'pasien-test@example.com',
            'password' => bcrypt('password'),
            'role' => 'pasien',
        ]);

        $pasienObj = new Pasien([
            'nama' => 'Pasien Test',
            'alamat' => 'Test',
            'no_ktp' => '2222222222222222',
            'no_hp' => '082222222222',
            'no_rm' => 'RM-001',
        ]);
        $pasienObj->id = $userPasien->id;
        $pasienObj->save();

        $jadwal = JadwalPeriksa::create([
            'id_dokter' => $dokter->id,
            'id_poli' => $poli->id,
            'hari' => 'Senin',
            'jam_mulai' => '08:00:00',
            'jam_selesai' => '12:00:00',
        ]);

        $antrian = DaftarPoli::create([
            'id_pasien' => $userPasien->id,
            'id_jadwal' => $jadwal->id,
            'keluhan' => 'Sakit kepala',
            'no_antrian' => 1,
            'status' => 'proses',
        ]);

        $obat = Obat::create([
            'nama_obat' => 'Paracetamol',
            'kemasan' => 'Tablet',
            'harga' => 5000,
            'stok' => 2,
        ]);

        $response = $this->actingAs($dokter)->from('/dokter/periksa-pasien/create/' . $antrian->id)
            ->post('/dokter/periksa-pasien/store', [
                'id_daftar_poli' => $antrian->id,
                'catatan' => 'Diagnosa sakit kepala biasa',
                'id_obat' => [$obat->id],
                'jumlah' => [
                    $obat->id => 3, // more than stock
                ],
            ]);

        $response->assertRedirect('/dokter/periksa-pasien/create/' . $antrian->id);
        $response->assertSessionHas('error');
        $this->assertTrue(str_contains(session('error'), 'hanya tersisa'));
        
        $this->assertDatabaseHas('obat', [
            'id' => $obat->id,
            'stok' => 2, // unchanged
        ]);
    }

    public function test_doctor_cannot_prescribe_drug_if_stock_is_empty(): void
    {
        $poli = Poli::create([
            'nama_poli' => 'Poli Umum',
            'keterangan' => 'Poli Umum',
        ]);

        $dokter = User::create([
            'nama' => 'Dokter Test',
            'alamat' => 'Test',
            'no_ktp' => '1111111111111111',
            'no_hp' => '081111111111',
            'email' => 'dokter-test@example.com',
            'password' => bcrypt('password'),
            'role' => 'dokter',
            'id_poli' => $poli->id,
        ]);

        $userPasien = User::create([
            'nama' => 'Pasien Test',
            'alamat' => 'Test',
            'no_ktp' => '2222222222222222',
            'no_hp' => '082222222222',
            'email' => 'pasien-test@example.com',
            'password' => bcrypt('password'),
            'role' => 'pasien',
        ]);

        $pasienObj = new Pasien([
            'nama' => 'Pasien Test',
            'alamat' => 'Test',
            'no_ktp' => '2222222222222222',
            'no_hp' => '082222222222',
            'no_rm' => 'RM-001',
        ]);
        $pasienObj->id = $userPasien->id;
        $pasienObj->save();

        $jadwal = JadwalPeriksa::create([
            'id_dokter' => $dokter->id,
            'id_poli' => $poli->id,
            'hari' => 'Senin',
            'jam_mulai' => '08:00:00',
            'jam_selesai' => '12:00:00',
        ]);

        $antrian = DaftarPoli::create([
            'id_pasien' => $userPasien->id,
            'id_jadwal' => $jadwal->id,
            'keluhan' => 'Sakit kepala',
            'no_antrian' => 1,
            'status' => 'proses',
        ]);

        $obat = Obat::create([
            'nama_obat' => 'Paracetamol',
            'kemasan' => 'Tablet',
            'harga' => 5000,
            'stok' => 0,
        ]);

        $response = $this->actingAs($dokter)->from('/dokter/periksa-pasien/create/' . $antrian->id)
            ->post('/dokter/periksa-pasien/store', [
                'id_daftar_poli' => $antrian->id,
                'catatan' => 'Diagnosa sakit kepala biasa',
                'id_obat' => [$obat->id],
                'jumlah' => [
                    $obat->id => 1,
                ],
            ]);

        $response->assertRedirect('/dokter/periksa-pasien/create/' . $antrian->id);
        $response->assertSessionHas('error');
        $this->assertTrue(str_contains(session('error'), 'sudah habis'));
        
        $this->assertDatabaseHas('obat', [
            'id' => $obat->id,
            'stok' => 0, // unchanged
        ]);
    }
}

