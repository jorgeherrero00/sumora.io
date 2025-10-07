<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function send(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        // Enviar el email
        Mail::raw($validated['message'], function ($message) use ($validated) {
            $message->to('jorgeherrero.dev@gmail.com')
                    ->subject('Contacto desde Syntal: ' . $validated['subject'])
                    ->replyTo($validated['email'], $validated['name']);
        });
        
        return redirect('/')->with('success', 'Mensaje enviado correctamente');
    }
}