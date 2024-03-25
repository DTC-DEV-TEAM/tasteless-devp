<?php

namespace App\Exports;

use App\GCList;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;


class CampaignExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return GCList::
            leftJoin('qr_creations', 'qr_creations.id', 'g_c_lists.campaign_id')
            ->select(
                'name',
                'phone',
                'email',
                'qr_creations.campaign_id',
                'qr_creations.gc_description',
                'qr_creations.gc_value',
                'invoice_number',
                'pos_terminal',
                'qr_reference_number',
                'redeem'
            )
            ->get();
    }

    public function headings(): array
    {
        return ['NAME', 'PHONE', 'EMAIL', 'CAMPAIGN ID', 'GC DESCRIPTION', 'GC VALUE', 'INVOICE NUMBER', 'POS TERMINAL', 'REFERENCE CODE', 'IS REDEEMED'];
    }
}
