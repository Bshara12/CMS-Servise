<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CoreDataSeeder extends Seeder
{
    public function run()
    {
        DB::beginTransaction();

        try {

            // 0) Create default user
            $userId = DB::table('users')->insertGetId([
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => bcrypt('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 1) Create Project
            $projectId = DB::table('projects')->insertGetId([
                'public_id' => Str::uuid(),
                'name' => 'Core Project',
                'owner_id' => $userId,
                'supported_languages' => json_encode(['en', 'ar']),
                'enabled_modules' => json_encode(['cms']),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 2) Attach owner to project_user
            DB::table('project_user')->insert([
                'project_id' => $projectId,
                'user_id' => $userId,
            ]);

            // 3) Create Data Type
            $dataTypeId = DB::table('data_types')->insertGetId([
                'project_id' => $projectId,
                'name' => 'Articles',
                'slug' => 'articles',
                'description' => 'Demo article type',
                'is_active' => true,
                'settings' => json_encode([]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 4) Create Fields
            $titleFieldId = DB::table('data_type_fields')->insertGetId([
                'data_type_id' => $dataTypeId,
                'name' => 'title',
                'type' => 'text',
                'required' => true,
                'translatable' => true,
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $bodyFieldId = DB::table('data_type_fields')->insertGetId([
                'data_type_id' => $dataTypeId,
                'name' => 'body',
                'type' => 'textarea',
                'required' => false,
                'translatable' => true,
                'sort_order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $priceFieldId = DB::table('data_type_fields')->insertGetId([
                'data_type_id' => $dataTypeId,
                'name' => 'price',
                'type' => 'number',
                'required' => false,
                'translatable' => false,
                'sort_order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $cityFieldId = DB::table('data_type_fields')->insertGetId([
                'data_type_id' => $dataTypeId,
                'name' => 'city',
                'type' => 'text',
                'required' => false,
                'translatable' => false,
                'sort_order' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 5) Create MANY Entries
            $cities = ['Damascus', 'Aleppo', 'Homs', 'Latakia', 'Daraa'];

            for ($i = 1; $i <= 30; $i++) {

                // نشر عشوائي خلال آخر 30 يوم
                $publishedAt = now()->subDays(rand(0, 30));

                $entryId = DB::table('data_entries')->insertGetId([
                    'data_type_id' => $dataTypeId,
                    'project_id' => $projectId,
                    'status' => 'published',
                    'published_at' => $publishedAt,
                    'created_by' => $userId,
                    'updated_by' => $userId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $city = $cities[array_rand($cities)];
                $price = rand(10, 500);

                DB::table('data_entry_values')->insert([
                    // title
                    [
                        'data_entry_id' => $entryId,
                        'data_type_field_id' => $titleFieldId,
                        'language' => 'en',
                        'value' => "Article Title $i",
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'data_entry_id' => $entryId,
                        'data_type_field_id' => $titleFieldId,
                        'language' => 'ar',
                        'value' => "عنوان المقال رقم $i",
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],

                    // body
                    [
                        'data_entry_id' => $entryId,
                        'data_type_field_id' => $bodyFieldId,
                        'language' => 'en',
                        'value' => "This is body text for article $i.",
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'data_entry_id' => $entryId,
                        'data_type_field_id' => $bodyFieldId,
                        'language' => 'ar',
                        'value' => "هذا نص تجريبي للمقال رقم $i.",
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],

                    // price
                    [
                        'data_entry_id' => $entryId,
                        'data_type_field_id' => $priceFieldId,
                        'language' => null,
                        'value' => $price,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],

                    // city
                    [
                        'data_entry_id' => $entryId,
                        'data_type_field_id' => $cityFieldId,
                        'language' => null,
                        'value' => $city,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                ]);
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}