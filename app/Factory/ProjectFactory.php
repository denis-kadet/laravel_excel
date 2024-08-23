<?php

namespace App\Factory;

use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class ProjectFactory
{
    private $typeId;
    private $title;
    private $createdAtTime;
    private $contractedAt;
    private $deadline;
    private $isChain;
    private $isOnTime;
    private $hasOutsource;
    private $hasInvestors;
    private $workerCount;
    private $serviceCount;
    private $paymentFirstStep;
    private $paymentSecondStep;
    private $paymentThirdStep;
    private $paymentForthStep;
    private $comment;
    private $effectiveValue;

    /**
     * @param $typeId
     * @param $title
     * @param $createdAtTime
     * @param $contractedAt
     * @param $deadline
     * @param $isChain
     * @param $isOnTime
     * @param $hasOutsource
     * @param $hasInvestors
     * @param $workerCount
     * @param $serviceCount
     * @param $paymentFirstStep
     * @param $paymentSecondStep
     * @param $paymentThirdStep
     * @param $paymentForthStep
     * @param $comment
     * @param $effectiveValue
     */
    public function __construct($typeId, $title, $createdAtTime, $contractedAt, $deadline, $isChain, $isOnTime, $hasOutsource, $hasInvestors, $workerCount, $serviceCount, $paymentFirstStep, $paymentSecondStep, $paymentThirdStep, $paymentForthStep, $comment, $effectiveValue)
    {
        $this->typeId = $typeId;
        $this->title = $title;
        $this->createdAtTime = $createdAtTime;
        $this->contractedAt = $contractedAt;
        $this->deadline = $deadline;
        $this->isChain = $isChain;
        $this->isOnTime = $isOnTime;
        $this->hasOutsource = $hasOutsource;
        $this->hasInvestors = $hasInvestors;
        $this->workerCount = $workerCount;
        $this->serviceCount = $serviceCount;
        $this->paymentFirstStep = $paymentFirstStep;
        $this->paymentSecondStep = $paymentSecondStep;
        $this->paymentThirdStep = $paymentThirdStep;
        $this->paymentForthStep = $paymentForthStep;
        $this->comment = $comment;
        $this->effectiveValue = $effectiveValue;
    }

    public static function make($row, $typeId): ProjectFactory
    {
        return new self(
            $typeId->id,// self::getTypeId($typesMap, $row[0]) - но при первом добавление залетает все что не пожходит
            $row['naimenovanie'],
            Date::excelToDateTimeObject($row['data_sozdaniia'])->format('Y-m-d'),
            Date::excelToDateTimeObject($row['podpisanie_dogovora'])->format('Y-m-d'),
            isset($row['dedlain']) ? Date::excelToDateTimeObject($row['dedlain'])->format('Y-m-d') : null,
            isset($row['setevik']) ? $row['setevik'] == 'да' : null,
            isset($row['sdaca_v_srok']) ? $row['sdaca_v_srok'] == 'да' : null,
            isset($row['nalicie_autsorsinga']) ? $row['nalicie_autsorsinga'] == 'да' : null,
            isset($row['nalicie_investorov']) ? $row['nalicie_investorov'] == 'да' : null,
            $row['kolicestvo_ucastnikov'] ?? null,
            $row['kolicestvo_uslug'] ?? null,
            $row['vlozenie_v_pervyi_etap'] ?? null,
            $row['vlozenie_vo_vtoroi_etap'] ?? null,
            $row['vlozenie_v_tretii_etap'] ?? null,
            $row['vlozenie_v_cetvertyi_etap'] ?? null,
            $row['kommentarii'] ?? null,
            isset($row['znacenie_effektivnosti']) ? (float)$row['znacenie_effektivnosti'] : null,
        );
    }

    public function getValues(): array
    {
        $props = get_object_vars($this);

        return collect($props)->mapWithKeys(function ($item, $key) {
            return [Str::snake($key) => $item];
        })->toArray();
    }
}
