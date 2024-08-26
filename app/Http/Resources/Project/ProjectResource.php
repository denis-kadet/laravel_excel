<?php

namespace App\Http\Resources\Project;

use App\Http\Resources\Type\TypeResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'type' => new TypeResource($this->type),
            'title' => $this->title,
            'created_at_time' => $this->created_at_time->format('Y-m-d'),
            'contracted_at' => $this->contracted_at->format('Y-m-d'),
            'deadline' => isset($this->deadline) ? $this->deadline->format('Y-m-d') : '',
            'is_chain' => $this->is_chain ? 'Да' : 'Нет',
            'is_on_time' => $this->is_on_time ? 'Да' : 'Нет',
            'has_outsource' => $this->has_outsource ? 'Да' : 'Нет',
            'has_investors' => $this->has_investors ? 'Да' : 'Нет',
            'worker_count' => $this->worker_count,
            'service_count' => $this->service_count,
            'payment_first_step' => $this->payment_first_step,
            'payment_second_step' => $this->payment_second_step,
            'payment_third_step' => $this->payment_third_step,
            'payment_forth_step' => $this->payment_forth_step,
            'comment' => $this->comment,
            'effective_value' => $this->effective_value,
            'created_at' => $this->created_at->format('d-m-Y H:i:s'),
            'updated_at' => $this->updated_at->format('d-m-Y H:i:s'),
        ];
    }
}
