<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class UsersExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $request;
    protected $no = 1;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /*
    |--------------------------------------------------------------------------
    | COLLECTION: Query data sesuai filter aktif
    |--------------------------------------------------------------------------
    */
    public function collection()
    {
        $search = $this->request->search;

        return User::when($search, function ($q) use ($search) {
                $q->where('email', 'like', "%$search%")
                  ->orWhere('nama_lengkap', 'like', "%$search%");
            })
            ->latest()
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | HEADER KOLOM
    |--------------------------------------------------------------------------
    */
    public function headings(): array
    {
        return [
            'No',
            'ID',
            'Nama Lengkap',
            'Email',
            'Role',
            'Tanggal Registrasi',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | MAPPING DATA PER BARIS
    |--------------------------------------------------------------------------
    */
    public function map($user): array
    {
        return [
            $this->no++,
            $user->id,
            $user->nama_lengkap ?? '-',
            $user->email,
            strtoupper($user->role),
            $user->created_at ? $user->created_at->format('d/m/Y H:i') : '-',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | STYLING: Header hijau sesuai tema JobLynx
    |--------------------------------------------------------------------------
    */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType'   => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '2d7f6a'],
                ],
            ],
        ];
    }
}
