<?php

namespace Idez\Caradhras\Database\Factories;

use Idez\Caradhras\Data\Card;
use Idez\Caradhras\Data\CardSettings;
use Idez\Caradhras\Enums\Cards\CardStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class CardSettingsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CardSettings::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $cardNumber = $this->faker->creditCardNumber('Visa');

        return [
            'id' => $this->faker->randomNumber(3),
            'flagTitular' => $this->faker->boolean(),
            'idPessoa' => $this->faker->randomNumber(2, true),
            'sequencialCartao' => $this->faker->randomNumber(2, true),
            'idConta' => $this->faker->randomNumber(3),
            'idStatus' => $this->faker->randomElement(CardStatus::cases()),
            'dataStatus' => '2020-05-12T01:37:00.000Z',
            'idEstagio' => 4,
            'dataEstagio' => '2020-05-13T05:45:00.000Z',
            'numeroBin' => substr($cardNumber, 0, 6),
            'numeroCartao' => substr_replace($cardNumber, '********', 4, 8),
            'numeroCartaoHash' => -6892947191434469588,
            'numeroCartaoCriptografado' => '0F2BCD967581B8D009924FCE6CA1AF98',
            'dataEmissao' => '2020-05-12T01:37:00.000Z',
            'dataValidade' => '2025-05-31T00:00:00.000Z',
            'cartaoVirtual' => 0,
            'impressaoAvulsa' => null,
            'dataImpressao' => '2020-05-13T05:45:00.000Z',
            'nomeArquivoImpressao' => 'IDEZ_2PJ_130520',
            'idProduto' => $this->faker->randomDigitNotNull(),
            'nomeImpresso' => $this->faker->name(),
            'codigoDesbloqueio' => '5175',
            'tipoPortador' => 'T',
            'idStatusCartao' => 2,
            'dataStatusCartao' => '2020-05-12T01:37:00.000Z',
            'idEstagioCartao' => 4,
            'dataEstagioCartao' => '2020-05-13T05:45:00.000Z',
            'dataGeracao' => '2020-05-12T01:37:00.000Z',
            'flagVirtual' => 0,
            'flagImpressaoOrigemComercial' => null,
            'arquivoImpressao' => 'IDEZ_2PJ_130520',
            'portador' => 1,
            'flagCartaoMifare' => false,
            'idImagem' => null,
        ];
    }
}
