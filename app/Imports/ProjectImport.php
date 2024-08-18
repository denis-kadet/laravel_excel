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
        return [
            'tip' => 'required|string',
            'naimenovanie' => 'required|string',
            'data_sozdaniia' => 'required|string',//integer убрать полсе ошибки
            'setevik' => 'nullable|string',
            'kolicestvo_ucastnikov' => 'nullable|string',//integer убрать полсе ошибки
            'nalicie_autsorsinga' => 'nullable|string',
            'nalicie_investorov' => 'nullable|string',
            'dedlain' => 'nullable|integer',
            'sdaca_v_srok' => 'nullable|string',
            'vlozenie_v_pervyi_etap' => 'nullable|integer',
            'vlozenie_vo_vtoroi_etap' => 'nullable|integer',
            'vlozenie_v_tretii_etap' => 'nullable|integer',
            'vlozenie_v_cetvertyi_etap' => 'nullable|integer',
            'podpisanie_dogovora' => 'required|integer',
            'kolicestvo_uslug' => 'nullable|integer',
            'kommentarii' => 'nullable|string',
            'znacenie_effektivnosti' => 'nullable|numeric',
        ];
    }

    public function onFailure(Failure ...$failures)
    {
        $colFailures = collect($failures);

        $dataFailures = $colFailures->map(function ($failure) {
            return [
                'key' => $failure->attribute(),
                'row' => $failure->row(),
                'message' =>  implode(' ', $failure->errors()),
            ];
        });
        dd($dataFailures);
    }

    public function customValidationMessages(): array
    {
        return [
            'data_sozdaniia.string' => 'Должно быть числом :attribute.',
        ];
    }
}
