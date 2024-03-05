<?php

namespace Idez\Caradhras\Database\Factories;

use Carbon\Carbon;
use Idez\Caradhras\Data\TransactionLegacy;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionLegacyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TransactionLegacy::class;

    private function getDateSequence()
    {
        for ($i = strtotime('-200 days'); $i < time(); $i += rand(600, 604800)) {
            yield $i;
        }
    }

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $dateSequence = $this->getDateSequence();
        $dateSequence->next();

        $placeName = substr($this->faker->company(), 0, 25);
        $cityName = substr($this->faker->city(), 0, 13);

        return [
            'id' => $this->faker->randomNumber(3),
            'idTipoTransacao' => TransactionLegacy::PURCHASE_NATIONAL,
            'descricaoAbreviada' => 'Compra Pré-Pago Visa Nacional',
            'statusTransacao' => '2',
            'idEvento' => $this->faker->randomNumber(4),
            'tipoEvento' => 'Compra',
            'idConta' => $this->faker->randomNumber(2),
            'cartaoMascarado' => substr_replace($this->faker->creditCardNumber(), '000000', 4, 6),
            'nomePortador' => \Illuminate\Support\Str::upper(substr($this->faker->name(), 0, 21)),
            'dataTransacaoUTC' => null,
            'dataTransacao' => Carbon::createFromTimestamp($dateSequence->current())->toDateTimeString(),
            'dataFaturamento' => null,
            'dataVencimento' => Carbon::parse($this->faker->dateTime())->format('Y-m-d'),
            'modoEntradaTransacao' => null,
            'valorTaxaEmbarque' => null,
            'valorEntrada' => null,
            'valorBRL' => $this->faker->randomFloat(2, 10, 100),
            'valorUSD' => 0.0000,
            'cotacaoUSD' => null,
            'dataCotacaoUSD' => null,
            'codigoMoedaOrigem' => 'BRL',
            'codigoMoedaDestino' => 'BRL',
            'codigoAutorizacao' => '768180',
            'codigoReferencia' => '24278910137165930760337',
            'codigoTerminal' => 'VSN',
            'codigoMCC' => 5814,
            'grupoMCC' => 14,
            'grupoDescricaoMCC' => 'Restaurante / Lanchonete / Bar',
            'idEstabelecimento' => 1,
            'nomeEstabelecimento' => \Illuminate\Support\Str::upper(str_pad($placeName, 25).$cityName.'BR'),
            'nomeFantasiaEstabelecimento' => $placeName,
            'localidadeEstabelecimento' => str_pad($cityName, 13).'/BR',
            'planoParcelamento' => 0,
            'numeroParcela' => 0,
            'detalhesTransacao' => 'O10045 - Compra Pré-Pago Visa Nacional',
            'flagCredito' => 0,
            'flagFaturado' => 0,
            'flagEstorno' => null,
            'idTransacaoEstorno' => null,
            'valorCompraMoedaEstrangeira' => null,
            'moedaEstrangeira' => null,
        ];
    }

    public function base1()
    {
        return $this->state([
            'statusTransacao' => '1',
        ]);
    }

    public function base2()
    {
        return $this->state([
            'statusTransacao' => '2',
        ]);
    }

    public function credit()
    {
        return $this->state([
            'flagCredito' => 1,
        ]);
    }

    public function deposit()
    {
        return $this->state([
            'idTipoTransacao' => TransactionLegacy::DEPOSIT,
            'descricaoAbreviada' => 'Pagamento Efetuado       ',
            'tipoEvento' => 'Pagamento',
            'detalhesTransacao' => 'P29 - Pagamentos Validos Normais',
            'flagCredito' => 1,
        ]);
    }

    public function payment()
    {
        return $this->state([
            'idTipoTransacao' => TransactionLegacy::PAYMENT,
            'descricaoAbreviada' => 'Pagamento de Contas',
            'tipoEvento' => 'Ajuste',
            'detalhesTransacao' => 'A10000-Pagamento de Contas-Cta Pgto',
        ]);
    }

    public function purchaseInternational()
    {
        $usdAmt = $this->faker->randomFloat(2, 1, 5);
        $usdRate = $this->faker->randomFloat(2, 4, 5);

        return $this->state(fn (array $attributes) => [
            'idTipoTransacao' => TransactionLegacy::PURCHASE_INTL,
            'descricaoAbreviada' => 'Compra Pré-Pago Visa Ext',
            'tipoEvento' => 'NaoUtilizada',
            'valorBRL' => $usdAmt * $usdRate,
            'valorUSD' => $usdAmt,
            'cotacaoUSD' => $usdRate,
            'dataCotacaoUSD' => Carbon::parse($this->faker->dateTimeThisMonth())->toDateTimeString(),
            'codigoMoedaOrigem' => 'USD',
            'codigoMoedaDestino' => 'USD',
            'nomeEstabelecimento' => 'INSOMNIA PLUS            MONTREAL     CA',
            'nomeFantasiaEstabelecimento' => 'INSOMNIA PLUS',
            'localidadeEstabelecimento' => 'MONTREAL/CA',
            'detalhesTransacao' => 'O10046 - Compra Pré-Pago Visa Ext R$',
            'valorCompraMoedaEstrangeira' => $usdAmt,
            'moedaEstrangeira' => 'USD',
        ]);
    }

    public function purchaseNational()
    {
        return $this->state(fn (array $attributes) => [
            'idTipoTransacao' => TransactionLegacy::PURCHASE_NATIONAL,
            'descricaoAbreviada' => 'Compra Pré-Pago Visa Nacional',
            'tipoEvento' => 'Compra',
            'modoEntradaTransacao' => $this->faker->randomElement(['Chip', 'Contactless']).' / Terminal aceita PIN',
            'detalhesTransacao' => 'O10045 - Compra Pré-Pago Visa Nacional',
        ]);
    }

    public function transferP2pSent()
    {
        return $this->state([
            'id' => 6616,
            'idTipoTransacao' => TransactionLegacy::P2P_SENT,
            'descricaoAbreviada' => 'Transf entre Contas-Remetente',
            'tipoEvento' => 'Ajuste',
            'detalhesTransacao' => 'A10031-Transferencia entre Contas-Remetente-Cta Pgto',
        ]);
    }

    public function transferP2pReceived()
    {
        return $this->state([
            'idTipoTransacao' => TransactionLegacy::P2P_RECEIVED,
            'descricaoAbreviada' => 'Transf entre Contas-Favorecido',
            'tipoEvento' => 'Ajuste',
            'detalhesTransacao' => 'A10032-Transferencia entre Contas-Favorecido-Cta Pgto',
            'flagCredito' => 1,
        ]);
    }

    public function transferTedSent()
    {
        return $this->state([
            'idTipoTransacao' => TransactionLegacy::TRANSFER_SENT,
            'descricaoAbreviada' => 'Transf Bancaria Enviada',
            'tipoEvento' => 'Ajuste',
            'detalhesTransacao' => 'A10038-Transferencia Bancaria Enviada-Cta Pgto',
        ]);
    }

    public function withdrawal()
    {
        return $this->state([
            'idTipoTransacao' => TransactionLegacy::WITHDRAWAL,
            'descricaoAbreviada' => 'Saque Nacional Pré-Pago Visa',
            'tipoEvento' => 'Parcela',
            'modoEntradaTransacao' => 'Chip / Terminal aceita PIN',
            'codigoMCC' => 6011,
            'grupoMCC' => 18,
            'grupoDescricaoMCC' => 'Serviços financeiros',
            'idEstabelecimento' => 1,
            'planoParcelamento' => 1,
            'numeroParcela' => 1,
            'detalhesTransacao' => 'O10048 - Parcela Saque Nacional Pré-Pago Visa',
        ]);
    }
}
