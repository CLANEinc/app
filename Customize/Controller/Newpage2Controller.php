<?php

namespace Customize\Controller;

// use キーワードを使うことで、下記のことが可能
// 名前空間などのエイリアス（別名）を作成
// 名前空間の全て、または一部をインポート
// クラスをインポート
// 関数をインポート
// 定数をインポート

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Eccube\Controller\AbstractController;
use Eccube\Repository\ProductRepository;
use Customize\Repository\CustomizeProductRepository;


class Newpage2Controller extends AbstractController
{

//変数を使用することを定義する
    /**
     * @var ProductRepository
     */
    protected $productRepository;




   /**
     * @var CustomizeProductRepository
     */
    protected $customizeProductRepository;




    /**
     * Newpage2Controller
       constructor.
     *
     * @param ProductRepository $productRepository
     * @param CustomizeProductRepository $customizeProductRepository
     */
    public function __construct(ProductRepository $productRepository,CustomizeProductRepository $customizeProductRepository){
      $this->productRepository = $productRepository;
      $this->customizeProductRepository = $customizeProductRepository;
   }
  /**
   **
    * テスト 画面.
    *
    *@Route("/new/page2", name="new_page2")
    *@Template("New/page2.twig")
    */
   public function index(Request $request)
   {

   // find(1) 商品のIDを入れれば商品情報を取得
   //   $Product = $this->productRepository ->find(1);
  



// findBy 結果が配列で返ってくる 商品のIDを入れれば商品情報を取得
   // $Product = $this->productRepository ->findBy(['name'=>'彩のジェラートCUBE']);
// dump($Product);
// die;



// findOneBy findと同じ ように1つの要素 しか 取得しない
// $Product = $this->productRepository ->findOneBy(['name'=>'彩のジェラートCUBE']);
// dump($Product);
// die;






// findAll 登録されている 全ての要素を取得する
// findAll はただ ID順に出力 する だけで
// 自由に抽出やソートをする ことができ ません
// $Products = $this->productRepository ->findAll();



// findBy 配列として取得自由に抽出やソート調整もできる 第2引数でソート変更 第3引数で表示数変更 第4引数で指定したレコードからX件分の抽出が可能になる
// $Products = $this->productRepository->findBy([],['id'=>'DESC'],2,1);
// $Products = $this->productRepository->findBy([],['id'=>'DESC']);


// カスタムリポジトリーでデータを取得することができている
$Products = $this->customizeProductRepository->findBy([],['id' => 'DESC']);


 //   戻り値に設定 twigで受け取るときは左側のProductで受け取れる
 return ['Products' => $Products];



 //   戻り値に設定 twigで受け取るときは左側のProductで受け取れる
//  return ['Product' => $Product];

   }
}
