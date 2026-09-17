<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    protected function getAdminUser(): User
    {
        return User::firstOrCreate(
            ['email' => 'admin.test@elipsacademy.com'],
            [
                'nama' => 'Admin Test',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );
    }

    protected function getSuperadminUser(): User
    {
        return User::firstOrCreate(
            ['email' => 'superadmin.test@elipsacademy.com'],
            [
                'nama' => 'Superadmin Test',
                'password' => Hash::make('password'),
                'role' => 'superadmin',
            ]
        );
    }

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('Masuk ke Akun Anda');
        $response->assertSee('Sistem Penjadwalan Terpadu');
    }

    public function test_root_redirects_guest_to_login(): void
    {
        $response = $this->get('/');

        $response->assertRedirect('/login');
    }

    public function test_admin_can_login_and_is_redirected_to_admin_dashboard(): void
    {
        $admin = $this->getAdminUser();

        $response = $this->post('/login', [
            'email' => $admin->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($admin);
        $response->assertRedirect(route('admin.dashboard'));
    }

    public function test_superadmin_can_login_and_is_redirected_to_superadmin_dashboard(): void
    {
        $superadmin = $this->getSuperadminUser();

        $response = $this->post('/login', [
            'email' => $superadmin->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($superadmin);
        $response->assertRedirect(route('superadmin.dashboard'));
    }

    public function test_users_cannot_authenticate_with_invalid_password(): void
    {
        $admin = $this->getAdminUser();

        $response = $this->post('/login', [
            'email' => $admin->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('email');
    }

    public function test_users_cannot_authenticate_with_nonexistent_email(): void
    {
        $response = $this->post('/login', [
            'email' => 'nonexistent@elipsacademy.com',
            'password' => 'password',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('email');
    }

    public function test_authenticated_admin_visiting_login_redirects_to_admin_dashboard(): void
    {
        $admin = $this->getAdminUser();

        $response = $this->actingAs($admin)->get('/login');

        $response->assertRedirect(route('admin.dashboard'));
    }

    public function test_authenticated_superadmin_visiting_login_redirects_to_superadmin_dashboard(): void
    {
        $superadmin = $this->getSuperadminUser();

        $response = $this->actingAs($superadmin)->get('/login');

        $response->assertRedirect(route('superadmin.dashboard'));
    }

    public function test_guest_cannot_access_admin_dashboard(): void
    {
        $response = $this->get('/admin/dashboard');

        $response->assertRedirect('/login');
    }

    public function test_guest_cannot_access_superadmin_dashboard(): void
    {
        $response = $this->get('/superadmin/dashboard');

        $response->assertRedirect('/login');
    }

    public function test_admin_can_access_admin_dashboard(): void
    {
        $admin = $this->getAdminUser();

        $response = $this->actingAs($admin)->get('/admin/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Selamat Datang, ' . $admin->nama);
        $response->assertSee('Admin');
    }

    public function test_admin_cannot_access_superadmin_dashboard(): void
    {
        $admin = $this->getAdminUser();

        $response = $this->actingAs($admin)->get('/superadmin/dashboard');

        $response->assertStatus(403);
    }

    public function test_superadmin_can_access_superadmin_dashboard(): void
    {
        $superadmin = $this->getSuperadminUser();

        $response = $this->actingAs($superadmin)->get('/superadmin/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Selamat Datang, ' . $superadmin->nama);
        $response->assertSee('Superadmin');
    }

    public function test_superadmin_can_access_admin_dashboard(): void
    {
        $superadmin = $this->getSuperadminUser();

        $response = $this->actingAs($superadmin)->get('/admin/dashboard');

        $response->assertStatus(200);
    }

    public function test_user_can_logout(): void
    {
        $admin = $this->getAdminUser();

        $response = $this->actingAs($admin)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/login');
        $response->assertSessionHas('success');
    }
}
