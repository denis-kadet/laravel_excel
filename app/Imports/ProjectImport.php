<?php

namespace App\Imports;

use App\Factory\ProjectFactory;
use App\Models\Project;
use App\Models\Type;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Validators\Failure;

class ProjectImport implements ToCollection, WithHeadingRow, WithValidation, SkipsOnFailure
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection): Collection
    {
        return $collection->map(function ($row) {

            if (!isset($row['naimenovanie'])) {
                return false;
            }
            // Данный код каждый раз делает запрос в таблицу Type - с точки зрения производительности хуже
            // но при первом добавлении летит только что не повторяется
            $typeId = Type::firstOrNew(['title' => $row['tip']]);
            $typeId->save();

            $projectFactory = ProjectFactory::make($row, $typeId);


            return Project::updateOrCreate([
                'type_id' => $projectFactory->getValues()['type_id'],
                'title' => $projectFactory->getValues()['title'],
                'created_at_time' => $projectFactory->getValues()['created_at_time'],
                'contracted_at' => $projectFactory->getValues()['contracted_at'],
            ], $projectFactory->getValues());

        });
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

    public function rules(): array
    {
        // TODO: Implement rules() method.
    }

    public function onFailure(Failure ...$failures)
    {
        // TODO: Implement onFailure() method.
    }
}
