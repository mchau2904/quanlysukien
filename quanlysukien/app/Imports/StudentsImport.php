<?php
// app/Imports/StudentsImport.php

namespace App\Imports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Row;

class StudentsImport implements OnEachRow, WithHeadingRow, SkipsEmptyRows
{
    protected int $total = 0;
    protected int $inserted = 0;
    protected int $updated = 0;
    protected int $skipped = 0;
    protected array $errors = [];

    public function onRow(Row $row)
    {
        $this->total++;

        // Lấy dữ liệu, loại bỏ khoảng trắng
        $raw = $row->toArray();
        $r = [];
        foreach ($raw as $k => $v) {
            $key = is_string($k) ? trim($k) : $k;
            $val = is_string($v) ? trim($v) : (is_null($v) ? null : (string)$v);
            $r[$key] = $val;
        }

        // Map các cột (đảm bảo đọc đúng tên cột trong file Excel)
        $username   = $r['masv']       ?? $r['MaSv']       ?? $r['MASV']       ?? null;
        $full_name  = $r['hoten']      ?? $r['HoTen']      ?? $r['HOTEN']      ?? null;
        $email      = $r['email']      ?? $r['Email']      ?? $r['EMAIL']      ?? null;
        $phone      = $r['sdt']        ?? $r['phone']      ?? $r['Phone']      ?? $r['SDT'] ?? null;
        $class      = $r['lop']        ?? $r['Lop']        ?? $r['LOP']        ?? null;
        $faculty    = $r['khoa']       ?? $r['Khoa']       ?? $r['KHOA']       ?? null;
        $gender     = $r['gioitinh']   ?? $r['GioiTinh']   ?? $r['GIOITINH']   ?? null; // ✅ Thêm cột giới tính

        // Nếu cả hàng trống → bỏ qua
        if (!$username && !$full_name && !$email && !$phone && !$class && !$faculty && !$gender) {
            $this->skipped++;
            return;
        }

        // Kiểm tra dữ liệu bắt buộc
        if (!$username || !$full_name) {
            $this->skipped++;
            $this->errors[] = "Dòng {$row->getIndex()}: thiếu 'username' hoặc 'full_name'.";
            return;
        }

        // Chuẩn hóa giới tính
        if ($gender !== null) {
            $gender = strtolower($gender);
            if (in_array($gender, ['nam', 'male', 'm'])) {
                $gender = 'Nam';
            } elseif (in_array($gender, ['nữ', 'nu', 'female', 'f'])) {
                $gender = 'Nữ';
            } else {
                $gender = ucfirst($gender);
            }
        }

        // Chuẩn hóa số điện thoại
        if ($phone !== null) {
            $phone = preg_replace('/\s+/', '', (string)$phone);
        }

        // Kiểm tra tồn tại
        $exists = DB::table('users')->where('username', $username)->exists();

        try {
            if ($exists) {
                // Cập nhật
                DB::table('users')->where('username', $username)->update([
                    'full_name'  => $full_name,
                    'email'      => $email ?: null,
                    'phone'      => $phone ?: null,
                    'class'      => $class ?: null,
                    'faculty'    => $faculty ?: null,
                    'gender'     => $gender ?: null, // ✅ cập nhật giới tính
                    // 'updated_at' => now(),
                ]);
                $this->updated++;
            } else {
                // Thêm mới
                DB::table('users')->insert([
                    'username'   => $username,
                    'password'   => sha1('12345678'),
                    'full_name'  => $full_name,
                    'email'      => $email ?: null,
                    'phone'      => $phone ?: null,
                    'class'      => $class ?: null,
                    'faculty'    => $faculty ?: null,
                    'gender'     => $gender ?: null, // ✅ thêm giới tính khi insert
                    'role'       => 'student',
                    'created_at' => now(),
                ]);
                $this->inserted++;
            }
        } catch (\Throwable $e) {
            $this->skipped++;
            $this->errors[] = "Dòng {$row->getIndex()} (username={$username}): {$e->getMessage()}";
        }
    }

    public function getTotal()     { return $this->total; }
    public function getInserted()  { return $this->inserted; }
    public function getUpdated()   { return $this->updated; }
    public function getSkipped()   { return $this->skipped; }
    public function getErrors()    { return $this->errors; }
}
