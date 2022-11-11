<?php
namespace Customize\Controller\Admin;

use Eccube\Entity\ExportCsvRow;
use Eccube\Entity\Master\CsvType;
use Eccube\Entity\OrderItem;
use Eccube\Entity\Product;
use Eccube\Event\EccubeEvents;
use Eccube\Event\EventArgs;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends \Eccube\Controller\Admin\Order\OrderController
{
    /**
     * 受注CSVの出力.
     *
     * @Route("/%eccube_admin_route%/order/export/order", name="admin_order_export_order")
     *
     * @param Request $request
     *
     * @return StreamedResponse
     */
    public function exportOrder(Request $request)
    {
        $filename = 'order_'.(new \DateTime())->format('YmdHis').'.csv';
        $response = $this->exportOrderCsv($request, CsvType::CSV_TYPE_ORDER, $filename);
        log_info('受注CSV出力ファイル名', [$filename]);

        return $response;
    }

    /**
     * @param Request $request
     * @param $csvTypeId
     * @param string $fileName
     *
     * @return StreamedResponse
     */
    protected function exportOrderCsv(Request $request, $csvTypeId, $fileName)
    {
        // タイムアウトを無効にする.
        set_time_limit(0);

        // sql loggerを無効にする.
        $em = $this->entityManager;
        $em->getConfiguration()->setSQLLogger(null);

        $response = new StreamedResponse();
        $response->setCallback(function () use ($request, $csvTypeId) {
            // CSV種別を元に初期化.
            $this->csvExportService->initCsvType($csvTypeId);

            // ヘッダ行の出力.
            $this->csvExportService->exportHeader();

            // 受注データ検索用のクエリビルダを取得.
            $qb = $this->csvExportService
                ->getOrderQueryBuilder($request);

            // データ行の出力.
            $this->csvExportService->setExportQueryBuilder($qb);
            $this->csvExportService->exportData(function ($entity, $csvService) use ($request) {
                $Csvs = $csvService->getCsvs();

                $Order = $entity;
                $OrderItems = $Order->getOrderItems();

                $meisai_no = 1;
                /** @var OrderItem $OrderItem */
                foreach ($OrderItems as $OrderItem) {
                    // 商品以外（手数料・送料）のレコードは出さない
                    if (!$OrderItem->isProduct()) {
                        continue;
                    }
                    $ExportCsvRow = new ExportCsvRow();

                    // CSV出力項目と合致するデータを取得.
                    foreach ($Csvs as $Csv) {
                        if ($Csv->getFieldName() === 'meisai_no') {
                            $ExportCsvRow->setData($meisai_no);
                        }
                        if ($Csv->getEntityName() === 'Fixed') {
                            // '0'など、固定値で出すものはCSV設定のfiele_nameに値が入っている。
                            $ExportCsvRow->setData($Csv->getFieldName());
                        }

                        if ($ExportCsvRow->isDataNull()) {
                            // 受注データを検索.
                            $ExportCsvRow->setData($csvService->getData($Csv, $Order));
                        }
                        if ($ExportCsvRow->isDataNull()) {
                            // 受注データにない場合は, 受注明細を検索.
                            $ExportCsvRow->setData($csvService->getData($Csv, $OrderItem));
                        }
                        if ($ExportCsvRow->isDataNull() && $Shipping = $OrderItem->getShipping()) {
                            // 受注明細データにない場合は, 出荷を検索.
                            $ExportCsvRow->setData($csvService->getData($Csv, $Shipping));
                        }

                        $event = new EventArgs(
                            [
                                'csvService' => $csvService,
                                'Csv' => $Csv,
                                'OrderItem' => $OrderItem,
                                'ExportCsvRow' => $ExportCsvRow,
                            ],
                            $request
                        );
                        $this->eventDispatcher->dispatch(EccubeEvents::ADMIN_ORDER_CSV_EXPORT_ORDER, $event);

                        $ExportCsvRow->pushData();
                    }

                    //$row[] = number_format(memory_get_usage(true));
                    // 出力.
                    $csvService->fputcsv($ExportCsvRow->getRow());
                    $meisai_no++;
                }
            });
        });

        $response->headers->set('Content-Type', 'application/octet-stream');
        $response->headers->set('Content-Disposition', 'attachment; filename='.$fileName);
        $response->send();

        return $response;
    }
}