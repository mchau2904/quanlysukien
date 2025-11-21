<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSampleSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $rows = [
            [
                'user_id'    => 1,
                'username'   => 'admin',
                'password'   => sha1('12345678'),
                'full_name'  => 'System Admin',
                'dob'        => '1990-01-01',
                'gender'     => 'Nam', // ENUM: Nam | Nữ | Khác
                'phone'      => '0900000001',
                'email'      => 'admin@example.com',
                'class'      => null,
                'faculty'    => 'IT',
                'role'       => 'admin', // ENUM: admin | student
                'created_at' => $now,
            ],
            [
                'user_id'    => 2,
                'username'   => 'sv001',
                'password'   => sha1('12345678'),
                'full_name'  => 'Nguyễn Văn A',
                'dob'        => '2003-05-20',
                'gender'     => 'Nam',
                'phone'      => '0900000002',
                'email'      => 'sv001@example.com',
                'class'      => 'K17-CNTT1',
                'faculty'    => 'CNTT',
                'role'       => 'student',
                'created_at' => $now,
            ],
            [
                'user_id'    => 3,
                'username'   => 'sv002',
                'password'   => sha1('12345678'),
                'full_name'  => 'Trần Thị B',
                'dob'        => '2003-09-12',
                'gender'     => 'Nữ',
                'phone'      => '0900000003',
                'email'      => 'sv002@example.com',
                'class'      => 'K17-CNTT2',
                'faculty'    => 'CNTT',
                'role'       => 'student',
                'created_at' => $now,
            ],
            [
                'user_id'    => 4,
                'username'   => 'sv003',
                'password'   => sha1('12345678'),
                'full_name'  => 'Lê Văn C',
                'dob'        => '2002-12-01',
                'gender'     => 'Nam',
                'phone'      => '0900000004',
                'email'      => 'sv003@example.com',
                'class'      => 'K16-QTKD1',
                'faculty'    => 'QTKD',
                'role'       => 'student',
                'created_at' => $now,
            ],
            [
                'user_id'    => 5,
                'username'   => 'sv004',
                'password'   => sha1('12345678'),
                'full_name'  => 'Phạm Thu D',
                'dob'        => '2004-02-15',
                'gender'     => 'Nữ',
                'phone'      => '0900000005',
                'email'      => 'sv004@example.com',
                'class'      => 'K18-TCKT1',
                'faculty'    => 'Tài chính - Kế toán',
                'role'       => 'student',
                'created_at' => $now,
            ],
        ];

        // upsert theo khóa duy nhất user_id (hoặc bạn có thể dùng 'username')
        DB::table('users')->upsert(
            $rows,
            ['user_id'], // unique key
            ['username', 'password', 'full_name', 'dob', 'gender', 'phone', 'email', 'class', 'faculty', 'role', 'created_at']
        );
    }
}
