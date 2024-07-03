<?php
namespace app\models;
use Yii;
use yii\base\Model;

class KandangForm extends Model
{
    public $name;
    public $location;
    public $description;

    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['name', 'location'], 'required'],
        ];
    }
}

