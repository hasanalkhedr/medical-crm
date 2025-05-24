<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Lead;
use App\Models\Tag;
use App\Models\Role;
use App\Models\User;
use App\Models\Product;
use App\Models\Customer;
use App\Models\LeadSource;
use App\Models\CustomField;
use App\Models\PipelineStage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'tuantq',
        //     'email' => 'admin@gmail.com',
        //     'password' => Hash::make('12341234'),
        // ]);

        $leadSources = [
            'Website',
            'Online AD',
            'Twitter',
            'LinkedIn',
            'Webinar',
            'Trade Show',
            'Referral',
        ];
        foreach ($leadSources as $leadSource) {
            LeadSource::create(['name' => $leadSource]);
        }

        $tags = [
            'Priority',
            'VIP'
        ];

        foreach ($tags as $tag) {
            Tag::create(['name' => $tag]);
        }

        $pipelineStages = [
            [
                'name' => 'New',
                'position' => 1,
                'is_default' => true,
            ],
            [
                'name' => 'Follow Up',
                'position' => 2,
            ],
            [
                'name' => 'Not Qualified',
                'position' => 3,
            ],
            [
                'name' => 'Booked',
                'position' => 4,
            ],
            [
                'name' => 'No Show',
                'position' => 5,
            ]
        ];

        foreach ($pipelineStages as $stage) {
            PipelineStage::create($stage);
        }

        $defaultPipelineStage = PipelineStage::where('is_default', true)->first()->id;
        Lead::factory()->count(10)->create([
            'pipeline_stage_id' => $defaultPipelineStage,
        ]);


        $roles = [
            'Admin',
            'Doctor',
            'Nurse',
            'Marketing',
            'Sales',
        ];

        foreach ($roles as $role) {
            Role::create(['name' => $role]);
        }

        User::factory()->create([
            'name' => 'Admin Admin',
            'email' => 'admin@medical-crm.com',
            'password' => Hash::make('password'),
            'role_id' => Role::where('name', 'Admin')->first()->id,
        ]);
        User::factory()->create([
            'name' => 'Doctor Doctor',
            'email' => 'doctor@medical-crm.com',
            'password' => Hash::make('password'),
            'role_id' => Role::where('name', 'Doctor')->first()->id,
        ]);
        User::factory()->create([
            'name' => 'Nurse Nurse',
            'email' => 'nurse@medical-crm.com',
            'password' => Hash::make('password'),
            'role_id' => Role::where('name', 'Nurse')->first()->id,
        ]);
        User::factory()->create([
            'name' => 'Marketing Marketing',
            'email' => 'marketing@medical-crm.com',
            'password' => Hash::make('password'),
            'role_id' => Role::where('name', 'Marketing')->first()->id,
        ]);
        User::factory()->create([
            'name' => 'Sales Sales',
            'email' => 'sales@medical-crm.com',
            'password' => Hash::make('password'),
            'role_id' => Role::where('name', 'Sales')->first()->id,
        ]);


        $products = [
            ['name' => 'Product 1', 'price' => 12.99],
            ['name' => 'Product 2', 'price' => 2.99],
            ['name' => 'Product 3', 'price' => 55.99],
            ['name' => 'Product 4', 'price' => 99.99],
            ['name' => 'Product 5', 'price' => 1.99],
            ['name' => 'Product 6', 'price' => 12.99],
            ['name' => 'Product 7', 'price' => 15.99],
            ['name' => 'Product 8', 'price' => 29.99],
            ['name' => 'Product 9', 'price' => 33.99],
            ['name' => 'Product 10', 'price' => 62.99],
            ['name' => 'Product 11', 'price' => 42.99],
            ['name' => 'Product 12', 'price' => 112.99],
            ['name' => 'Product 13', 'price' => 602.99],
            ['name' => 'Product 14', 'price' => 129.99],
            ['name' => 'Product 15', 'price' => 1200.99],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
