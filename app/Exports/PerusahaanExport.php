<?php

namespace App\Exports;

use App\Models\Perusahaan;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PerusahaanExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithTitle
{
    protected Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function query()
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
            ->latest();
    }

    public function title(): string
    {
        $status = $this->request->status;
        return $status ? 'Perusahaan - ' . $status : 'Perusahaan - Semua';
    }

    public function headings(): array
    {
        return ['No', 'ID', 'Nama Perusahaan', 'Email HR', 'Nama HR', 'Status', 'Terdaftar'];
    }

    public function map($p): array
    {
        static $no = 0;
        $no++;
        return [
            $no,
            $p->id,
            $p->nama_perusahaan ?? '-',
            $p->user->email ?? '-',
            $p->user->nama_lengkap ?? '-',
            $p->status ?? '-',
            $p->created_at ? $p->created_at->format('d/m/Y H:i') : '-',
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
