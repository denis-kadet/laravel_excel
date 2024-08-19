<?php

namespace App\Imports;

use App\Factory\ProjectFactory;
use App\Models\FailedRow;
use App\Models\Project;
use App\Models\Task;
use App\Models\Type;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Validators\Failure;

class ProjectImport implements ToCollection, WithHeadingRow, WithValidation, SkipsOnFailure
{
    private Task $task;

    /**
     * @param $task
     */
    public function __construct($task)
    {
        $this->task = $task;
    }

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
            'data_sozdaniia' => 'required|integer',//string
            'setevik' => 'nullable|string',
            'kolicestvo_ucastnikov' => 'nullable|integer',//string
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
                'key' => $this->attributesMap()[$failure->attribute()],
                'row' => $failure->row(),
                'message' => implode(' ', $failure->errors()),
                'task_id' => 3,
            ];
        });
        if ($dataFailures->count()) FailedRow::insertFailedRows($dataFailures, $this->task);
    }

    public function customValidationMessages(): array
    {
        return [
            'data_sozdaniia.string' => 'Должно быть числом :attribute.',
        ];
    }

    private function attributesMap(): array
    {
        return [
            'tip' => 'Тип',
            'naimenovanie' => 'Наименование',
            'data_sozdaniia' => 'Дата создания',
            'setevik' => 'Сетевик',
            'kolicestvo_ucastnikov' => 'Количество участников',
            'nalicie_autsorsinga' => 'Наличие аутсорсинга',
            'nalicie_investorov' => 'Наличие инвесторов',
            'dedlain' => 'Дедлайн',
            'sdaca_v_srok' => 'Сдача в срок',
            'vlozenie_v_pervyi_etap' => 'Вложение в первый этап',
            'vlozenie_vo_vtoroi_etap' => 'Вложение во второй этап',
            'vlozenie_v_tretii_etap' => 'Вложение в третий этап',
            'vlozenie_v_cetvertyi_etap' => 'Вложение в четвертый этап',
            'podpisanie_dogovora' => 'Подписание договора',
            'kolicestvo_uslug' => 'Количество участников',
            'kommentarii' => 'Комментарий',
            'znacenie_effektivnosti' => 'Значение эффективности',
        ];
    }
}
