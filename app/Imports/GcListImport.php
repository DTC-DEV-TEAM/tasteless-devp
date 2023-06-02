<?php

namespace App\Imports;

use App\GCList;
use App\QrCreation;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\Importable;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Mail;
use CRUDBooster;
use Illuminate\Support\Str;
use DB;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Validators\Failure;


class GcListImport implements 
    ToModel, 
    WithValidation, 
    WithHeadingRow,
    // SkipsOnFailure,
    WithBatchInserts,
    WithChunkReading
    // ShouldQueue
{

    use Importable;

    private $gc_information;


    public function __construct($gc_information)
    {
        $this->gc_information = $gc_information;
    }
    
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        
        do {
            $generated_qr_code = Str::random(10);
        } while (GCList::where('qr_reference_number', $generated_qr_code)->exists());

        $gcList = new GCList([
            'name' => $row['name'],
            'phone' => $row['phone'],
            'email' => $row['email'],
            'customer_reference_number' => $row['customer_reference_number'],
            'campaign_id' => $this->gc_information['campaign_id'],
            'email_template_id' => $this->gc_information['email_template_id'],
            'qr_reference_number' => $generated_qr_code
        ]);

        $gcList->save();
    }

    public function rules(): array{
        return [
            '*.name' => 'required',
            '*.email' => 'required|email',
            '*.phone' => 'required',
            '*.customer_reference_number' => 'required'
        ];
    }
    
    public function batchSize(): int
    {
        return 500; 
    }

    public function chunkSize(): int
    {   
        return 500;
    }
    
}
