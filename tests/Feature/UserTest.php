<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('um é igual a um', function(){
    $number = 1;
    expect($number)->toBe(1);
});

it('O modelo consegue criar um usuário com nome Kaua', function(){

    $user = User::factory()->create([
        'name' => 'Kaua',
    ]);

    expect($user->name)->toBe('Kaua');
});

it('Os usuários podem ser atualizados', function(){

    $user = User::factory()->create();
    $oldName = $user->name;
    $user->update([
        'name' => 'Jorge',
    ]);
    expect($user->name)->not->toBe($oldName);

});

it('O modelo usuário pode criar', function(){

    $user = User::create([
            'name' => 'Kaua',
            'is_admin' => rand(0, 1),
            'phone' => '16993556789',
            'avatar' => 'https://randomuser.me/api/portraits/' . fake()->randomElement(['women', 'men']) . '/' 
                . rand(1, 100) . '.jpg',
            'email' => 'kaua@kaua.com',
            'password' => 'kauakaua',
    ]);

    expect($user->name)->toBe('Kaua');
});