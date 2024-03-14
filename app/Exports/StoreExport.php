<?php

namespace App\Exports;

use App\g_c_lists_devp;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;


class StoreExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return g_c_lists_devp::
        leftJoin('egc_value_types', 'egc_value_types.id', 'g_c_lists_devps.egc_value_id')
        ->select(
            'claimed_by',
            'claimed_email',
            'egc_value_types.value',
            'store_concept',
            'invoice_number',
            'pos_terminal',
            'qr_reference_number',
        )->get();
    }

    public function headings(): array
    {
        return ['NAME', 'EMAIL', 'GC VALUE', 'STORE CONCEPT', 'INVOICE NUMBER', 'POS TERMINAL', 'REFERENCE CODE'];
    }
}
