<?php

namespace App\Exports;

use App\GcListSummaryView;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class GcListExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return GcListSummaryView::select(
            'name',
            'phone',
            'email',
            'campaign_id',
            'gc_description',
            'gc_value',
            'invoice_number',
            'pos_terminal',
            'gclists',
        )->get();
    }

    public function headings(): array
    {
        return ['NAME', 'PHONE', 'EMAIL', 'CAMPAIGN ID', 'GC DESCRIPTION', 'GC VALUE', 'INVOICE NUMBER', 'POS TERMINAL', 'CAMPAIGN'];
    }
}
