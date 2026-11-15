<?php

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('new users can register', function () {
    $response = $this->post('/register', [
        'fname' => 'Test',
        'lname' => 'User',
        'phone' => '0712345678',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'address' => '72 Fox Street',
        'city' => 'Nairobi',
        'estate' => 'Kilimani',
        'country_id' => \App\Models\Country::where('name', 'Kenya')->value('id'),
    ]);

    $response->assertSessionHasNoErrors();
    $this->assertAuthenticated();
    $response->assertRedirect(route('home', absolute: false));
});
