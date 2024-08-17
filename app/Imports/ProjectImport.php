<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class ProjectImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
       $test = $collection->each(function ($row) {
           if($row === null){
               return false;
           }
            return $row;
        });
       dd(123);
    }
}
