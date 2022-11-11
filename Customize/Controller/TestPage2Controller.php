<?php
//viewとcontrollerを繋ぐための定義。
//Controllerというnamespaceを定義。
namespace Customize\Controller;

//useはimportのようなもの?
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Eccube\Controller\AbstractController;

//AbstractControllerは規定のController。
//このclassの中ではcontrollerの初期化を行っている。
//controllerの初期化を行うことによってec-cube上でcontrollerだと認識してもらえるようになる。
class TestPage2Controller extends AbstractController
{
    /**
     * TestPage2Controller
       constructor.
     *
     */
    public function __construct(){
   }
   //@Routeはユーザがurlでドメイン/webdoc/news/とやると表示してという定義。
   //@Templateはこのurlであればこのファイルを表示してという定義。
  /**
  /**
    * テスト 画面.
    *
    *@Route("/webdoc/news", name="webdoc_news")
    *@Template("Webdoc/news.twig")
    */
    //indexの引数のRequestクラスとrequestインスタンス。
    //Controllerの上部でuserしている中にRequestクラスの記述があり、それを使用している。
   public function index(Request $request)
   {
return [];
   }
}