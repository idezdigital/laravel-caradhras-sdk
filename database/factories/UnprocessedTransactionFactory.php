<?php

namespace Idez\Caradhras\Database\Factories;

use Carbon\Carbon;
use Idez\Caradhras\Data\UnprocessedTransaction;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UnprocessedTransactionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = UnprocessedTransaction::class;

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
            'idTipoTransacaoNaoProcessada' => UnprocessedTransaction::TYPE_PIER,
            'descricaoTipoTransacaoNaoProcessada' => $this->faker->randomElement(['Saque', 'Compra', 'Ajuste']),
            'descricaoAbreviada' => 'Compra Pré-Pago Visa Nacional',
            'idConta' => $this->faker->randomNumber(2),
            'cartaoMascarado' => substr_replace($this->faker->creditCardNumber(), '000000', 4, 6),
            'nomePortador' => Str::upper(substr($this->faker->name(), 0, 21)),
            'dataOrigem' => Carbon::createFromTimestamp($dateSequence->current())->toDateTimeString(),
            'dataFaturamento' => null,
            'dataVencimentoReal' => Carbon::parse($this->faker->dateTime())->format('Y-m-d'),
            'modoEntradaTransacao' => null,
            'taxaEmbarque' => null,
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
            'nomeEstabelecimento' => Str::upper(str_pad($placeName, 25).$cityName.'BR'),
            'nomeFantasiaEstabelecimento' => $placeName,
            'localidadeEstabelecimento' => str_pad($cityName, 13).'/BR',
            'plano' => 0,
            'parcela' => 0,
            'detalhesTransacao' => 'O10045 - Compra Pré-Pago Visa Nacional',
            'flagCredito' => 0,
            'flagFaturado' => 0,
            'flagEstorno' => null,
            'idTransacaoEstorno' => null,
            'valorCompraMoedaEstrangeira' => null,
            'moedaEstrangeira' => null,
            'valorTAC' => 0.0000,
            'valorIOF' => 12.1200,
        ];
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
            'descricaoAbreviada' => 'Pagamento Efetuado       ',
            'flagCredito' => 1,
        ]);
    }

    public function payment()
    {
        return $this->state([
            'descricaoAbreviada' => 'Pagamento de Contas',
            'detalhesTransacao' => 'A10000-Pagamento de Contas-Cta Pgto',
        ]);
    }

    public function purchaseInternational()
    {
        $usdAmt = $this->faker->randomFloat(2, 1, 5);
        $usdRate = $this->faker->randomFloat(2, 4, 5);

        return $this->state(fn ($attributes) => [
            'descricaoAbreviada' => 'Compra Pré-Pago Visa Ext',
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
        return $this->state(fn ($attributes) => [
            'descricaoAbreviada' => 'Compra Pré-Pago Visa Nacional',
            'modoEntradaTransacao' => $this->faker->randomElement(['Chip', 'Contactless']).' / Terminal aceita PIN',
        ]);
    }

    public function transferP2pReceived()
    {
        return $this->state([
            'descricaoAbreviada' => 'Transf entre Contas-Favorecido',
            'flagCredito' => 1,
        ]);
    }

    public function transferP2pSent()
    {
        return $this->state([
            'descricaoAbreviada' => 'Transf entre Contas-Remetente',
        ]);
    }

    public function transferTedSent()
    {
        return $this->state([
            'descricaoAbreviada' => 'Transf Bancaria Enviada',
        ]);
    }

    public function withdrawal()
    {
        return $this->state([
            'descricaoAbreviada' => 'Saque Nacional Pré-Pago Visa',
            'modoEntradaTransacao' => 'Chip / Terminal aceita PIN',
            'codigoMCC' => 6011,
            'grupoMCC' => 18,
            'grupoDescricaoMCC' => 'Serviços financeiros',
            'idEstabelecimento' => 1,
            'plano' => 1,
            'parcela' => 1,

        ]);
    }
}
