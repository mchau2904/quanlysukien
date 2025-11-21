<?php

namespace App\Imports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Row;

class AdminsImport implements OnEachRow, WithHeadingRow, SkipsEmptyRows
{
    protected int $total = 0;
    protected int $inserted = 0;
    protected int $updated = 0;
    protected int $skipped = 0;
    protected array $errors = [];

    public function onRow(Row $row)
    {
        $this->total++;

        $raw = $row->toArray();
        $r = [];
        foreach ($raw as $k => $v) {
            $key = is_string($k) ? trim($k) : $k;
            $val = is_string($v) ? trim($v) : (is_null($v) ? null : (string)$v);
            $r[$key] = $val;
        }

        // Map cột Excel → DB
        $username  = $r['ma_can_bo'] ?? $r['macanbo'] ?? $r['ma_cb'] ?? null;
        $full_name = $r['ho_ten'] ?? $r['hoten'] ?? null;
        $gender    = $r['gioi_tinh'] ?? $r['gioitinh'] ?? null;
        $phone     = $r['sdt'] ?? $r['so_dien_thoai'] ?? null;
        $email     = $r['email'] ?? null;
        $faculty   = $r['chuc_vu'] ?? $r['chucvu'] ?? null;


        // Nếu dòng trống toàn bộ → bỏ qua
        if (!$username && !$full_name && !$email && !$phone && !$faculty) {
            $this->skipped++;
            return;
        }

        // Thiếu mã hoặc tên → lỗi
        if (!$username || !$full_name) {
            $this->skipped++;
            $this->errors[] = "⚠️ Dòng {$row->getIndex()}: Thiếu Mã cán bộ hoặc Họ tên.";
            return;
        }

        $exists = DB::table('users')->where('username', $username)->exists();

        try {
            if ($exists) {
                DB::table('users')->where('username', $username)->update([
                    'full_name' => $full_name,
                    'gender'    => $gender ?: null,
                    'phone'     => $phone ?: null,
                    'email'     => $email ?: null,
                    'faculty'   => $faculty ?: null,
                ]);
                $this->updated++;
            } else {
                DB::table('users')->insert([
                    'username'   => $username,
                    'password'   => sha1('12345678'),
                    'full_name'  => $full_name,
                    'gender'     => $gender ?: null,
                    'phone'      => $phone ?: null,
                    'email'      => $email ?: null,
                    'faculty'    => $faculty ?: null,
                    'role'       => 'admin',
                    'created_at' => now(),
                ]);
                $this->inserted++;
            }
        } catch (\Throwable $e) {
            $this->skipped++;
            $this->errors[] = "❌ Dòng {$row->getIndex()} ({$username}): {$e->getMessage()}";
        }
    }

    public function getTotal()
    {
        return $this->total;
    }
    public function getInserted()
    {
        return $this->inserted;
    }
    public function getUpdated()
    {
        return $this->updated;
    }
    public function getSkipped()
    {
        return $this->skipped;
    }
    public function getErrors()
    {
        return $this->errors;
    }
}
