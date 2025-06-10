<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WindsurfingSession;

class WindsurfingSessionSeeder extends Seeder
{
    public function run(): void
    {
        $sessions = [
            [
                'title' => 'Beginner Windsurfing Course',
                'description' => 'Perfect for first-timers! Learn the basics of windsurfing in a safe and controlled environment. Our experienced instructors will guide you through equipment handling, basic techniques, and safety procedures.',
                'price' => 89.99,
                'max_participants' => 6,
                'duration_minutes' => 120,
                'difficulty_level' => 'Beginner',
                'is_available' => true,
            ],
            [
                'title' => 'Intermediate Wave Riding',
                'description' => 'Take your windsurfing skills to the next level! This session focuses on wave riding techniques, advanced maneuvers, and reading wind conditions. Suitable for those with basic windsurfing experience.',
                'price' => 129.99,
                'max_participants' => 4,
                'duration_minutes' => 180,
                'difficulty_level' => 'Intermediate',
                'is_available' => true,
            ],
            [
                'title' => 'Advanced Freestyle Session',
                'description' => 'Master advanced freestyle tricks and maneuvers! This intensive session is designed for experienced windsurfers looking to perfect their technique and learn new moves.',
                'price' => 149.99,
                'max_participants' => 3,
                'duration_minutes' => 240,
                'difficulty_level' => 'Advanced',
                'is_available' => true,
            ],
            [
                'title' => 'Weekend Group Session',
                'description' => 'Join our popular weekend group session! Perfect for friends and families looking to enjoy windsurfing together. Equipment and instruction included.',
                'price' => 79.99,
                'max_participants' => 8,
                'duration_minutes' => 150,
                'difficulty_level' => 'All Levels',
                'is_available' => true,
            ],
            [
                'title' => 'Private Coaching',
                'description' => 'One-on-one coaching session with our expert instructors. Customized to your skill level and goals. Perfect for focused learning and rapid improvement.',
                'price' => 199.99,
                'max_participants' => 1,
                'duration_minutes' => 120,
                'difficulty_level' => 'All Levels',
                'is_available' => true,
            ],
        ];

        foreach ($sessions as $session) {
            WindsurfingSession::create($session);
        }
    }
} 