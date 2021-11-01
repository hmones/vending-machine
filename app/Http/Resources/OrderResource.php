<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray($request): array
    {
        $change = $this->user->deposit ?? 0;

        return [
            'total_spent'    => $this->product->cost * $this->amount,
            'product_id'     => $this->product->id,
            'product_amount' => $this->amount,
            'change'   => [
                '100_cents' => $this->getChangeAmount($change, 1),
                '50_cents'  => $this->getChangeAmount($change, 0.5),
                '20_cents'  => $this->getChangeAmount($change, 0.2),
                '10_cents'  => $this->getChangeAmount($change, 0.1),
                '5_cents'   => $this->getChangeAmount($change, 0.05),
            ]
        ];
    }

    protected function getChangeAmount(float &$amount, float $change): int
    {
        $amount = $amount + 0.0001;
        $result = ($amount - fmod($amount, $change))/$change;
        $amount = fmod($amount, $change);

        return (int) $result;
    }
}
