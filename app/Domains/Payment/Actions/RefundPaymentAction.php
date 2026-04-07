<?php

namespace App\Domains\Payment\Actions;

use App\Domains\Payment\DTOs\RefundDTO;
use App\Domains\Payment\Gateways\PaymentGatewayInterface;
use App\Domains\Payment\Repositories\PaymentRepositoryInterface;
use App\Models\Payment;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RefundPaymentAction
{
  public function __construct(
    private readonly PaymentRepositoryInterface $repository,
  ) {}

  public function execute(RefundDTO $dto): array
  {
    return DB::transaction(function () use ($dto) {

      $payment = $this->repository->findPayment($dto->paymentId);

      throw_if(! $payment,           \Exception::class, 'Payment not found.');
      throw_if(! $payment->isPaid(), \Exception::class, 'Payment is not paid.');

      try {
        // ─── استرداد من المحفظة ────────────────────────────────────
        if ($dto->gateway === 'wallet') {
          return $this->refundViaWallet($payment, $dto);
        }

        // ─── استرداد من Gateway ────────────────────────────────────
        return $this->refundViaGateway($payment, $dto);
      } catch (\Throwable $e) {
        Log::error('Refund failed', ['payment_id' => $payment->id, 'error' => $e->getMessage()]);
        throw $e;
      }
    });
  }

  private function refundViaGateway(Payment $payment, RefundDTO $dto): array
  {
    $gateway = app(PaymentGatewayInterface::class, ['gatewayName' => $dto->gateway]);
    $result  = $gateway->refund($dto);

    $status = $result['success']
      ? Transaction::STATUS_SUCCESS
      : Transaction::STATUS_FAILED;

    $this->repository->createGatewayTransaction(
      payment: $payment,
      type: Transaction::TYPE_REFUND,
      gatewayTransactionId: $result['refund_id'],
      amount: $dto->amount,
      currency: $dto->currency,
      status: $status,
      gatewayResponse: $result['raw'],
      installmentNumber: null,
    );

    if ($result['success']) {
      $this->repository->updatePaymentStatus($payment, Payment::STATUS_REFUNDED);
    }

    return [
      'success'        => $result['success'],
      'payment_id'     => $payment->id,
      'refund_id'      => $result['refund_id'],
      'payment_method' => 'gateway',
      'status'         => $payment->fresh()->status,
    ];
  }

  private function refundViaWallet(Payment $payment, RefundDTO $dto): array
  {
    $toWallet = $this->repository->findWalletByUserId($payment->user_id);

    throw_if(! $toWallet, \Exception::class, 'Wallet not found.');

    $this->repository->creditWallet($toWallet, $dto->amount);

    $transaction = $this->repository->createWalletTransaction(
      payment: $payment,
      type: Transaction::TYPE_REFUND,
      fromWalletId: $dto->fromWalletId ?? $toWallet->id,
      toWalletId: $toWallet->id,
      amount: $dto->amount,
      currency: $dto->currency,
      status: Transaction::STATUS_SUCCESS,
      installmentNumber: null,
    );

    $this->repository->updatePaymentStatus($payment, Payment::STATUS_REFUNDED);

    return [
      'success'        => true,
      'payment_id'     => $payment->id,
      'refund_id'      => $transaction->id,
      'payment_method' => 'wallet',
      'status'         => Payment::STATUS_REFUNDED,
    ];
  }
}
