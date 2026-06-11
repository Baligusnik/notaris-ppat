<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

Route::get('/', function () {
    return view('public.home');
})->name('public.home');


Route::post('/contact-email', function (Request $request) {
    $validated = $request->validate([
        'name' => ['required', 'string', 'max:120'],
        'email' => ['required', 'email', 'max:160'],
        'phone' => ['nullable', 'string', 'max:40'],
        'subject' => ['required', 'string', 'max:160'],
        'message' => ['required', 'string', 'max:2000'],
    ]);

    $recipient = env('OFFICE_EMAIL_TO', env('MAIL_FROM_ADDRESS', 'info@notarissuryamira.co.id'));

    Mail::raw(
        "Nama: {$validated['name']}\n" .
        "Email: {$validated['email']}\n" .
        "Telepon/WhatsApp: " . ($validated['phone'] ?? '-') . "\n" .
        "Subjek: {$validated['subject']}\n\n" .
        "Pesan:\n{$validated['message']}",
        function ($message) use ($validated, $recipient) {
            $message->to($recipient)
                ->replyTo($validated['email'], $validated['name'])
                ->subject('[Kontak Website] ' . $validated['subject']);
        }
    );

    return response()->json([
        'message' => 'Pesan berhasil dikirim. Kami akan menghubungi Anda kembali.',
    ]);
})->name('contact.email');
