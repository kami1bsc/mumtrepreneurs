<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([        
            'username' => 'Admin',                
            'email' => 'admin@admin.com',         
            'password' => bcrypt('admin'),
            'type' => '0', //admin 
        ]);

        User::create([            
            'username' => 'kamran123',             
            'email' => 'kamranabrar90@gmail.com', 
            'community_url' => 'https://mtecsoft.com/', 
            'community_description' => 'Mtechsoft is a team of website designers & developers, print designers, mobile app developers and web marketers helping businesses & online investors realize the true power of web & mobile technologies.',
            'are_you_disciplined' => 'Yes',
            'community_industry' => 'Information Technology',
            'community_size' => 20,
            'city' => 'Lahore',
            'intrested_in_learning' => 'Yes',
            'like_to_contribute' => 'Yes',
            'social_media_url' => 'https://www.facebook.com/mtecsoft',
            'password' => bcrypt('12345678'),
            'type' => '1', //candidate
        ]);

        User::create([            
            'username' => 'asad123',             
            'email' => 'asad24188@gmail.com', 
            'community_url' => 'https://mtecsoft.com/', 
            'community_description' => 'Mtechsoft is a team of website designers & developers, print designers, mobile app developers and web marketers helping businesses & online investors realize the true power of web & mobile technologies.',
            'are_you_disciplined' => 'Yes',
            'community_industry' => 'Information Technology',
            'community_size' => 20,
            'city' => 'Lahore',
            'intrested_in_learning' => 'Yes',
            'like_to_contribute' => 'Yes',
            'social_media_url' => 'https://www.facebook.com/mtecsoft',
            'password' => bcrypt('12345678'),
            'type' => '1', //candidate
        ]);

        User::create([            
            'username' => 'uzma123',             
            'email' => 'uzma11@hotmail.com', 
            'community_url' => 'https://mtecsoft.com/', 
            'community_description' => 'Mtechsoft is a team of website designers & developers, print designers, mobile app developers and web marketers helping businesses & online investors realize the true power of web & mobile technologies.',
            'are_you_disciplined' => 'Yes',
            'community_industry' => 'Information Technology',
            'community_size' => 20,
            'city' => 'Lahore',
            'intrested_in_learning' => 'Yes',
            'like_to_contribute' => 'Yes',
            'social_media_url' => 'https://www.facebook.com/mtecsoft',
            'password' => bcrypt('12345678'),
            'type' => '1', //candidate
        ]);

        User::create([            
            'username' => 'waqar123',             
            'email' => 'waqarsaleem444@gmail.com', 
            'community_url' => 'https://mtecsoft.com/', 
            'community_description' => 'Mtechsoft is a team of website designers & developers, print designers, mobile app developers and web marketers helping businesses & online investors realize the true power of web & mobile technologies.',
            'are_you_disciplined' => 'Yes',
            'community_industry' => 'Information Technology',
            'community_size' => 20,
            'city' => 'Lahore',
            'intrested_in_learning' => 'Yes',
            'like_to_contribute' => 'Yes',
            'social_media_url' => 'https://www.facebook.com/mtecsoft',
            'password' => bcrypt('12345678'),
            'type' => '1', //candidate
        ]);

        User::create([            
            'username' => 'fraz123',             
            'email' => 'fraz@gmail.com', 
            'community_url' => 'https://mtecsoft.com/', 
            'community_description' => 'Mtechsoft is a team of website designers & developers, print designers, mobile app developers and web marketers helping businesses & online investors realize the true power of web & mobile technologies.',
            'are_you_disciplined' => 'Yes',
            'community_industry' => 'Information Technology',
            'community_size' => 20,
            'city' => 'Lahore',
            'intrested_in_learning' => 'Yes',
            'like_to_contribute' => 'Yes',
            'social_media_url' => 'https://www.facebook.com/mtecsoft',
            'password' => bcrypt('12345678'),
            'type' => '1', //candidate
        ]);
    }
}
