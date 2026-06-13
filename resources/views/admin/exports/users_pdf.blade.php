<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Data Pengguna - JOBLYNX</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #333;
            margin: 20px;
        }

        /* HEADER */
        .header {
            border-bottom: 3px solid #2d7f6a;
            padding-bottom: 12px;
            margin-bottom: 15px;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-table td {
            border: none;
            vertical-align: top;
        }

        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #1a4450;
            margin-bottom: 4px;
        }

        .company-desc {
            font-size: 11px;
            color: #666;
        }

        .company-contact {
            font-size: 10px;
            color: #666;
            margin-top: 3px;
        }

        .report-title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            color: #1a4450;
            text-transform: uppercase;
            margin-bottom: 15px;
        }

        /* SUMMARY */
        .summary {
            background: #f8fafc;
            border: 1px solid #dbe5e1;
            border-left: 5px solid #2d7f6a;
            padding: 10px 12px;
            margin-bottom: 18px;
            font-size: 10px;
        }

        .summary strong {
            color: #1a4450;
        }

        /* TABLE */
        table.data-table {
            width: 100%;
            border-collapse: collapse;
        }

        table.data-table thead tr {
            background: #2d7f6a;
            color: white;
        }

        table.data-table th {
            padding: 9px;
            border: 1px solid #d1d5db;
            font-size: 10px;
            text-transform: uppercase;
            text-align: left;
        }

        table.data-table td {
            padding: 8px;
            border: 1px solid #e5e7eb;
            font-size: 10px;
            vertical-align: top;
        }

        table.data-table tbody tr:nth-child(even) {
            background: #f7fbfa;
        }

        .text-center {
            text-align: center;
        }

        /* ROLE BADGE */
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 20px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .badge-admin {
            background: #fee2e2;
            color: #991b1b;
        }

        .badge-hr {
            background: #dcfce7;
            color: #166534;
        }

        .badge-user {
            background: #dbeafe;
            color: #1d4ed8;
        }

        /* FOOTER */
        .footer {
            margin-top: 20px;
            border-top: 1px solid #d1d5db;
            padding-top: 8px;
            font-size: 9px;
            color: #6b7280;
        }

        .footer-left {
            float: left;
        }

        .footer-right {
            float: right;
        }

        .clearfix {
            clear: both;
        }
    </style>
</head>
<body>

    <!-- HEADER -->
    <div class="header">
        <table class="header-table">
            <tr>
                <td width="100%">
                    <div class="company-name">
                        JOBLYNX
                    </div>

                    <div class="company-desc">
                        Sistem Informasi Karir, Kompetisi, Volunteer,
                        Penelitian dan Magang
                    </div>

                    <div class="company-contact">
                        Email: admin@joblynx.id |
                        Website: www.joblynx.id
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <!-- JUDUL LAPORAN -->
    <div class="report-title">
        LAPORAN DATA PENGGUNA
    </div>

    <!-- SUMMARY -->
    <div class="summary">
        <strong>Total Pengguna :</strong>
        {{ $users->count() }}

        &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;

        <strong>Filter Role :</strong>
        {{ $exportMeta['filter_role'] ?? 'Semua Role' }}

        &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;

        <strong>Kata Kunci :</strong>
        {{ $exportMeta['filter_search'] ?? '-' }}

        &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;

        <strong>Tanggal Export :</strong>
        {{ $exportMeta['tanggal'] ?? now()->translatedFormat('d F Y') }}
    </div>

    <!-- TABEL -->
    <table class="data-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="7%">ID</th>
                <th width="28%">Nama Lengkap</th>
                <th width="30%">Email</th>
                <th width="10%">Role</th>
                <th width="20%">Registrasi</th>
            </tr>
        </thead>

        <tbody>
            @forelse($users as $i => $user)
                <tr>
                    <td class="text-center">
                        {{ $i + 1 }}
                    </td>

                    <td class="text-center">
                        #{{ $user->id }}
                    </td>

                    <td>
                        {{ $user->nama_lengkap ?? '-' }}
                    </td>

                    <td>
                        {{ $user->email }}
                    </td>

                    <td class="text-center">
                        <span class="badge badge-{{ strtolower($user->role) }}">
                            {{ strtoupper($user->role) }}
                        </span>
                    </td>

                    <td class="text-center">
                        @if($user->created_at)
                            {{ $user->created_at->format('d/m/Y') }}
                            <br>
                            {{ $user->created_at->format('H:i:s') }}
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6"
                        class="text-center"
                        style="padding:20px;color:#9ca3af;">
                        Tidak ada data pengguna
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- FOOTER -->
    <div class="footer">
        <div class="footer-left">
            JOBLYNX © {{ date('Y') }}
        </div>

        <div class="footer-right">
            Dicetak pada {{ now()->format('d/m/Y H:i:s') }} WIB
        </div>

        <div class="clearfix"></div>
    </div>

</body>
</html>