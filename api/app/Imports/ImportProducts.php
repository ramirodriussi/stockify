<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\Store;
use App\Models\Progress_job;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithValidation;  
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Contracts\Queue\ShouldQueue;

use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterImport;

use Maatwebsite\Excel\Concerns\SkipsErrors;  
use Maatwebsite\Excel\Concerns\SkipsOnError;  
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Validators\Failure;

// use Throwable;

HeadingRowFormatter::default('none');


class ImportProducts implements ToModel, 
                                WithUpserts, 
                                WithHeadingRow, 
                                WithValidation, 
                                WithBatchInserts, 
                                WithChunkReading, 
                                ShouldQueue,
                                WithEvents,
                                SkipsOnFailure,
                                SkipsOnError
{

    use Importable, RegistersEventListeners, SkipsErrors;

    public $stores;

    public function __construct()
    {

        $stores = Store::select(['id','store'])->get();

        $this->stores = $stores;

    }

    protected function getStoreId($store)
    {

        $store = $this->stores->where('store', $store)->first();

        return $store->id;

    }

    public function model(array $row)
    {

        mb_internal_encoding('UTF-8');

        return new Product([
            'product' => ucfirst(ltrim($row['Producto'])),
            'code' => $row['Código'],
            'price' => $row['Precio'],
            'stock' => $row['Stock'],
            'stock_notification_below' => $row['Notificación de stock'],
            'store_id' => $this->getStoreId($row['Local']),
        ]);

    }

    /**
     * @return string|array
     */
    public function uniqueBy()
    {
        return 'code';
    }

    public function rules(): array 
    {

        return [
            '*.Precio' => 'required',
            '*.Código' => 'required',
        ];

    }

    public function batchSize(): int
    {
        return 1000;
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    public static function afterImport(AfterImport $event)
    {

       
        // $progress = Progress_job::find(1);
        // $progress->progress = 1;
        // $progress->save();

        // \Log::channel('single')->info('bbb');


    }

    public function onFailure(Failure ...$failures)
    {
        // Handle the exception how you'd like.
        \Log::channel('single')->info('failures');

        \Log::channel('single')->info($failures);

    }

    public function onError(\Throwable $e)
    {
        // Handle the exception how you'd like.
        \Log::channel('single')->info('errors');

        \Log::channel('single')->info($e);

    }


}




