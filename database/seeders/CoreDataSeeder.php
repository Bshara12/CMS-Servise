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

            $faker = fake();

            $projectCount = 3;
            $typesPerProject = 3;
            $entriesPerType = 250;
            $versionsPerEntry = 2;

            $projects = [];
            $dataTypesByProject = [];
            $fieldsByDataType = [];
            $entryIdsByDataType = [];

            for ($p = 1; $p <= $projectCount; $p++) {
                $projectSlug = "project-$p";

                $projectId = DB::table('projects')->insertGetId([
                    'public_id' => Str::uuid(),
                    'slug' => $projectSlug,
                    'name' => "Project $p",
                    'owner_id' => $userId,
                    'supported_languages' => json_encode(['en', 'ar']),
                    'enabled_modules' => json_encode(['cms']),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $projects[] = $projectId;

                DB::table('project_user')->insert([
                    'project_id' => $projectId,
                    'user_id' => $userId,
                ]);

                $dataTypesByProject[$projectId] = [];

                for ($t = 1; $t <= $typesPerProject; $t++) {
                    $typeName = "Type {$p}-{$t}";
                    $typeSlug = Str::slug($typeName);

                    $dataTypeId = DB::table('data_types')->insertGetId([
                        'project_id' => $projectId,
                        'name' => $typeName,
                        'slug' => $typeSlug,
                        'description' => $faker->sentence(),
                        'is_active' => true,
                        'settings' => json_encode([]),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    $dataTypesByProject[$projectId][] = $dataTypeId;

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

                    $ratingFieldId = DB::table('data_type_fields')->insertGetId([
                        'data_type_id' => $dataTypeId,
                        'name' => 'rating',
                        'type' => 'number',
                        'required' => false,
                        'translatable' => false,
                        'sort_order' => 5,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    $fieldsByDataType[$dataTypeId] = [
                        'title' => $titleFieldId,
                        'body' => $bodyFieldId,
                        'price' => $priceFieldId,
                        'city' => $cityFieldId,
                        'rating' => $ratingFieldId,
                    ];

                    $entryIdsByDataType[$dataTypeId] = [];

                    $cities = ['Damascus', 'Aleppo', 'Homs', 'Latakia', 'Daraa', 'Hama', 'Tartus'];

                    $valuesBatch = [];
                    $seoBatch = [];
                    $versionsBatch = [];

                    for ($i = 1; $i <= $entriesPerType; $i++) {

                        $publishedAt = now()->subDays(rand(0, 365));

                        $entrySlug = "p{$p}-t{$t}-{$i}";

                        $entryId = DB::table('data_entries')->insertGetId([
                            'data_type_id' => $dataTypeId,
                            'project_id' => $projectId,
                            'slug' => $entrySlug,
                            'status' => 'published',
                            'published_at' => $publishedAt,
                            'created_by' => $userId,
                            'updated_by' => $userId,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);

                        $entryIdsByDataType[$dataTypeId][] = $entryId;

                        $city = $cities[array_rand($cities)];
                        $price = rand(10, 500);
                        $rating = rand(1, 5);

                        $valuesBatch[] = [
                            'data_entry_id' => $entryId,
                            'data_type_field_id' => $titleFieldId,
                            'language' => 'en',
                            'value' => "Title {$p}-{$t}-{$i}",
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                        $valuesBatch[] = [
                            'data_entry_id' => $entryId,
                            'data_type_field_id' => $titleFieldId,
                            'language' => 'ar',
                            'value' => "عنوان {$p}-{$t}-{$i}",
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];

                        $valuesBatch[] = [
                            'data_entry_id' => $entryId,
                            'data_type_field_id' => $bodyFieldId,
                            'language' => 'en',
                            'value' => $faker->paragraphs(2, true),
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                        $valuesBatch[] = [
                            'data_entry_id' => $entryId,
                            'data_type_field_id' => $bodyFieldId,
                            'language' => 'ar',
                            'value' => $faker->sentence(12),
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];

                        $valuesBatch[] = [
                            'data_entry_id' => $entryId,
                            'data_type_field_id' => $priceFieldId,
                            'language' => null,
                            'value' => (string) $price,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];

                        $valuesBatch[] = [
                            'data_entry_id' => $entryId,
                            'data_type_field_id' => $cityFieldId,
                            'language' => null,
                            'value' => $city,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];

                        $valuesBatch[] = [
                            'data_entry_id' => $entryId,
                            'data_type_field_id' => $ratingFieldId,
                            'language' => null,
                            'value' => (string) $rating,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];

                        $seoBatch[] = [
                            'data_entry_id' => $entryId,
                            'language' => 'en',
                            'meta_title' => "SEO {$p}-{$t}-{$i}",
                            'meta_description' => $faker->sentence(18),
                            'slug' => $entrySlug,
                            'canonical_url' => "https://example.com/{$projectSlug}/{$typeSlug}/{$entrySlug}",
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];

                        for ($v = 1; $v <= $versionsPerEntry; $v++) {
                            $versionsBatch[] = [
                                'data_entry_id' => $entryId,
                                'version_number' => $v,
                                'snapshot' => json_encode([
                                    'entry_id' => $entryId,
                                    'data_type_id' => $dataTypeId,
                                    'project_id' => $projectId,
                                    'values' => [
                                        'title_en' => "Title {$p}-{$t}-{$i}",
                                        'title_ar' => "عنوان {$p}-{$t}-{$i}",
                                        'city' => $city,
                                        'price' => $price,
                                        'rating' => $rating,
                                    ],
                                    'created_at' => now()->toISOString(),
                                ]),
                                'created_by' => $userId,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ];
                        }

                        if (count($valuesBatch) >= 2000) {
                            DB::table('data_entry_values')->insert($valuesBatch);
                            $valuesBatch = [];
                        }
                        if (count($seoBatch) >= 500) {
                            DB::table('seo_entries')->insert($seoBatch);
                            $seoBatch = [];
                        }
                        if (count($versionsBatch) >= 500) {
                            DB::table('data_entry_versions')->insert($versionsBatch);
                            $versionsBatch = [];
                        }
                    }

                    if (!empty($valuesBatch)) {
                        DB::table('data_entry_values')->insert($valuesBatch);
                    }
                    if (!empty($seoBatch)) {
                        DB::table('seo_entries')->insert($seoBatch);
                    }
                    if (!empty($versionsBatch)) {
                        DB::table('data_entry_versions')->insert($versionsBatch);
                    }
                }
            }

            $relations = [];
            foreach ($projects as $projectId) {
                $types = $dataTypesByProject[$projectId] ?? [];
                if (count($types) < 2) {
                    continue;
                }

                $a = $types[0];
                $b = $types[1];

                $relationId = DB::table('data_type_relations')->insertGetId([
                    'data_type_id' => $a,
                    'related_data_type_id' => $b,
                    'relation_type' => 'many_to_many',
                    'relation_name' => 'related',
                    'pivot_table' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $relations[] = $relationId;

                $aEntries = $entryIdsByDataType[$a] ?? [];
                $bEntries = $entryIdsByDataType[$b] ?? [];

                if (empty($aEntries) || empty($bEntries)) {
                    continue;
                }

                $entryRelationsBatch = [];

                $linkCount = min(500, count($aEntries));
                for ($i = 0; $i < $linkCount; $i++) {
                    $entryRelationsBatch[] = [
                        'data_entry_id' => $aEntries[$i],
                        'related_entry_id' => $bEntries[array_rand($bEntries)],
                        'data_type_relation_id' => $relationId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                if (!empty($entryRelationsBatch)) {
                    DB::table('data_entry_relations')->insert($entryRelationsBatch);
                }
            }

            DB::table('circuit_breakers')->upsert([
                [
                    'service_name' => 'dataEntry.delete',
                    'state' => 'closed',
                    'failure_count' => 0,
                    'failure_threshold' => 5,
                    'opened_at' => null,
                    'next_attempt_at' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'service_name' => 'dataEntry.publish',
                    'state' => 'closed',
                    'failure_count' => 0,
                    'failure_threshold' => 5,
                    'opened_at' => null,
                    'next_attempt_at' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ], ['service_name'], ['state', 'failure_count', 'failure_threshold', 'opened_at', 'next_attempt_at', 'updated_at']);

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}