<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BillingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'inv_no' => $this->inv_no,
            'unit_no' => $this->unit->no_unit,
            'month' => $this->month,
            'year' => $this->year,
            'unit_id' => $this->unit_id,
            'name' => $this->name,
            's4_mbase_amt' => $this->s4_mbase_amt,
            's4_mtax_amt' => $this->s4_mtax_amt,
            'sd_mbase_amt' => $this->sd_mbase_amt,
            'service_charge' => $this->service_charge,
            'sinking_fund' => $this->sinking_fund,
            'electric_previous' => $this->electric_previous,
            'electric_current' => $this->electric_current,
            'electric_read' => $this->electric_read,
            'electric_fixed' => $this->electric_fixed,
            'electric_mbase' => $this->electric_mbase,
            'electric_administration' => $this->electric_administration,
            'electric_tax' => $this->electric_tax,
            'electric_total' => $this->electric_total,
            'mcb' => $this->mcb,
            'water_previous' => $this->water_previous,
            'water_current' => $this->water_current,
            'water_read' => $this->water_read,
            'water_fixed' => $this->water_fixed,
            'water_mbase' => $this->water_mbase,
            'water_administration' => $this->water_administration,
            'water_tax' => $this->water_tax,
            'water_total' => $this->water_total,
            'total' => $this->total,
            'tube' => $this->tube,
            'panin' => $this->panin,
            'bca' => $this->bca,
            'cimb' => $this->cimb,
            'mandiri' => $this->mandiri,
            'add_charge' => $this->add_charge,
            'previous_transaction' => $this->previous_transaction,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
