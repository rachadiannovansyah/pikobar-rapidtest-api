<?php

use App\Enums\UserStatus;
use App\Entities\User;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Admin
        $user = User::create(
            [
                'name'     => 'Administrator',
                'email'    => 'admin@myapp.id',
                'password' => bcrypt('123456'),
                'status'   => UserStatus::ACTIVE(),
            ]
        );

        // $user->assignRole('administrator');

        $user = User::create(
            [
                'name'          => 'Operator Dinas Kesehatan Provinsi',
                'email'         => 'dinkesprov@myapp.id',
                'password'      => bcrypt('123456'),
                'province_code' => '32',
                'status'        => UserStatus::ACTIVE(),
            ]
        );

        // $user->assignRole('dinkes-provinsi-operator');

        $user = User::create(
            [
                'name'          => 'Operator Dinas Kesehatan Kota Bandung',
                'email'         => 'dinkeskotabandung@myapp.id',
                'password'      => bcrypt('123456'),
                'province_code' => '32',
                'city_code'     => '32.73',
                'status'        => UserStatus::ACTIVE(),
            ]
        );

        // $user->assignRole('dinkes-kabkota-operator');

        $user = User::create(
            [
                'name'          => 'Operator Dinas Kesehatan Kota Depok',
                'email'         => 'dinkeskotadepok@myapp.id',
                'password'      => bcrypt('123456'),
                'province_code' => '32',
                'city_code'     => '32.76',
                'status'        => UserStatus::ACTIVE(),
            ]
        );

        // $user->assignRole('dinkes-kabkota-operator');

        $user = User::create(
            [
                'name'          => 'Operator Dinas Kesehatan Kota Bekasi',
                'email'         => 'dinkeskotabekasi@myapp.id',
                'password'      => bcrypt('123456'),
                'province_code' => '32',
                'city_code'     => '32.75',
                'status'        => UserStatus::ACTIVE(),
            ]
        );

        // $user->assignRole('dinkes-kabkota-operator');

        $user = User::create(
            [
                'name'          => 'Operator Rumah Sakit Hasan Sadikin',
                'email'         => 'rshs@myapp.id',
                'password'      => bcrypt('123456'),
                'province_code' => '32',
                'city_code'     => '32.73',
                'status'        => UserStatus::ACTIVE(),
            ]
        );

        // $user->assignRole('rumahsakit-operator');

        $user = User::create(
            [
                'name'          => 'Operator Rumah Sakit Umum Daerah Al Ihsan Provinsi Jawa Barat',
                'email'         => 'rsalihsan@myapp.id',
                'password'      => bcrypt('123456'),
                'province_code' => '32',
                'city_code'     => '32.73',
                'status'        => UserStatus::ACTIVE(),
            ]
        );

        // $user->assignRole('rumahsakit-operator');

        $user = User::create(
            [
                'name'          => 'Operator Puskesmas 1',
                'email'         => 'puskesmas1@myapp.id',
                'password'      => bcrypt('123456'),
                'status'        => UserStatus::ACTIVE(),
            ]
        );

        // $user->assignRole('puskesmas-operator');

        $user = User::create(
            [
                'name'          => 'Operator Puskesmas 2',
                'email'         => 'puskesmas2@myapp.id',
                'password'      => bcrypt('123456'),
                'status'        => UserStatus::ACTIVE(),
            ]
        );

        // $user->assignRole('puskesmas-operator');

        $user = User::create(
            [
                'name'          => 'Operator Puskesmas 3',
                'email'         => 'puskesmas3@myapp.id',
                'password'      => bcrypt('123456'),
                'status'        => UserStatus::ACTIVE(),
            ]
        );

        // $user->assignRole('puskesmas-operator');

        $user = User::create(
            [
                'name'          => 'Operator Puskesmas 4',
                'email'         => 'puskesmas4@myapp.id',
                'password'      => bcrypt('123456'),
                'status'        => UserStatus::ACTIVE(),
            ]
        );

        // $user->assignRole('puskesmas-operator');
    }
}
