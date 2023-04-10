<?php

namespace App\Http\Resources;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TasksResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */

    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'attributes'=>[
                'name' => $this->name,
                'description' => $this->description,
                'priority' => $this->prority,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
            ],

            'relationships'=> [
                'id' => (string)$this->user->id,
                'user_name' => (string)$this->user->name,
                'user_email' => (string)$this->user->email,
            ]

        ];
    }
}
