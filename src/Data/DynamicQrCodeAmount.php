<?php

namespace Idez\Caradhras\Data;

use App\Enums\Caradhras\Pix\DiscountType;
use App\Enums\Caradhras\Pix\FineType;
use App\Enums\Caradhras\Pix\InterestType;
use App\Enums\Caradhras\Pix\RebateType;

class DynamicQrCodeAmount
{
    public function __construct(
        private float $amount,
        private ?array $discount,
        private ?array $rebate,
        private ?array $fine,
        private ?array $interest,
    ) {
    }

    public function toArray(): array
    {
        $data = [
            'original' => sprintf('%0.2f', $this->amount),
        ];

        if (filled($this->discount)) {
            $data['discount'] = [
                'amountPerc' => sprintf('%0.2f', $this->discount['amount']),
                'modality' => DiscountType::tryFrom($this->discount['type'])->crType(),
            ];
        }

        if (filled($this->rebate)) {
            $data['abatement'] = [
                'amountPerc' => sprintf('%0.2f', $this->rebate['amount']),
                'modality' => RebateType::tryFrom($this->rebate['type'])->crType(),
            ];
        }

        if (filled($this->fine)) {
            $data['fine'] = [
                'amountPerc' => sprintf('%0.2f', $this->fine['amount']),
                'modality' => FineType::tryFrom($this->fine['type'])->crType(),
            ];
        }

        if (filled($this->interest)) {
            $data['interest'] = [
                'amountPerc' => sprintf('%0.2f', $this->interest['amount']),
                'modality' => InterestType::tryFrom($this->interest['type'])->crType(),
            ];
        }

        return $data;
    }
}
