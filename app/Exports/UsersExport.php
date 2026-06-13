<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class UsersExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithTitle
{
    protected Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function query()
    {
        $search = $this->request->search;
        $role   = $this->request->role;

        return User::whereIn('role', ['user', 'hr'])
            ->when($search, function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('email', 'like', "%$search%")
                        ->orWhere('nama_lengkap', 'like', "%$search%");
                });
            })
            ->when($role, function ($q) use ($role) {
                $q->where('role', $role);
            })
            ->latest();
    }

    public function title(): string
    {
        $role = $this->request->role;
        return $role ? 'Users - ' . strtoupper($role) : 'Users - Semua';
    }

    public function headings(): array
    {
        return ['No', 'ID', 'Nama Lengkap', 'Email', 'Role', 'Tanggal Registrasi'];
    }

    public function map($user): array
    {
        static $no = 0;
        $no++;
        return [
            $no,
            $user->id,
            $user->nama_lengkap ?? '-',
            $user->email,
            strtoupper($user->role),
            $user->created_at ? $user->created_at->format('d/m/Y H:i') : '-',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font'      => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill'      => ['fillType' => 'solid', 'startColor' => ['argb' => 'FF2d7f6a']],
                'alignment' => ['horizontal' => 'center'],
            ],
        ];
    }
}
