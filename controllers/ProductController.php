<?php

namespace app\controllers;

use app\controllers\CommonController;
use Yii;
use app\models\Product;
use yii\data\Pagination;

class ProductController extends CommonController
{
    public function actionIndex()
    {
        $this->layout = 'layout2';
        $cid = Yii::$app->request->get('cateid');
        //var_dump($cid);exit;
        $where = "cateid = :cid and ison = '1'";
        $params = [':cid' => $cid];
        $model = Product::find()->where($where, $params);
        $all = $model->asArray()->all();
        $count = $model->count();
        $pageSize = Yii::$app->params['pageSize']['frontproduct'];
        $pager = new Pagination(['totalCount' => $count, 'pageSize' => $pageSize]);
        $all = $model->offset($pager->offset)->limit($pager->limit)->asArray()->all();

        $tui = $model->where($where . ' and istui = \'1\'', $params)->orderby('createtime desc')->limit(5)->asArray()->all();
        $hot = $model->where($where . ' and ishot = \'1\'', $params)->orderby('createtime desc')->limit(5)->asArray()->all();
        $sale = $model->where($where . ' and issale = \'1\'', $params)->orderby('createtime desc')->limit(5)->asArray()->all();

        return $this->render('index', ['sale' => $sale, 'tui' => $tui, 'hot' => $hot, 'all' => $all, 'pager' => $pager, 'count' => $count]);
    }

    public function actionDetail()
    {
        $this->layout = 'layout2';
        $productid = Yii::$app->request->get('productid');
        $product = Product::find()->where('productid = :pid', [':pid' => $productid])->asArray()->one();
        $data['all'] = Product::find()->where('ison = "1"')->orderby('createtime desc')->limit(7)->all();
        return $this->render('detail',['product' => $product, 'data' => $data]);
    }
}
