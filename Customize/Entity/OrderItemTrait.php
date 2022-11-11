<?php
namespace Customize\Entity;

use Eccube\Annotation\EntityExtension;
use Eccube\Common\EccubeConfig;
use Doctrine\ORM\Mapping as ORM;

/**
 * @EntityExtension("Eccube\Entity\OrderItem")
 */
trait OrderItemTrait
{
    /**
     * @var string|null
     *
     * @ORM\Column(name="option_serial", type="string", length=10000, nullable=true)
     */
    private $option_serial;

    public function setOptionSerial($serial)
    {
        $this->option_serial = $serial;

        return $this;
    }

    public function getOptionSerial()
    {
        return $this->option_serial;
    }

    public function getArrOption()
    {
        return unserialize($this->option_serial);
    }

    public function getShippingFullAddr()
    {
        if ($this->getShipping()) {
            $prefName = $this->getShipping()->getPref()->getName();
            $addr01 = $this->getShipping()->getAddr01();
            $addr02 = $this->getShipping()->getAddr02();
            return $prefName . $addr01 . $addr02;
        }
        return '';
    }

    public function getDenpyoNo()
    {
        global $kernel;
        $container = $kernel->getContainer();
        $eccubeConfig = new EccubeConfig($container);
        $startVal = $eccubeConfig->get('denpyo_no_start_value');
        return $startVal - $this->getOrderId();
    }

    /**
     * 受注CSV用商品区分 カーテンは1, シェードは2
     * @return int
     */
    public function getProductKubun () {
        if ($this->getProduct()->isCurtain()) {
            return 1;
        }
        return 2;
    }

    public function getOptionChoiceCSV ()
    {
        if (!$this->isProduct()) {
            return '';
        }
        $optionKeyAndValue = [
            '基本スタイル' => '',
            '縫製スタイル' => '',
            '幅' => '',
            '高さ' => '',
            '開き方' => '',
            'フック' => '',
            '裏地' => '',
            'タッセル' => '',
            'タッセル１窓本数' => '',
            'カタログ' => '',
            '操作' => '',
            '取り付け方' => '',
            '取付高' => '',
            '数量' => '',
            'メカ' => '',
            'メカカラー' => '',
            'メーカー' => '',
            'スラット種類' => '',
            'ラダー種類' => '',
            'スラットカラー' => '',
            'ラダーテープカラー' => '',
            'ボトムレールカラー' => '',
            'バランス種類' => '',
            '部屋' => 'リビング',
            '品番2' => '',
            'オプション' => '',
            'オプション料金' => '',
        ];

        $optionArray = $this->getArrOption();
        if ($this->getProduct()->isCurtain()) {
            $NumOfTassel = 0;
            if ($optionArray['共生地タッセル']['value'] == 'あり') {
                $NumOfTassel = 1;
                if ($optionArray['開き方']['value'] == '両開き') {
                    $NumOfTassel = 2;
                }
            }

            $optionKeyAndValue['基本スタイル'] = $optionArray['縫製グレード']['value'];
            $optionKeyAndValue['縫製スタイル'] = $optionArray['縫製グレード']['value'];
            $optionKeyAndValue['幅'] = $optionArray['幅']['value'] * 10; // cm -> mm
            $optionKeyAndValue['高さ'] = $optionArray['高さ']['value'] * 10;
            $optionKeyAndValue['開き方'] = $optionArray['開き方']['value'];
            $optionKeyAndValue['フック'] = $optionArray['フック']['value'];
            $optionKeyAndValue['タッセル'] = $optionArray['共生地タッセル']['value'];
            $optionKeyAndValue['タッセル１窓本数'] = $NumOfTassel;

            $subOptions = [];
            $subOptionPrices = [];
            if ($optionArray['プリーツ加工']['value'] == 'あり') {
                $subOptions[] = 'プリーツ加工';
                $subOptionPrices[] = $optionArray['プリーツ加工']['price'];
            }
            if ($optionArray['リターン付き']['value'] == 'あり') {
                $subOptions[] = 'リターン付き';
                $subOptionPrices[] = $optionArray['リターン付き']['price'];
            }
            // '防炎ラベル' はカテゴリで判断する
            if ($this->getProduct()->isFireProof()) {
                $subOptions[] = '防炎ラベル';
                $subOptionPrices[] = 0;
            }
            $optionKeyAndValue['オプション'] = implode('#', $subOptions);
            $optionKeyAndValue['オプション料金'] = implode('#', $subOptionPrices);
        }
        if ($this->getProduct()->isShade()) {
            $optionKeyAndValue['基本スタイル'] = $optionArray['縫製スタイル']['value'];
            $optionKeyAndValue['縫製スタイル'] = $optionArray['縫製スタイル']['value'];
            $optionKeyAndValue['幅'] = $optionArray['幅']['value'] * 10; // cm -> mm
            $optionKeyAndValue['高さ'] = $optionArray['高さ']['value'] * 10;
            $optionKeyAndValue['操作'] = $optionArray['操作']['value'];
            $optionKeyAndValue['取り付け方'] = $optionArray['取り付け方']['value'];
            $optionKeyAndValue['取付高'] = $optionArray['取付高']['value'] * 10;
            $optionKeyAndValue['メカ'] = $optionArray['メカ']['value'];
            $optionKeyAndValue['品番2'] = isset($optionArray['裏生地']) ? $optionArray['裏生地']['value'] : '';

            $subOptions = [];
            $subOptionPrices = [];
            if ($optionArray['レール取り付け']['value'] == 'あり') {
                $subOptions[] = 'レール取り付け';
                $subOptionPrices[] = $optionArray['レール取り付け']['price'];
            }
            $optionKeyAndValue['オプション'] = implode('#', $subOptions);
            $optionKeyAndValue['オプション料金'] = implode('#', $subOptionPrices);

        }
        return implode(',', array_values($optionKeyAndValue));
    }
}
