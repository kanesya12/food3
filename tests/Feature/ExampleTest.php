<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;

class ExampleTest extends TestCase
{

    public function test_login_with_valid_data(): void
    {
        $credential = [
            'email' => 'test@gmail.com',
            'password' => 'test'
        ];

        $this->post(route('login'), $credential);
        $this->assertAuthenticated();
    }

    function test_login_if_data_false(): void
    {
        $credential = [
            'email' => 'faizdiandra11@gmail.com',
            'password' => 'test'
        ];

        $this->post(route('login'), $credential);
        $this->assertGuest();
    }

    public function test_register_if_data_true(): void
    {
        $data = [
            'name' => 'testtest',
            'email' => 'hello@gmail.com',
            'password' => 'testtest',
            'password_confirmation' => 'testtest',
            'terms' => true,
        ];

        $response = $this->post(route('register'), $data);
        $response->assertRedirect('/');
    }

    public function test_register_if_data_false(): void
    {
        $data = [
            'name' => 'opfkafkas',
            'email' => 'opfkafkas@gmail.com',
        ];
        $response = $this->post(route('register'), $data);
        $response->assertRedirect('/');
        $response->assertSessionHasErrors(['password']);
    }

    public function test_create_menu_if_data_true(): void
    {
    // Buat user untuk pengujian
    $user = User::factory()->create(['email' => 'test@gmail.com']);

    // Data valid untuk membuat menu
    $data = [
        'productname' => 'Kinderjoy',
        'productprice' => 15000,
        'productimage' => UploadedFile::fake()->image('test.jpeg'), // Membuat gambar palsu
        'productdescription' => 'Delicious chocolate for kids',
    ];

    // Simulasikan user yang sudah login dan kirim request POST
    $response = $this->actingAs($user)->post(route('foodmenu.store'), $data);

    // Pastikan redirect ke halaman daftar menu
    $response->assertRedirect('/foodmenu');
    // Pastikan status HTTP adalah 302 (Found/Redirect)
    $response->assertStatus(302);
}

    public function test_create_menu_if_data_false(): void
    {
    // Buat user untuk pengujian
    $user = User::factory()->create(['email' => 'test@gmail.com']);

    // Data tidak valid untuk membuat menu (tanpa `productname`)
    $data = [
        'productprice' => 15000,
        'productimage' => UploadedFile::fake()->image('test.jpeg'), // Membuat gambar palsu
        'productdescription' => 'Missing product name',
    ];

    // Simulasikan user yang sudah login dan kirim request POST
    $response = $this->actingAs($user)->post(route('foodmenu.store'), $data);

    // Pastikan redirect kembali karena validasi gagal
    $response->assertStatus(302);
    // Pastikan ada error validasi pada 'productname'
    $response->assertSessionHasErrors(['productname']);
    }
}
