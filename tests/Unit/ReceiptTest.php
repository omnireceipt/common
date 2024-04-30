<?php

namespace Omnireceipt\Common\Tests\Unit;

use Omnireceipt\Common\Contracts\ReceiptInterface;
use Omnireceipt\Common\Entities\Receipt;
use Omnireceipt\Common\Exceptions\Parameters\ParameterNotFoundException;
use Omnireceipt\Common\Supports\ParametersTrait;
use Omnireceipt\Common\Tests\factories\ReceiptFactory;
use Omnireceipt\Common\Tests\factories\ReceiptItemFactory;
use Omnireceipt\Common\Tests\TestCase;

class ReceiptTest extends TestCase
{
    public function testBase()
    {
        $receiptItem = new Receipt;

        $this->assertInstanceOf(ReceiptInterface::class, $receiptItem);
        $this->assertContains(ParametersTrait::class, class_uses($receiptItem));
    }

    /**
     * @depends testBase
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\Depends('testBase')]
    public function testGetterAndSetter()
    {
        $receipt = new Receipt;
        $type = 'Type';
        $paymentId = 'Payment id';
        $customerName = 'Customer name';
        $customerEmail = 'Customer email';
        $customerPhone = 'Customer phone';
        $info = 'Info';
        $date = 'Date';
        $qweAsd = 'QweAsd';

        $receipt->setType($type);
        $receipt->setPaymentId($paymentId);
        $receipt->setInfo($info);
        $receipt->setDate($date);

        $receipt->setQweAsd($qweAsd);

        $this->assertEquals($type, $receipt->getType());
        $this->assertEquals($paymentId, $receipt->getPaymentId());
        $this->assertEquals($info, $receipt->getInfo());
        $this->assertEquals($date, $receipt->getDate());
        $this->assertEquals($qweAsd, $receipt->getQweAsd());
    }

    /**
     * @depends testGetterAndSetter
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\Depends('testGetterAndSetter')]
    public function testGetterException()
    {

        $receipt = new Receipt;

        $this->expectException(ParameterNotFoundException::class);
        $receipt->getType();
    }

    /**
     * @depends testGetterAndSetter
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\Depends('testGetterAndSetter')]
    public function testValidator()
    {
        $receipt = ReceiptFactory::create();

        $this->assertInstanceOf(ReceiptInterface::class, $receipt);
        $this->assertFalse($receipt->validate());

        $receipt->addItem(ReceiptItemFactory::create());
        $this->assertTrue($receipt->validate());

        $receipt->setType(null);
        $this->assertFalse($receipt->validate());
    }

    /**
     * @depends testGetterAndSetter
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\Depends('testGetterAndSetter')]
    public function testItems()
    {
        $receipt = ReceiptFactory::create();

        $receiptItem = ReceiptItemFactory::create();
        $receiptItem->setAmount(12.51);
        $receipt->addItem($receiptItem);

        $receiptItem = ReceiptItemFactory::create();
        $receiptItem->setAmount(20.82);
        $receipt->addItem($receiptItem);

        $this->assertCount(2, $receipt->getItemList());
        $this->assertEquals(33.33, $receipt->getAmount());
    }
}
