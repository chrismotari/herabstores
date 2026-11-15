<?php

use App\Filament\Resources\Roles\Pages\CreateRole;
use App\Filament\Resources\Staff\Pages\CreateStaff;
use App\Filament\Resources\Staff\Pages\EditStaff;
use App\Models\Contact;
use App\Models\County;
use App\Models\DeliveryZone;
use App\Models\Estate;
use App\Models\Permission;
use App\Models\Ref;
use App\Models\Role;
use App\Models\Store;
use App\Models\User;
use Filament\Facades\Filament;
use Livewire\Livewire;

beforeEach(function () {
    $this->admin = User::factory()->create(['is_admin' => true]);
    $this->actingAs($this->admin);
    Filament::setCurrentPanel('admin');
});

test('settings pages render', function (string $url) {
    DeliveryZone::factory()->create();
    Estate::factory()->create();
    Store::factory()->create();
    Ref::create(['description' => 'Launch promo']);
    Contact::create(['email' => 'a@example.com', 'message' => 'Hi', 'source' => 'Website']);
    Role::create(['name' => 'manager', 'guard_name' => 'web']);

    $this->get($url)->assertOk();
})->with([
    '/admin/delivery-zones', '/admin/delivery-zones/create', '/admin/counties', '/admin/estates',
    '/admin/stores', '/admin/stores/create', '/admin/refs', '/admin/refs/create', '/admin/contacts',
    '/admin/staff', '/admin/staff/create', '/admin/roles', '/admin/roles/create', '/admin/permissions',
]);

test('staff accounts are admins with hashed passwords and roles', function () {
    $role = Role::create(['name' => 'manager', 'guard_name' => 'web']);

    Livewire::test(CreateStaff::class)
        ->fillForm([
            'fname' => 'Wanjiru', 'lname' => 'M', 'email' => 'wanjiru@herabs.test', 'phone' => '0700111222',
            'password' => 'secret-pass', 'roles' => [$role->id], 'is_active' => true,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $staff = User::firstWhere('email', 'wanjiru@herabs.test');
    expect($staff->is_admin)->toBeTruthy()
        ->and(Hash::check('secret-pass', $staff->password))->toBeTrue()
        ->and($staff->hasRole('manager'))->toBeTrue();
});

test('editing staff without a password keeps the old one', function () {
    $staff = User::factory()->create(['is_admin' => true]);

    Livewire::test(EditStaff::class, ['record' => $staff->getRouteKey()])
        ->fillForm(['fname' => 'Renamed', 'password' => ''])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($staff->fresh()->fname)->toBe('Renamed')
        ->and(Hash::check('password', $staff->fresh()->password))->toBeTrue();
});

test('admins cannot delete or deactivate themselves', function () {
    Livewire::test(EditStaff::class, ['record' => $this->admin->getRouteKey()])
        ->assertActionHidden('delete')
        ->assertFormFieldIsDisabled('is_active');
});

test('customers are not listed as staff', function () {
    $customer = User::factory()->create();

    $this->get("/admin/staff/{$customer->id}/edit")->assertNotFound();
});

test('roles can be given permissions', function () {
    $permission = Permission::create(['name' => 'manage orders', 'guard_name' => 'web']);

    Livewire::test(CreateRole::class)
        ->fillForm(['name' => 'dispatcher', 'guard_name' => 'web', 'permissions' => [$permission->id]])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(Role::findByName('dispatcher')->hasPermissionTo('manage orders'))->toBeTrue();
});

test('referral codes are generated and estates have no fee any more', function () {
    $ref = Ref::create(['description' => 'Radio ad']);

    expect($ref->ref_code)->toBeString()->not->toBeEmpty()
        ->and($ref->id)->toBeString()
        ->and(Schema::hasColumn('estates', 'delivery_fees'))->toBeFalse();
});

test('the contacts inbox has no create page', function () {
    $this->get('/admin/contacts/create')->assertNotFound();
});

test('delivery zones save county, estate and active flag', function () {
    $county = County::factory()->create();
    $zone = DeliveryZone::create(['name' => 'Karen', 'delivery_fees' => 300, 'county_id' => $county->id, 'is_active' => false]);

    expect($zone->fresh()->county_id)->toBe($county->id)->and($zone->fresh()->is_active)->toBeFalse();
});
