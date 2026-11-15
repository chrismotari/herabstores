<?php

use App\Models\Contact;

test('anyone can send a contact message', function () {
    $this->postJson('/api/v1/contacts', [
        'name' => 'Jane', 'email' => 'jane@example.com', 'subject' => 'Delivery', 'message' => 'Do you deliver to Karen?', 'consented' => true,
    ])->assertCreated();

    $contact = Contact::sole();
    expect($contact->message)->toBe('Do you deliver to Karen?')
        ->and($contact->consented)->toBeTruthy()
        ->and($contact->source)->toBe('Website');
});

test('contact form validates and is rate limited', function () {
    $this->postJson('/api/v1/contacts', ['email' => 'nope'])->assertJsonValidationErrors('email');

    foreach (range(1, 4) as $i) {
        $this->postJson('/api/v1/contacts', ['email' => "a{$i}@example.com"]);
    }
    $this->postJson('/api/v1/contacts', ['email' => 'last@example.com'])->assertTooManyRequests();
});

test('only allowed fields are stored', function () {
    $this->postJson('/api/v1/contacts', ['email' => 'jane@example.com', 'source' => 'Admin', 'ref_code' => 'X'])->assertCreated();

    expect(Contact::sole()->source)->toBe('Website')
        ->and(Contact::sole()->ref_code)->toBeNull();
});
