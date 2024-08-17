<?php

namespace App\Imports;

use App\Models\Project;
use App\Models\Type;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class ProjectImport implements ToCollection
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection): array
    {

        $typesMap = self::getTypesMap();

        $col = $collection->map(function ($row) use ($typesMap) {

            if ($row[0] === 'Тип') {
                return false;
            }
            $col = [
                'type_id' => self::getTypeId($typesMap, $row[0]),
                'title' => $row[1],
                'created_at_time' => Date::excelToDateTimeObject($row[2])->format('Y-m-d'),
                'contracted_at' => Date::excelToDateTimeObject($row[4])->format('Y-m-d'),
                'deadline' => Date::excelToDateTimeObject($row[9])->format('Y-m-d'),
                'is_chain' => $row[3] == 'да',
                'is_on_time' => $row[8] == 'да',
                'has_outsource' => $row[5] == 'да',
                'has_investors' => $row[6] == 'да',
                'worker_count' => $row[4],
                'service_count' => $row[14],
                'payment_first_step' => $row[9],
                'payment_second_step' => $row[10],
                'payment_third_step' => $row[11],
                'payment_forth_step' => $row[12],
                'comment' => $row[15],
                'effective_value' => (float) $row[16],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];
           // dd($col);
            Project::create($col);
            return $col;
        });

        return ['true'];
    }

    private static function getTypesMap(): array
    {
        $types = Type::all();

        return $types->map(function ($type) {
            return [$type->title => $type->id];
        })->collapse()->toArray();
    }

    private static function getTypeId($map, $title)
    {
        return $map[$title] ?? Type::create(['title' => $title])->id;
    }
}
