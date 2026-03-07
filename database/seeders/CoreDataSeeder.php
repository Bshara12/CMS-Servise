<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CoreDataSeeder extends Seeder
{
<<<<<<< HEAD
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
        'public_id' => Str::random(32),
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
=======
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
>>>>>>> f4a86fb9649ba8d167b864add396550e197cb9e1
