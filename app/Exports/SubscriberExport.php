<?php

namespace App\Exports;

use App\GCListsDevpsCustomer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SubscriberExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return GCListsDevpsCustomer::
            whereNotNull('is_subscribe')
            ->select(
                'name',
                'store_concept',
                'email',
                'phone'
            )->get();
    }

    public function headings(): array
    {
        return ['NAME', 'STORE', 'EMAIL', 'PHONE'];
    
    }
}
