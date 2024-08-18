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
            $row[1],
            Date::excelToDateTimeObject($row[2])->format('Y-m-d'),
            Date::excelToDateTimeObject($row[13])->format('Y-m-d'),
            isset($row[7]) ? Date::excelToDateTimeObject($row[7])->format('Y-m-d') : null,
            isset($row[3]) ? $row[3] == 'да' : null,
            isset($row[8]) ? $row[8] == 'да' : null,
            isset($row[5]) ? $row[5] == 'да' : null,
            isset($row[6]) ? $row[6] == 'да' : null,
            $row[4] ?? null,
            $row[14] ?? null,
            $row[9] ?? null,
            $row[10] ?? null,
            $row[11] ?? null,
            $row[12] ?? null,
            $row[15] ?? null,
            isset($row[16]) ? (float)$row[16] : null,
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
