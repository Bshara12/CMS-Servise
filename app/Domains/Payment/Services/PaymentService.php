<?php

namespace App\Domains\Payment\Services;

use App\Domains\Payment\Actions\PayInstallmentAction;
use App\Domains\Payment\Actions\ProcessPaymentAction;
use App\Domains\Payment\Actions\RefundPaymentAction;
use App\Domains\Payment\DTOs\PayInstallmentDTO;
use App\Domains\Payment\DTOs\PaymentDTO;
use App\Domains\Payment\DTOs\RefundDTO;

class PaymentService
{
  public function __construct(
    private readonly ProcessPaymentAction  $processAction,
    private readonly PayInstallmentAction  $installmentAction,
    private readonly RefundPaymentAction   $refundAction,
  ) {}

  public function processPayment(PaymentDTO $dto): array
  {
    return $this->processAction->execute($dto);
  }

  public function payInstallment(PayInstallmentDTO $dto): array
  {
    return $this->installmentAction->execute($dto);
  }

  public function processRefund(RefundDTO $dto): array
  {
    return $this->refundAction->execute($dto);
  }
}
