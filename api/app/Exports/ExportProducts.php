<?php

namespace App\Exports;

// use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use App\Models\Product;

class ExportProducts implements FromQuery, WithHeadings, WithMapping, WithStrictNullComparison, WithStyles, WithColumnFormatting
{

    use Exportable;

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1 => [
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' =>  ['argb' => 'FFFFFF00'], 
                    'endColor' => ['argb' => 'FFFFFF00']
                         ]
                ],

        ];

    }

	public function headings(): array
	{

		return [
            
			'Producto',
			'Código',
			'Precio',
			'Stock',
			'Notificación de stock',
			'Local',

        ];

	}

    public function columnFormats(): array
    {
        return [
            'B' => DataType::TYPE_STRING,
        ];
    }

    public function map($product): array
    {

        return [
            $product->product,
            $product->code,
            $product->price,
            $product->stock,
            $product->stock_notification_below,
            $product->store->store,
        ];
    }

    public function query()
    {
        return Product::with(['store']);
    }

}
