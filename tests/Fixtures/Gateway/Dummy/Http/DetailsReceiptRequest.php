<?php

namespace Omnireceipt\Common\Tests\Fixtures\Gateway\Dummy\Http;

use Doctrine\Common\Collections\ArrayCollection;
use Omnireceipt\Common\Http\Request\AbstractDetailsReceiptRequest;
use Omnireceipt\Common\Http\Response\AbstractResponse;
use Omnireceipt\Common\Tests\Fixtures\Gateway\Dummy\Helper;

class DetailsReceiptRequest extends AbstractDetailsReceiptRequest
{
    const RULES = [
        'id' => ['required', 'string'],
    ];

    public function getData(): array
    {
        return [
            'id' => $this->getId(),
        ];
    }

    public function sendData(array $data): AbstractResponse
    {
        $options = [
            'id' => $data['id'],
        ];

        $answer = (new ArrayCollection(Helper::getFixtureAsArray('details_collection')))
                  ->filter(static function(array $item) use ($options) {
                      return $item['id'] === $options['id'];
                  })
                  ->first() ?: null;

        return $answer
            ? new DetailsReceiptResponse($this, $answer, 200)
            : new DetailsReceiptResponse($this, null, 404);
    }
}