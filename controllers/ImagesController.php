<?php
/**
 * Created by PhpStorm.
 * User: costa
 * Date: 25.06.14
 * Time: 15:35
 */

namespace rico\yii2images\controllers;

use yii\web\Controller;
use yii;
use rico\yii2images\models\Image;
use \rico\yii2images\ModuleTrait;

class ImagesController extends Controller
{
    use ModuleTrait;
    public function actionIndex()
    {
        echo "Hello, man. It's ok, dont worry.";
    }

    public function actionTestTest()
    {
        echo "Hello, man. It's ok, dont worry.";
    }


    /**
     *
     * All we need is love. No.
     * We need item (by id or another property) and alias (or images number)
     * @param $item
     * @param $dirtyAlias
     *
     */
    public function actionImageByItemAndAlias($item='', $dirtyAlias)
    {
        $dotParts = explode('.', $dirtyAlias);
        if(!isset($dotParts[1])){
            throw new \yii\web\HttpException(404, 'Image must have extension');
        }
        $dirtyAlias = $dotParts[0];

        $size = isset(explode('_', $dirtyAlias)[1]) ? explode('_', $dirtyAlias)[1] : false;
        $alias = isset(explode('_', $dirtyAlias)[0]) ? explode('_', $dirtyAlias)[0] : false;
        $image = $this->getModule()->getImage($item, $alias);

        if($image->getExtension() != $dotParts[1]){
            throw new \yii\web\HttpException(404, 'Image not found (extension)');
        }
        
        if (empty($image)) {
            throw new \yii\web\HttpException(404, 'There is no images');
        }
        
        $name = !empty($image->name) ? $image->name : $image->urlAlias;

        return Yii::$app->response->sendContentAsFile(
            $image->getContent($size),
            $name,
            [
                'mimeType' => $image->getMimeType($size),
                'inline' => true
            ]
        );
    }
}
