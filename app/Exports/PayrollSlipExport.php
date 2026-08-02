<?php

namespace App\Exports;

use App\Models\Payrolls;
use App\Services\SettingsService;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class PayrollSlipExport implements FromCollection, WithEvents, ShouldAutoSize
{
    public function __construct(
        protected Payrolls $payroll,
        protected array $settings = []
    ) {}

    public function collection()
    {
        return collect([]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $sheet = $event->sheet->getDelegate();

                $currency = data_get($this->settings, 'payroll.currency', 'IDR');

                $companyName = data_get($this->settings, 'general.company_name', 'Company');

                $companyAddress = data_get($this->settings, 'general.company_address');

                $companyPhone = data_get($this->settings, 'general.company_phone');

                $companyEmail = data_get($this->settings, 'general.company_email');

                $companyWebsite = data_get($this->settings, 'general.company_website');

                $city = data_get($this->settings, 'general.company_city', 'Tangerang');

                $preparedBy = data_get($this->settings, 'payroll.prepared_by');

                $approvedBy = data_get($this->settings, 'payroll.approved_by');

                $receivedBy = data_get(
                    $this->settings,
                    'payroll.received_by',
                    $this->payroll->user?->name
                );

                /*
                |--------------------------------------------------------------------------
                | COLUMN WIDTH
                |--------------------------------------------------------------------------
                */

                foreach (range('A', 'F') as $column) {
                    $sheet->getColumnDimension($column)->setWidth(22);
                }

                /*
                |--------------------------------------------------------------------------
                | LOGO
                |--------------------------------------------------------------------------
                */

                $logo = data_get($this->settings, 'general.logo');

                if ($logo && Storage::disk('public')->exists($logo)) {

                    $drawing = new Drawing();

                    $drawing->setName('Logo');

                    $drawing->setPath(
                        Storage::disk('public')->path($logo)
                    );

                    $drawing->setHeight(70);

                    $drawing->setCoordinates('A1');

                    $drawing->setWorksheet($sheet);
                }

                /*
                |--------------------------------------------------------------------------
                | HEADER
                |--------------------------------------------------------------------------
                */

                $sheet->mergeCells('B1:F1');
                $sheet->mergeCells('B2:F2');
                $sheet->mergeCells('B3:F3');

                $sheet->setCellValue('B1', $companyName);

                $sheet->setCellValue(
                    'B2',
                    $companyAddress
                );

                $sheet->setCellValue(
                    'B3',
                    "{$companyPhone} | {$companyEmail} | {$companyWebsite}"
                );

                $sheet->getStyle('B1')->getFont()
                    ->setBold(true)
                    ->setSize(18);

                $sheet->getStyle('B2:B3')
                    ->getFont()
                    ->setSize(10);

                /*
                |--------------------------------------------------------------------------
                | TITLE
                |--------------------------------------------------------------------------
                */

                $sheet->mergeCells('A5:F5');

                $sheet->setCellValue(
                    'A5',
                    'SLIP GAJI PEGAWAI'
                );

                $sheet->getStyle('A5')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 16,
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                ]);

                /*
                |--------------------------------------------------------------------------
                | META
                |--------------------------------------------------------------------------
                */

                $row = 7;

                $sheet->setCellValue("A{$row}", "No. Slip");
                $sheet->setCellValue("B{$row}", $this->payroll->slip_number ?? ('SG/' . now()->format('Y/m') . '/' . str_pad($this->payroll->id ?? 0, 5, '0', STR_PAD_LEFT)));

                $sheet->setCellValue("D{$row}", "Tanggal Cetak");
                $sheet->setCellValue("E{$row}", now()->translatedFormat('d F Y'));

                $row += 2;

                /*
                |--------------------------------------------------------------------------
                | EMPLOYEE
                |--------------------------------------------------------------------------
                */

                $sheet->mergeCells("A{$row}:F{$row}");

                $sheet->setCellValue(
                    "A{$row}",
                    "INFORMASI PEGAWAI"
                );

                $sheet->getStyle("A{$row}:F{$row}")
                    ->applyFromArray([
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'color' => ['rgb' => '1F2D3D'],
                        ],
                        'font' => [
                            'bold' => true,
                            'color' => ['rgb' => 'FFFFFF'],
                        ],
                    ]);

                $row++;

                $sheet->setCellValue("A{$row}", "Nama Pegawai");
                $sheet->setCellValue("B{$row}", $this->payroll->user?->name ?? '-');

                $sheet->setCellValue("D{$row}", "NIP / ID Pegawai");
                $sheet->setCellValue("E{$row}", $this->payroll->user?->nip ?? '-');

                $row++;

                $sheet->setCellValue("A{$row}", "Jabatan");
                $sheet->setCellValue("B{$row}", $this->payroll->user?->position ?? $this->payroll->position ?? '-');

                $sheet->setCellValue("D{$row}", "Departemen");
                $sheet->setCellValue("E{$row}", $this->payroll->user?->department ?? '-');

                /*
                |--------------------------------------------------------------------------
                | SALARY
                |--------------------------------------------------------------------------
                */

                $row += 3;

                $sheet->mergeCells("A{$row}:F{$row}");

                $sheet->setCellValue(
                    "A{$row}",
                    "RINCIAN GAJI"
                );

                $sheet->getStyle("A{$row}:F{$row}")
                    ->applyFromArray([
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'color' => ['rgb' => '1F2D3D'],
                        ],
                        'font' => [
                            'bold' => true,
                            'color' => ['rgb' => 'FFFFFF'],
                        ],
                    ]);

                $row++;

                $sheet->setCellValue("A{$row}", "Komponen");
                $sheet->setCellValue("E{$row}", "Nominal");

                $row++;

                $sheet->setCellValue("A{$row}", "Gaji Pokok");
                $sheet->setCellValue("E{$row}", $this->payroll->basic_salary ?? $this->payroll->basic_pay ?? 0);

                $row++;

                $sheet->setCellValue("A{$row}", "Tunjangan & Insentif Lainnya");
                $sheet->setCellValue("E{$row}", $this->payroll->addition_total ?? $this->payroll->additions ?? 0);

                $row++;

                $sheet->setCellValue("A{$row}", 'Tunjangan Lembur (' . ($this->payroll->attendanceReport?->total_overtime ?? 0) . ' jam)');
                $sheet->setCellValue("E{$row}", $this->payroll->overtime_pay ?? 0);

                $row++;

                $sheet->setCellValue("A{$row}", "Jaminan Kesehatan & Ketenagakerjaan (BPJS)");
                $sheet->setCellValue("E{$row}", -($this->payroll->bpjs_deduction ?? 0));

                $row++;

                $sheet->setCellValue("A{$row}", "Pajak Penghasilan (PPh 21)");
                $sheet->setCellValue("E{$row}", -($this->payroll->tax_deduction ?? 0));

                $row++;

                $sheet->setCellValue("A{$row}", "Potongan Lainnya");
                $sheet->setCellValue("E{$row}", -($this->payroll->deduction_total ?? $this->payroll->deductions ?? 0));

                $row++;

                $sheet->setCellValue("A{$row}", "GAJI DITERIMA (THP)");

                $sheet->setCellValue("E{$row}", $this->payroll->net_pay ?? 0);

                $sheet->getStyle("A{$row}:F{$row}")
                    ->applyFromArray([
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'color' => ['rgb' => '1F2D3D'],
                        ],
                        'font' => [
                            'bold' => true,
                            'color' => ['rgb' => 'FFFFFF'],
                        ],
                    ]);

                /*
                |--------------------------------------------------------------------------
                | FORMAT MONEY
                |--------------------------------------------------------------------------
                */

                $sheet->getStyle("E16:E30")
                    ->getNumberFormat()
                    ->setFormatCode('"'.$currency.'" #,##0');

                /*
                |--------------------------------------------------------------------------
                | BORDER
                |--------------------------------------------------------------------------
                */

                $sheet->getStyle("A10:F30")
                    ->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                            ],
                        ],
                    ]);

                /*
                |--------------------------------------------------------------------------
                | SIGNATURE
                |--------------------------------------------------------------------------
                */

                $row += 5;

                $sheet->mergeCells("A{$row}:B{$row}");
                $sheet->mergeCells("C{$row}:D{$row}");
                $sheet->mergeCells("E{$row}:F{$row}");

                $sheet->setCellValue(
                    "A{$row}",
                    "{$city}, ".now()->format('d F Y')
                );

                $row++;

                $sheet->setCellValue("A{$row}", "Prepared By");
                $sheet->setCellValue("C{$row}", "Approved By");
                $sheet->setCellValue("E{$row}", "Received By");

                $row += 5;

                $sheet->setCellValue("A{$row}", $preparedBy);
                $sheet->setCellValue("C{$row}", $approvedBy);
                $sheet->setCellValue("E{$row}", $receivedBy);

                $sheet->getStyle("A{$row}:F{$row}")
                    ->getFont()
                    ->setBold(true);
            },
        ];
    }
}