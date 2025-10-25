<?php

namespace App\Exports;

use App\Models\colonia;
use App\Models\persona;
use App\Models\personas;
use App\Models\seccion;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class estructuraAfiliadosExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{
   public function collection()
    {
        return persona::where('RolEstructura', '!=', null)
        ->orWhere('rolEstructuraTemporal', '!=', null)
        ->orWhere(function($query) {
            $query->whereNotNull('funcion_en_campania')
                ->where('funcion_en_campania', '!=', '');
        })
        ->select(
            'id',
            DB::raw("CONCAT(nombres, ' ', apellido_paterno, ' ', apellido_materno) AS nombre_completo"),
            'Telefono_celular',
            'correo',
            'RolEstructura',
            'rolNumero',
            'funcion_en_campania',
            'rolEstructuraTemporal',
            'rolNumeroTemporal'
        )
        ->get();

    }
    public function headings(): array{
        return [
            'Consecutivo',
            'Nombre completo',
            'Numero de telefono',
            'Correo electrónico',
            'Rol',
            'Encargado de',
            'Funcion Asignada',
            'Rol secundario',
            'Encargado de',
        ];
    }
    public function styles(Worksheet $sheet)
        {
            // 🔹 Estilo para la fila de encabezados
            $sheet->getStyle('A1:I1')->applyFromArray([
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
            $sheet->getStyle('A2:I' . ($sheet->getHighestRow()))->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

            // 🔹 Bordes alrededor de toda la tabla
            $sheet->getStyle('A1:I' . $sheet->getHighestRow())->applyFromArray([
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
