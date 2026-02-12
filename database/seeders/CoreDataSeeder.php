<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CoreDataSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {

            /*
            |--------------------------------------------------------------------------
            | 1️⃣ Users
            |--------------------------------------------------------------------------
            */

            $ownerId = DB::table('users')->insertGetId([
                'name' => 'Project Owner',
                'email' => 'owner@test.com',
                'password' => Hash::make('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $editorId = DB::table('users')->insertGetId([
                'name' => 'Editor User',
                'email' => 'editor@test.com',
                'password' => Hash::make('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            /*
            |--------------------------------------------------------------------------
            | 2️⃣ Project
            |--------------------------------------------------------------------------
            */

            $projectId = DB::table('projects')->insertGetId([
                'name' => 'Core Test Project',
                'owner_id' => $ownerId,
                'supported_languages' => json_encode(['en', 'ar']),
                'enabled_modules' => json_encode(['cms']),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('project_user')->insert([
                'project_id' => $projectId,
                'user_id' => $editorId,
            ]);

            /*
            |--------------------------------------------------------------------------
            | 3️⃣ DataTypes
            |--------------------------------------------------------------------------
            */

            $productTypeId = DB::table('data_types')->insertGetId([
                'project_id' => $projectId,
                'name' => 'Product',
                'slug' => 'product',
                'description' => 'Products data type',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $categoryTypeId = DB::table('data_types')->insertGetId([
                'project_id' => $projectId,
                'name' => 'Category',
                'slug' => 'category',
                'description' => 'Categories data type',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            /*
            |--------------------------------------------------------------------------
            | 4️⃣ Product Fields
            |--------------------------------------------------------------------------
            */

            DB::table('data_type_fields')->insert([
                [
                    'data_type_id' => $productTypeId,
                    'name' => 'title',
                    'type' => 'string',
                    'required' => true,
                    'translatable' => true,
                    'sort_order' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'data_type_id' => $productTypeId,
                    'name' => 'price',
                    'type' => 'number',
                    'required' => true,
                    'translatable' => false,
                    'sort_order' => 2,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);

            /*
            |--------------------------------------------------------------------------
            | 5️⃣ Category Fields
            |--------------------------------------------------------------------------
            */

            DB::table('data_type_fields')->insert([
                [
                    'data_type_id' => $categoryTypeId,
                    'name' => 'name',
                    'type' => 'string',
                    'required' => true,
                    'translatable' => true,
                    'sort_order' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);

            /*
            |--------------------------------------------------------------------------
            | 6️⃣ Relation Product → Category
            |--------------------------------------------------------------------------
            | relation_type = one_to_many
            | Product belongs to Category
            */

            DB::table('data_type_relations')->insert([
                'data_type_id' => $productTypeId,
                'related_data_type_id' => $categoryTypeId,
                'relation_type' => 'one_to_many',
                'relation_name' => 'category',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            echo "\nSeed Completed Successfully:\n";
            echo "--------------------------------\n";
            echo "Project ID: $projectId\n";
            echo "Product Type ID: $productTypeId\n";
            echo "Category Type ID: $categoryTypeId\n";
            echo "Owner Email: owner@test.com | password\n";
            echo "--------------------------------\n";
        });
    }
}
