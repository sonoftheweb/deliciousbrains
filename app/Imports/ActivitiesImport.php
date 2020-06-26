<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class ActivitiesImport implements ToCollection
{
    /**
     * @var Collection
     */
    public $data;

    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        $this->data = $collection;
    }
}
