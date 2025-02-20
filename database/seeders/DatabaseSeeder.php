<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Country;
use App\Models\Product;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::create([
            'fname'=> 'Admin',
            'mname'=> "Test",
            'lname'=> "One",
            'phone'=> 4545454,
            'email'=> 'admin@mail.com',
            'password' => Hash::make('password'),
            "address" => "72, Fox street",
            "city" => "Jerusalem",
            "estate" => "Bethsaida",
            "country_id" => Country::where('name', "like", "Kenya")->first()->id,
            "is_admin" => 1,  
        ]);

        User::create([
            'fname'=> 'User',
            'mname'=> "Test",
            'lname'=> "One",
            'phone'=> 6565615,
            'email'=> 'user@mail.com',
            'password' => Hash::make('password'),
            "address" => "72, Fox street",
            "city" => "Jerusalem",
            "estate" => "Bethsaida",
            "country_id" => Country::where('name', "like", "Kenya")->first()->id,
            "is_admin" => 0,  
        ]);

        Category::create([
            'name' => 'Test',
            'description' => 'Test category',
            'slug' => 'test',
            'is_active' => 1,
        ]);
        Category::create([
            'name' => 'General groceries',
            'description' => 'General groceries',
            'slug' => 'general-groceries',
            'is_active' => 1,
        ]);

        Product::create([
            'name' => 'Test',
            'description' => 'Test product',
            'slug' => 'test',
            'price' => 100,
            'stock' => 1,
            'is_active' => 1,
            'category_id' => Category::where('name', 'like', 'Test')->first()->id,
        ]);

    }
}
