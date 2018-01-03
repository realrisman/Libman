<?php

use Illuminate\Database\Seeder;
use App\Role;
use App\User;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create Role Admin
        $adminRole = new Role();
        $adminRole->name = "admin";
        $adminRole->display_name = "Admin";
        $adminRole->save();

        // Create Role Member
        $memberRole = new Role();
        $memberRole->name = "member";
        $memberRole->display_name = "Member";
        $memberRole->save();

        // Create Sample Admin
        $admin = new User();
        $admin->name = "Administrator";
        $admin->email = "admin@gmail.com";
        $admin->password = bcrypt('secret');
        $admin->save();
        $admin->attachRole($adminRole);

        // Create Sample Member
        $member = new User();
        $member->name = "Sample Member";
        $member->email = "member@gmail.com";
        $member->password = bcrypt('secret');
        $member->save();
        $member->attachRole($memberRole);
    }
}
