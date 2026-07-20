<?php

use App\Mail\EmailVerificationOtpMail;
use App\Models\EmailVerificationOtp;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

use function Pest\Laravel\assertDatabaseHas;

function createOtpFor(User $user, string $code = '123456'): EmailVerificationOtp
{
    return EmailVerificationOtp::create([
        'user_id' => $user->id,
        'code_hash' => Hash::make($code),
        'expires_at' => now()->addMinutes(10),
    ]);
}

test('email verification screen can be rendered', function () {
    $user = User::factory()->unverified()->create();

    $response = $this->actingAs($user)->get('/verify-email');

    $response->assertStatus(200);
});

test('email can be verified with valid otp', function () {
    $user = User::factory()->unverified()->create();
    createOtpFor($user);

    Event::fake();

    $response = $this->actingAs($user)->post(route('verification.verify'), [
        'code' => '123456',
    ]);

    Event::assertDispatched(Verified::class);
    expect($user->fresh()->hasVerifiedEmail())->toBeTrue();
    $response->assertRedirect(route('dashboard', absolute: false).'?verified=1');
});

test('email is not verified with invalid otp', function () {
    $user = User::factory()->unverified()->create();
    createOtpFor($user);

    $response = $this->actingAs($user)->post(route('verification.verify'), [
        'code' => '654321',
    ]);

    expect($user->fresh()->hasVerifiedEmail())->toBeFalse();
    $response->assertSessionHasErrors('code');
});

test('verification otp can be resent', function () {
    Mail::fake();

    $user = User::factory()->unverified()->create();

    $response = $this->actingAs($user)->post(route('verification.send'));

    $response->assertSessionHas('status', 'verification-code-sent');
    assertDatabaseHas('email_verification_otps', ['user_id' => $user->id]);
    Mail::assertSent(EmailVerificationOtpMail::class);
});