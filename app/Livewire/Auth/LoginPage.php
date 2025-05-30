<?php

namespace App\Livewire\Auth;
use App\Models\User;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;

use Livewire\Component;

class LoginPage extends Component
{
    public $email;
    public $password;

    public function login()
    {
        $credentials = $this->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            session()->regenerate();
            $this->dispatch('loginSuccess');
        } else {
            $this->dispatch('loginFailed');
        }
    }

    public function logoutConfirm()
    {
        $this->dispatch('confirmLogout');
    }

    public function logout()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect('/login');
    }

    /*******  580f463e-da31-4ae3-996c-fc7606678d11  *******/
    #[Layout('components.layouts.auth')]
    public function render()
    {
        return view('livewire.auth.login-page', [
            'title' => 'Login',
        ]);
    }
}
