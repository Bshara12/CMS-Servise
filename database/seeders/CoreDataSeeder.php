<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CoreDataSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {

            // 1. User
            $userId = DB::table('users')->insertGetId([
                'name' => 'Core Owner',
                'email' => 'owner@core.test',
                'password' => bcrypt('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 2. Project
            $projectId = DB::table('projects')->insertGetId([
                'owner_id' => $userId,
                'name' => 'Demo Project',
                'supported_languages' => json_encode(['en', 'ar']),
                'enabled_modules' => json_encode(['data_entry', 'seo']),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 3. DataTypes
            $categoryTypeId = DB::table('data_types')->insertGetId([
                'project_id' => $projectId,
                'name' => 'Category',
                'slug' => 'category',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $productTypeId = DB::table('data_types')->insertGetId([
                'project_id' => $projectId,
                'name' => 'Product',
                'slug' => 'product',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 4. Category Fields (بدون relation)
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
                [
                    'data_type_id' => $categoryTypeId,
                    'name' => 'description',
                    'type' => 'text',
                    'required' => false,
                    'translatable' => true,
                    'sort_order' => 2,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);

            // 5. Product Fields (بدون relation)
            DB::table('data_type_fields')->insert([
                [
                    'data_type_id' => $productTypeId,
                    'name' => 'name',
                    'type' => 'string',
                    'required' => true,
                    'translatable' => true,
                    'sort_order' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'data_type_id' => $productTypeId,
                    'name' => 'description',
                    'type' => 'text',
                    'required' => false,
                    'translatable' => true,
                    'sort_order' => 2,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'data_type_id' => $productTypeId,
                    'name' => 'images',
                    'type' => 'gallery',
                    'required' => false,
                    'translatable' => false,
                    'sort_order' => 3,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        });
    }
}