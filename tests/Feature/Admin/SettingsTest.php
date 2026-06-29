<?php

declare(strict_types=1);

use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Models\Role;

beforeEach(function (): void {
    Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
});

afterEach(function (): void {
    foreach (['logo.png', 'logo-light.png', 'favicon.png'] as $file) {
        $path = public_path($file);
        if (file_exists($path)) {
            unlink($path);
        }
    }
});

test('guests are redirected to login', function (): void {
    $this->get(route('admin.settings.index'))->assertRedirect('/login');
});

test('non-admin users cannot access settings', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user)->get(route('admin.settings.index'))->assertForbidden();
});

test('admin can view settings page', function (): void {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->get(route('admin.settings.index'))
        ->assertOk()
        ->assertViewIs('admin.settings.index');
});

test('admin can save google analytics code', function (): void {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->put(route('admin.settings.update'), [
            'google_analytics_code' => '<script async src="https://www.googletagmanager.com/gtag/js?id=G-TEST123"></script>',
        ])
        ->assertRedirect(route('admin.settings.index'))
        ->assertSessionHas('success');

    expect(Setting::get('google_analytics_code'))->toContain('G-TEST123');
});

test('admin can clear google analytics code', function (): void {
    $admin = User::factory()->admin()->create();
    Setting::set('google_analytics_code', '<script>GA</script>');

    $this->actingAs($admin)
        ->put(route('admin.settings.update'), [])
        ->assertRedirect(route('admin.settings.index'));

    expect(Setting::get('google_analytics_code'))->toBeNull();
});

test('admin can upload a logo', function (): void {
    $admin = User::factory()->admin()->create();
    $file = UploadedFile::fake()->image('logo.png', 200, 50);

    $this->actingAs($admin)
        ->put(route('admin.settings.update'), ['logo' => $file])
        ->assertRedirect(route('admin.settings.index'))
        ->assertSessionHas('success');

    expect(file_exists(public_path('logo.png')))->toBeTrue();
});

test('admin can upload a light logo', function (): void {
    $admin = User::factory()->admin()->create();
    $file = UploadedFile::fake()->image('logo-light.png', 200, 50);

    $this->actingAs($admin)
        ->put(route('admin.settings.update'), ['logo_light' => $file])
        ->assertRedirect(route('admin.settings.index'))
        ->assertSessionHas('success');

    expect(file_exists(public_path('logo-light.png')))->toBeTrue();
});

test('admin can upload a favicon', function (): void {
    $admin = User::factory()->admin()->create();
    $file = UploadedFile::fake()->image('favicon.png', 32, 32);

    $this->actingAs($admin)
        ->put(route('admin.settings.update'), ['favicon' => $file])
        ->assertRedirect(route('admin.settings.index'))
        ->assertSessionHas('success');

    expect(file_exists(public_path('favicon.png')))->toBeTrue();
});

test('logo upload validates image type', function (): void {
    $admin = User::factory()->admin()->create();
    $file = UploadedFile::fake()->create('malware.php', 10, 'application/x-php');

    $this->actingAs($admin)
        ->put(route('admin.settings.update'), ['logo' => $file])
        ->assertSessionHasErrors('logo');
});

test('admin can clear cache', function (): void {
    $admin = User::factory()->admin()->create();

    Artisan::spy();

    $this->actingAs($admin)
        ->post(route('admin.settings.clear-cache'))
        ->assertRedirect(route('admin.settings.index'))
        ->assertSessionHas('success');
});

test('admin downloading backup on sqlite in-memory returns error', function (): void {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->post(route('admin.settings.backup'))
        ->assertRedirect(route('admin.settings.index'))
        ->assertSessionHas('error');
});

test('guests cannot post to settings routes', function (): void {
    $this->put(route('admin.settings.update'))->assertRedirect('/login');
    $this->post(route('admin.settings.backup'))->assertRedirect('/login');
    $this->post(route('admin.settings.clear-cache'))->assertRedirect('/login');
});
