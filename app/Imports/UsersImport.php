<?php

namespace App\Imports;

use App\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\{
    ToCollection,
    WithHeadingRow,
    WithChunkReading
};

class UsersImport implements ToCollection, WithHeadingRow, WithChunkReading
{
    public function collection(Collection $rows)
    {
        $data = [];

        foreach ($rows as $row) {

            $data[] = [
                'email'          => $row['email'], // unique key
                'name'           => $row['name'],
                'user_name'      => $row['user_name'],
                'password'       => $row['password'], // cast will hash
                'role'           => $row['role'],
                'status'         => $row['status'],
                'stripe_pk_id'      => $this->nullCheck($row['stripe_id']),
                'actual_plan'    => $row['actual_plan'],
                'card_brand'     => $this->nullCheck($row['card_brand']),
                'card_last_four' => $this->nullCheck($row['card_last_four']),
                'trial_ends_at'  => $this->nullCheck($row['trial_ends_at']),
                'remember_token' => $row['remember_token'],
                'parent_id' => $this->resolveParentId($row['parent_id']),
                'custom'         => $row['custom'],
                'created_at'     => now(),
                'updated_at'     => now(),
            ];
        }

        // 🚀 Single UPSERT per chunk
        User::upsert(
            $data,
            ['email'], // unique key
            [
                'name',
                'user_name',
                'password',
                'role',
                'status',
                'stripe_pk_id',
                'actual_plan',
                'card_brand',
                'card_last_four',
                'trial_ends_at',
                'remember_token',
                'parent_id',
                'custom',
                'updated_at'
            ]
        );
    }

    /**
     * Chunk size (performance tuning)
     */
    public function chunkSize(): int
    {
        return 500;
    }

    /**
     * Convert "NULL" string to actual null
     */
    private function nullCheck($value)
    {
        return ($value === 'NULL' || $value === '') ? null : $value;
    }

    private function resolveParentId($parentId)
    {
        if (!$parentId) {
            return null;
        }

        return User::where('id', $parentId)->exists()
            ? $parentId
            : null;
    }
}
