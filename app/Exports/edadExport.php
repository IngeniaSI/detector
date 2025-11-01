<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class edadExport implements FromCollection, WithTitle, WithHeadings,WithStyles, ShouldAutoSize
{

    public function collection()
    {
        $rolesEstructura = ['NO ESPECIFICÓ', '18-28', '29-39','40-49', '50-69','70-adelante'];

        return collect($rolesEstructura)->map(function ($rol) {
            return ['Rango Edad' => $rol];
        });
    }

      public function headings(): array
    {
        return [
            'Edad',
        ];
    }
    public function title(): string
    {
        return 'Catalogo Rango edad';
    }
    public function styles(Worksheet $sheet)
            {
                // 🔹 Estilo para la fila de encabezados
                $sheet->getStyle('A1:A1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF'],
                        'size' => 12,
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '4F81BD'], // azul
                    ],
                    'alignment' => [
                        'horizontal' => AlignmenT::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                // 🔹 Alinear todas las celdas al centro verticalmente
                $sheet->getStyle('A2:A' . ($sheet->getHighestRow()))->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

                // 🔹 Bordes alrededor de toda la tabla
                $sheet->getStyle('A1:A' . $sheet->getHighestRow())->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['rgb' => 'AAAAAA'],
                        ],
                    ],
                ]);

                return [];
            }
}
