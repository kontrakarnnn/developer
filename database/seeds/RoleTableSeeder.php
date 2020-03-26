<?php

use Illuminate\Database\Seeder;
use App\role;
class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role_user =new role();
        $role_user->name ='User';
        $role_user->description ="A normal User";
        $role_user->save();

        $role_employee =new role();
        $role_employee->name ='Employee';
        $role_employee->description ="An Employee";
        $role_employee->save();

        $role_admin =new role();
        $role_admin->name ='Admin';
        $role_admin->description ="An Admin";
        $role_admin->save();
    }
}
