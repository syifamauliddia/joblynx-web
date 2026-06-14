<?php

namespace App\Exports;

use App\Models\Perusahaan;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class PerusahaanExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
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
        $status = $this->request->status;

        return Perusahaan::with('user')
            ->when($search, function ($q) use ($search) {
                $q->where('nama_perusahaan', 'like', "%$search%");
            })
            ->when($status, function ($q) use ($status) {
                $q->where('status', $status);
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
            'Nama Perusahaan',
            'Email Akun',
            'Website',
            'Status',
            'Tanggal Registrasi',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | MAPPING DATA PER BARIS
    |--------------------------------------------------------------------------
    */
    public function map($p): array
    {
        return [
            $this->no++,
            $p->id,
            $p->nama_perusahaan ?? '-',
            $p->user->email ?? '-',
            $p->website ?? '-',
            $p->status ?? '-',
            $p->created_at ? $p->created_at->format('d/m/Y H:i') : '-',
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
