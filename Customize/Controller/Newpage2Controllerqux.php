<?php
//viewとcontrollerを繋ぐための定義。
//Controllerというnamespaceを定義。
namespace Customize\Controller;

//useはimportのようなもの?
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Eccube\Controller\AbstractController;
use Eccube\Repository\ProductRepository;
use Customize\Repository\CustomizeProductRepository;

//AbstractControllerは規定のController。
//このclassの中ではcontrollerの初期化を行っている。
//controllerの初期化を行うことによってec-cube上でcontrollerだと認識してもらえるようになる。
class Newpage2Controllerqux extends AbstractController
{
    /**
     * @var ProductRepository
     */
    protected $productRepository;

    /**
     * @var CustomizeProductRepository
     */
    protected $customizeProductRepository;


    /**
     * WebdocNewsController
       constructor.
     * @param ProductRepository $productRepository
     * @param CustomizeProductRepository $customizeProductRepository
     *
     */
    public function __construct(
      ProductRepository $productRepository,
      CustomizeProductRepository $customizeProductRepository
    ){
      $this->productRepository = $productRepository;
      $this->customizeProductRepository=$customizeProductRepository;
   }
   //@Routeはユーザがurlでドメイン/webdoc/news/とやると表示してという定義。
   //@Templateはこのurlであればこのファイルを表示してという定義。
  /**
  /**
    * テスト 画面.
    *
    *@Route("/new/page2qux", name="new_page2qux")
    *@Template("New/page2qux.twig")
    */
    //indexの引数のRequestクラスとrequestインスタンス。
    //Controllerの上部でuserしている中にRequestクラスの記述があり、それを使用している。
   public function index(Request $request)
   {
    //  $Product=$this->productRepository->find(6);
    // $Product=$this->productRepository->findBy(["name"=>"バナナ"]);

    // $Product=$this->productRepository->findOneBy(["name"=>"バナナ"]);
    //findAll()メソッドではID順に取得される。findAll()メソッドはただID順に出力するだけで自由に抽出やソートをすることができない。
    //findBy()で引数に空配列を指定するケースとfindAll()メソッドで引数に何も指定しない場合は同じ内容。
    // $Products=$this->productRepository->findAll();
    // $Products=$this->productRepository->findBy([]);
    //デフォルトがASCなので以下のように第二引数に["id"=>"DESC"]とすれば降順で取得できる。
    // $Products=$this->productRepository->findBy([],["id"=>"DESC"]);
    //findBy())メソッドの第三引数に数字を入れると抽出レコード数を指定できる。
    // $Products=$this->productRepository->findBy([],["id"=>"DESC"],2);
    //第４引数を指定すると、第４引数はoffsetになる。offsetは開始位置。
    // $Products=$this->productRepository->findBy([],["id"=>"DESC"],2,1);
    // $Products=$this->customizeProductRepository->findBy([],["id"=>"DESC"],2,1);
    // $Products=$this->customizeProductRepository->customFindAll();
    // $Products=$this->customizeProductRepository->customFindAllDesc();
    $Products=$this->customizeProductRepository->customFindAllDesc(); 
    // $name=$_GET["name"];
    // $Product=$this->customizeProductRepository->customFindName($name);
    $name=$request->query->get("name");
    // if(isset($_GET["name"])){
    //   $name=$_GET["name"];
    //   $Product=$this->customizeProductRepository->customFindName($name);
    // }else{
    //   $Product=null;
    // }
    if(!empty($name)){
      $Product=$this->customizeProductRepository->customFindName($name);
    }else{
      $Product=null;
    }
    // dump($Product);
    // die;
     return ["Products"=>$Products,"SingleProduct"=>$Product];
   }
}