<?php

namespace App\Exports;

use App\Models\colonia;
use App\Models\persona;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class coloniasExport implements FromCollection, WithHeadings, ShouldAutoSize, WithTitle, WithStyles
{
 public function collection()
    {
        return colonia::select('id', 'nombre', 'codigo_postal')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nombre',
            'Código postal',
        ];
    }

    public function title(): string
    {
        return 'Catalogo Colonia';
    }
    public function styles(Worksheet $sheet)
            {
                // 🔹 Estilo para la fila de encabezados
                $sheet->getStyle('A1:C1')->applyFromArray([
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
                $sheet->getStyle('A2:C' . ($sheet->getHighestRow()))->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

                // 🔹 Bordes alrededor de toda la tabla
                $sheet->getStyle('A1:C' . $sheet->getHighestRow())->applyFromArray([
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
