<?php

namespace Tests\Feature;

use App\Models\Cabang;
use App\Models\Jadwal;
use App\Models\Program;
use App\Models\Ruangan;
use App\Models\Setting;
use App\Models\Tentor;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class WhatsAppAutoMessageTest extends TestCase
{
    use DatabaseTransactions;

    protected function getSuperadmin(): User
    {
        return User::firstOrCreate(
            ['email' => 'superadmin.setting.test@elipsacademy.com'],
            [
                'nama' => 'Superadmin Setting Tester',
                'password' => Hash::make('password123'),
                'role' => 'superadmin',
            ]
        );
    }

    protected function getAdmin(): User
    {
        return User::firstOrCreate(
            ['email' => 'admin.setting.test@elipsacademy.com'],
            [
                'nama' => 'Admin Setting Tester',
                'password' => Hash::make('password123'),
                'role' => 'admin',
            ]
        );
    }

    public function test_setting_model_get_set_and_default(): void
    {
        Setting::set('test_key', 'test_value');
        $this->assertEquals('test_value', Setting::get('test_key'));

        $this->assertEquals('fallback', Setting::get('non_existent_key', 'fallback'));
    }

    public function test_guest_is_redirected_to_login_from_setting_page(): void
    {
        $this->get(route('superadmin.setting.index'))->assertRedirect('/login');
        $this->put(route('superadmin.setting.update-wa-template'), ['wa_template' => 'test'])->assertRedirect('/login');
    }

    public function test_admin_cannot_access_setting_page(): void
    {
        $admin = $this->getAdmin();

        $this->actingAs($admin)->get(route('superadmin.setting.index'))->assertForbidden();
        $this->actingAs($admin)->put(route('superadmin.setting.update-wa-template'), [
            'wa_template' => 'Custom template',
        ])->assertForbidden();
    }

    public function test_superadmin_can_view_setting_page(): void
    {
        $superadmin = $this->getSuperadmin();

        $response = $this->actingAs($superadmin)->get(route('superadmin.setting.index'));
        $response->assertOk();
        $response->assertSee('Template Pesan WhatsApp');
        $response->assertSee('{sesi}');
        $response->assertSee('{hari}');
        $response->assertSee('{tanggal}');
        $response->assertSee('{jam}');
        $response->assertSee('{cabang}');
        $response->assertSee('{tentor}');
        $response->assertSee('{program}');
    }

    public function test_superadmin_can_update_wa_template(): void
    {
        $superadmin = $this->getSuperadmin();
        $newTemplate = "Halo {tentor}, besok ada kelas {program} jam {jam} di {cabang}.";

        $response = $this->actingAs($superadmin)->put(route('superadmin.setting.update-wa-template'), [
            'wa_template' => $newTemplate,
        ]);

        $response->assertRedirect(route('superadmin.setting.index'));
        $response->assertSessionHas('success');
        $this->assertEquals($newTemplate, Setting::get('wa_template'));
    }

    public function test_wa_template_validation_fails_when_empty(): void
    {
        $superadmin = $this->getSuperadmin();

        $response = $this->actingAs($superadmin)->put(route('superadmin.setting.update-wa-template'), [
            'wa_template' => '',
        ]);

        $response->assertSessionHasErrors(['wa_template']);
    }

    public function test_wa_link_generated_correctly_on_jadwal_show(): void
    {
        // Set fixed current time to 09:30 AM (Pagi)
        Carbon::setTestNow(Carbon::create(2026, 10, 1, 9, 30, 0));

        $cabang = Cabang::firstOrCreate(
            ['nama_cabang' => 'Cabang Test WA'],
            ['alamat' => 'Jl. Test WA No. 1', 'status' => 'aktif']
        );

        $program = Program::firstOrCreate(
            ['nama_program' => 'Program Test WA'],
            ['kategori' => 'Reguler', 'status' => 'aktif']
        );

        $tentor = Tentor::firstOrCreate(
            ['nama' => 'Kak Budi Test'],
            ['no_hp' => '081234567890', 'keahlian' => 'Komputer', 'status' => 'aktif']
        );

        $ruangan = Ruangan::firstOrCreate(
            ['cabang_id' => $cabang->id, 'nama_ruangan' => 'Lab 1 WA'],
            ['kapasitas' => 20, 'status' => 'aktif']
        );

        $jadwal = Jadwal::create([
            'nama_kelas' => 'Kelas Test WA',
            'cabang_id' => $cabang->id,
            'ruangan_id' => $ruangan->id,
            'program_id' => $program->id,
            'tentor_id' => $tentor->id,
            'tanggal' => '2026-10-02', // Jumat
            'jam_mulai' => '10:00:00',
            'jam_selesai' => '12:00:00',
            'status' => 'terjadwal',
        ]);

        Setting::set('wa_template', "Selamat {sesi} kak\nIzin Mengingatkan kak, besok hari {hari} Tanggal {tanggal} ada kelas {program} pada pukul {jam} di Elips Academy {cabang}. Terimakasih🙏");

        $admin = $this->getAdmin();
        $admin->cabang_id = $cabang->id;
        $admin->save();

        $response = $this->actingAs($admin)->get(route('admin.jadwal.show', $jadwal));

        $response->assertOk();
        $response->assertSee('id="btnWhatsAppTentor"', false);
        $response->assertSee('https://wa.me/6281234567890', false);

        // Expected message content in URL
        // Sesi: Pagi (since testNow is 09:30)
        // Hari: Jumat
        // Tanggal: 02/10/2026
        // Jam: 10:00
        // Cabang: Cabang Test WA
        // Program: Program Test WA
        $expectedMessage = "Selamat Pagi kak\nIzin Mengingatkan kak, besok hari Jumat Tanggal 02/10/2026 ada kelas Program Test WA pada pukul 10:00 di Elips Academy Cabang Test WA. Terimakasih🙏";
        $expectedWaLink = 'https://wa.me/6281234567890?text=' . urlencode($expectedMessage);

        $response->assertSee($expectedWaLink, false);

        Carbon::setTestNow(); // reset
    }

    public function test_wa_button_not_shown_when_tentor_has_no_phone(): void
    {
        $cabang = Cabang::firstOrCreate(
            ['nama_cabang' => 'Cabang Test No Phone'],
            ['alamat' => 'Jl. Test No Phone', 'status' => 'aktif']
        );

        $program = Program::firstOrCreate(
            ['nama_program' => 'Program Test No Phone'],
            ['kategori' => 'Reguler', 'status' => 'aktif']
        );

        $tentor = Tentor::firstOrCreate(
            ['nama' => 'Kak Tanpa HP'],
            ['no_hp' => null, 'keahlian' => 'Komputer', 'status' => 'aktif']
        );

        $jadwal = Jadwal::create([
            'nama_kelas' => 'Kelas Test Tanpa HP',
            'cabang_id' => $cabang->id,
            'program_id' => $program->id,
            'tentor_id' => $tentor->id,
            'tanggal' => '2026-10-02',
            'jam_mulai' => '10:00:00',
            'jam_selesai' => '12:00:00',
            'status' => 'terjadwal',
        ]);

        $admin = $this->getAdmin();
        $admin->cabang_id = $cabang->id;
        $admin->save();

        $response = $this->actingAs($admin)->get(route('admin.jadwal.show', $jadwal));

        $response->assertOk();
        $response->assertDontSee('id="btnWhatsAppTentor"', false);
    }

    public function test_wa_sessions_pagi_siang_sore_malam(): void
    {
        $cabang = Cabang::firstOrCreate(
            ['nama_cabang' => 'Cabang Sesi'],
            ['alamat' => 'Jl. Sesi', 'status' => 'aktif']
        );

        $program = Program::firstOrCreate(
            ['nama_program' => 'Program Sesi'],
            ['kategori' => 'Reguler', 'status' => 'aktif']
        );

        $tentor = Tentor::firstOrCreate(
            ['nama' => 'Kak Sesi'],
            ['no_hp' => '08987654321', 'keahlian' => 'Komputer', 'status' => 'aktif']
        );

        $jadwal = Jadwal::create([
            'nama_kelas' => 'Kelas Sesi',
            'cabang_id' => $cabang->id,
            'program_id' => $program->id,
            'tentor_id' => $tentor->id,
            'tanggal' => '2026-10-05',
            'jam_mulai' => '08:00:00',
            'jam_selesai' => '10:00:00',
            'status' => 'terjadwal',
        ]);

        Setting::set('wa_template', "Sesi: {sesi}");

        $admin = $this->getAdmin();
        $admin->cabang_id = $cabang->id;
        $admin->save();

        $cases = [
            ['hour' => 8,  'expected' => 'Sesi: Pagi'],
            ['hour' => 13, 'expected' => 'Sesi: Siang'],
            ['hour' => 16, 'expected' => 'Sesi: Sore'],
            ['hour' => 20, 'expected' => 'Sesi: Malam'],
        ];

        foreach ($cases as $case) {
            Carbon::setTestNow(Carbon::create(2026, 10, 5, $case['hour'], 0, 0));
            $response = $this->actingAs($admin)->get(route('admin.jadwal.show', $jadwal));
            $response->assertOk();
            $expectedUrl = 'https://wa.me/628987654321?text=' . urlencode($case['expected']);
            $response->assertSee($expectedUrl, false);
        }

        Carbon::setTestNow();
    }

    public function test_superadmin_views_have_pengaturan_in_sidebar_navigation(): void
    {
        $superadmin = $this->getSuperadmin();

        $routes = [
            'superadmin.dashboard',
            'superadmin.cabang.index',
            'superadmin.jadwal.index',
            'superadmin.program.index',
            'superadmin.tentor.index',
            'superadmin.ruangan.index',
            'superadmin.user.index',
            'superadmin.setting.index',
        ];

        foreach ($routes as $routeName) {
            $response = $this->actingAs($superadmin)->get(route($routeName));
            $response->assertStatus(200);
            $response->assertSee('id="nav-superadmin-setting"', false);
            $response->assertSee('Pengaturan');
        }
    }

    public function test_pengaturan_nav_is_active_on_superadmin_setting_index(): void
    {
        $superadmin = $this->getSuperadmin();

        $response = $this->actingAs($superadmin)->get(route('superadmin.setting.index'));
        $response->assertStatus(200);
        $content = $response->getContent();

        $this->assertMatchesRegularExpression(
            '/id="nav-superadmin-setting"[^>]*class="[^"]*bg-primary-container[^"]*"/',
            $content
        );
    }
}
