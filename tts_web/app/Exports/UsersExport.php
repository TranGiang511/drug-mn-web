<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UsersExport implements FromQuery, WithHeadings, WithMapping
{
    protected $query;
    protected $search;

    public function __construct($query, $search)
    {
        $this->query = $query;
        $this->search = $search;
    }

    public function query()
    {
        return $this->query;
    }

    public function headings(): array
    {
        return [
            'Id', 
            'Name', 
            'Email',
            'Phone',
            'Password',
            'Role',
        ];
    }

    public function map($user): array
    {
        $user->password = '';
        return [
            $user->id,
            $user->name,
            $user->email,
            $user->phone,
            $user->password,
            $user->role,
        ];
    }
}
