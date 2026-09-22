<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SuperadminUserManagementTest extends TestCase
{
    use DatabaseTransactions;

    protected function getSuperadmin(): User
    {
        return User::firstOrCreate(
            ['email' => 'superadmin.user.test@elipsacademy.com'],
            [
                'nama' => 'Superadmin User Tester',
                'password' => Hash::make('password123'),
                'role' => 'superadmin',
            ]
        );
    }

    protected function getAdmin(): User
    {
        return User::firstOrCreate(
            ['email' => 'admin.user.test@elipsacademy.com'],
            [
                'nama' => 'Admin User Tester',
                'password' => Hash::make('password123'),
                'role' => 'admin',
            ]
        );
    }

    // 1. Role Protection Tests
    public function test_guest_is_redirected_to_login_for_user_routes(): void
    {
        $this->get(route('superadmin.user.index'))->assertRedirect('/login');
        $this->post(route('superadmin.user.store'), [])->assertRedirect('/login');
    }

    public function test_admin_cannot_access_superadmin_user_routes(): void
    {
        $admin = $this->getAdmin();

        $this->actingAs($admin)->get(route('superadmin.user.index'))->assertForbidden();
        $this->actingAs($admin)->post(route('superadmin.user.store'), [
            'nama' => 'New User',
            'email' => 'new@elipsacademy.com',
            'password' => 'secret123',
            'role' => 'admin',
        ])->assertForbidden();
    }

    // 2. View Index & Filter
    public function test_superadmin_can_view_user_index(): void
    {
        $superadmin = $this->getSuperadmin();

        $response = $this->actingAs($superadmin)->get(route('superadmin.user.index'));

        $response->assertOk();
        $response->assertSee('Manajemen Akun Pengguna');
        $response->assertSee('Tabel SQL: <code class="font-mono text-primary font-bold">users</code>', false);
        $response->assertSee($superadmin->email);
    }

    public function test_superadmin_can_search_and_filter_users(): void
    {
        $superadmin = $this->getSuperadmin();
        $admin = $this->getAdmin();

        // Search by name
        $responseSearch = $this->actingAs($superadmin)->get(route('superadmin.user.index', ['q' => 'User Tester']));
        $responseSearch->assertOk();
        $responseSearch->assertSee($superadmin->nama);
        $responseSearch->assertSee($admin->nama);

        // Filter by role: superadmin
        $responseFilterSuper = $this->actingAs($superadmin)->get(route('superadmin.user.index', ['role' => 'superadmin']));
        $responseFilterSuper->assertOk();
        $responseFilterSuper->assertSee($superadmin->email);

        // Filter by role: admin
        $responseFilterAdmin = $this->actingAs($superadmin)->get(route('superadmin.user.index', ['role' => 'admin']));
        $responseFilterAdmin->assertOk();
        $responseFilterAdmin->assertSee($admin->email);
    }

    // 3. Create User
    public function test_superadmin_can_create_user_with_hashed_password(): void
    {
        $superadmin = $this->getSuperadmin();

        $response = $this->actingAs($superadmin)->post(route('superadmin.user.store'), [
            'nama' => 'Staff Operasional Baru',
            'email' => 'staff.baru@elipsacademy.com',
            'password' => 'secret12345',
            'role' => 'admin',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('users', [
            'nama' => 'Staff Operasional Baru',
            'email' => 'staff.baru@elipsacademy.com',
            'role' => 'admin',
        ]);

        $createdUser = User::where('email', 'staff.baru@elipsacademy.com')->first();
        $this->assertNotNull($createdUser);
        $this->assertTrue(Hash::check('secret12345', $createdUser->password));
    }

    public function test_create_user_fails_on_duplicate_email(): void
    {
        $superadmin = $this->getSuperadmin();

        $response = $this->actingAs($superadmin)->post(route('superadmin.user.store'), [
            'nama' => 'Duplicate Email User',
            'email' => $superadmin->email, // Existing email
            'password' => 'secret123',
            'role' => 'admin',
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_create_user_fails_on_short_password_or_missing_fields(): void
    {
        $superadmin = $this->getSuperadmin();

        $response = $this->actingAs($superadmin)->post(route('superadmin.user.store'), [
            'nama' => '',
            'email' => 'invalid-email',
            'password' => '123', // less than 6
            'role' => 'invalid_role',
        ]);

        $response->assertSessionHasErrors(['nama', 'email', 'password', 'role']);
    }

    // 4. Update User
    public function test_superadmin_can_update_user_details(): void
    {
        $superadmin = $this->getSuperadmin();
        $user = User::create([
            'nama' => 'Akun Lama',
            'email' => 'lama@elipsacademy.com',
            'password' => Hash::make('secret123'),
            'role' => 'admin',
        ]);

        $response = $this->actingAs($superadmin)->put(route('superadmin.user.update', $user->id), [
            'nama' => 'Akun Diperbarui',
            'email' => 'baru.update@elipsacademy.com',
            'role' => 'superadmin',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'nama' => 'Akun Diperbarui',
            'email' => 'baru.update@elipsacademy.com',
            'role' => 'superadmin',
        ]);
    }

    public function test_update_user_validates_email_uniqueness_except_current_user(): void
    {
        $superadmin = $this->getSuperadmin();
        $userA = User::create([
            'nama' => 'User A',
            'email' => 'usera@elipsacademy.com',
            'password' => Hash::make('secret123'),
            'role' => 'admin',
        ]);
        $userB = User::create([
            'nama' => 'User B',
            'email' => 'userb@elipsacademy.com',
            'password' => Hash::make('secret123'),
            'role' => 'admin',
        ]);

        // Attempt to update userB with userA's email should fail
        $responseDuplicate = $this->actingAs($superadmin)->put(route('superadmin.user.update', $userB->id), [
            'nama' => 'User B Edited',
            'email' => $userA->email,
            'role' => 'admin',
        ]);
        $responseDuplicate->assertSessionHasErrors('email');

        // Keeping own email should pass
        $responseSame = $this->actingAs($superadmin)->put(route('superadmin.user.update', $userB->id), [
            'nama' => 'User B Edited',
            'email' => $userB->email,
            'role' => 'admin',
        ]);
        $responseSame->assertSessionHasNoErrors();
    }

    // 5. Reset Password
    public function test_superadmin_can_reset_another_user_password(): void
    {
        $superadmin = $this->getSuperadmin();
        $user = User::create([
            'nama' => 'Reset Target',
            'email' => 'reset.target@elipsacademy.com',
            'password' => Hash::make('oldpassword'),
            'role' => 'admin',
        ]);

        $response = $this->actingAs($superadmin)->post(route('superadmin.user.reset-password', $user->id), [
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertRedirect();
        $user->refresh();
        $this->assertTrue(Hash::check('newpassword123', $user->password));

        // Test logging in with new password after logout
        auth()->logout();
        $loginResponse = $this->post(route('login'), [
            'email' => 'reset.target@elipsacademy.com',
            'password' => 'newpassword123',
        ]);
        $loginResponse->assertRedirect(route('admin.dashboard'));
    }

    public function test_reset_password_fails_if_confirmation_does_not_match(): void
    {
        $superadmin = $this->getSuperadmin();
        $user = User::create([
            'nama' => 'Reset Target 2',
            'email' => 'reset.target2@elipsacademy.com',
            'password' => Hash::make('oldpassword'),
            'role' => 'admin',
        ]);

        $response = $this->actingAs($superadmin)->post(route('superadmin.user.reset-password', $user->id), [
            'password' => 'newpassword123',
            'password_confirmation' => 'differentpassword',
        ]);

        $response->assertSessionHasErrors('password');
    }

    // 6. Delete User & Self Protection
    public function test_superadmin_cannot_delete_own_account(): void
    {
        $superadmin = $this->getSuperadmin();

        $response = $this->actingAs($superadmin)->delete(route('superadmin.user.destroy', $superadmin->id));

        $response->assertSessionHas('error');
        $this->assertDatabaseHas('users', ['id' => $superadmin->id]);
    }

    public function test_superadmin_can_delete_other_user(): void
    {
        $superadmin = $this->getSuperadmin();
        $targetUser = User::create([
            'nama' => 'User To Delete',
            'email' => 'delete.me@elipsacademy.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        $response = $this->actingAs($superadmin)->delete(route('superadmin.user.destroy', $targetUser->id));

        $response->assertRedirect(route('superadmin.user.index'));
        $this->assertDatabaseMissing('users', ['id' => $targetUser->id]);
    }
}
