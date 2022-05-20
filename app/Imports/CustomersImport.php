<?php

namespace App\Imports;

use App\Helper\Helper;
use App\Models\Customer;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CustomersImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Customer([
            'customer_code' => Str::orderedUuid(), 
            'name' => $row['name'], 
            'email' => $row['email'], 
            'address' => $row['address'],
            'gender' => $row['gender'], 
            'type_of_customer_id' => 1,
            'user_id' => Auth::user()->id ?? null,
        ]);
    }
}
