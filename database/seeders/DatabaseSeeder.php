<?php

namespace Database\Seeders;

use App\Enums\Priority;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $projects = Project::factory()->createMany([
            ['name' => 'Website Redesign'],
            ['name' => 'Mobile App'],
            ['name' => 'Marketing Campaign'],
        ]);

        $taskNames = [
            'Website Redesign' => [
                'Define wireframes',
                'Design homepage mockup',
                'Build navigation component',
                'Implement responsive layout',
                'Write copy for landing page',
            ],
            'Mobile App' => [
                'Set up project repository',
                'Design onboarding screens',
                'Implement authentication',
                'Build dashboard view',
                'Write API integration layer',
            ],
            'Marketing Campaign' => [
                'Research target audience',
                'Draft email newsletter',
                'Schedule social media posts',
                'Coordinate with design team',
                'Review campaign analytics',
            ],
        ];

        $cycle = [Priority::UltraHigh, Priority::High, Priority::Medium, Priority::Low, Priority::Medium];

        foreach ($projects as $project) {
            foreach ($taskNames[$project->name] as $index => $name) {
                Task::create([
                    'project_id' => $project->id,
                    'name'       => $name,
                    'priority'   => $cycle[$index],
                ]);
            }
        }
    }
}
