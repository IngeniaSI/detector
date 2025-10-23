<?php

namespace App\Exports;

use App\Models\meta;
use App\Models\seccion;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class MetaExport implements FromCollection, WithHeadings, WithStyles, WithTitle, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return seccion::get(['id', 'distrito_local_id', 'poblacion', 'objetivo']);
    }

    public function headings(): array
    {
        return [
            'ID',
            'Distrito Local ID',
            'Población',
            'Objetivo',
        ];
    }
      public function title(): string
    {
        return 'Metas';
    }

    public function styles(Worksheet $sheet)
    {
        // 🔹 Estilo para la fila de encabezados
        $sheet->getStyle('A1:D1')->applyFromArray([
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
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // 🔹 Ajustar alturas y anchos de columna manualmente
        $sheet->getRowDimension(1)->setRowHeight(25);
        $sheet->getColumnDimension('A')->setWidth(15); // ID
        $sheet->getColumnDimension('B')->setWidth(30); // Nombre
        $sheet->getColumnDimension('C')->setWidth(40); // Correo
        $sheet->getColumnDimension('D')->setWidth(25); // Fecha

        // 🔹 Alinear todas las celdas al centro verticalmente
        $sheet->getStyle('A2:D' . ($sheet->getHighestRow()))->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

        // 🔹 Bordes alrededor de toda la tabla
        $sheet->getStyle('A1:D' . $sheet->getHighestRow())->applyFromArray([
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
