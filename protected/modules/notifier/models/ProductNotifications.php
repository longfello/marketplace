<?php

/**
 * This is the model class for table "notifications".
 *
 * The followings are the available columns in table 'notifications':
 * @property integer $id
 * @property integer $product_id
 * @property string $email
 */
class ProductNotifications extends CActiveRecord
{
		/**
		 * Returns the static model of the specified AR class.
		 * @param string $className active record class name.
		 * @return ProductNotifications the static model class
		 */
		public static function model($className=__CLASS__)
		{
			return parent::model($className);
		}

		/**
		 * @return string the associated database table name
		 */
		public function tableName()
		{
			return 'notifications';
		}

		/**
		 * @return array validation rules for model attributes.
		 */
		public function rules()
		{
			return array(
				array('email', 'required'),
				array('email', 'length', 'max'=>255),
				array('email', 'email'),
			);
		}

		/**
		 * @return array relational rules.
		 */
		public function relations()
		{
			return array(
				'product' => array(self::BELONGS_TO, 'StoreProduct', 'product_id')
			);
		}

		/**
		 * @return array customized attribute labels (name=>label)
		 */
		public function attributeLabels()
		{
			return array(
				'id'                   => 'ID',
				'product_id'           => Yii::t('NotifierModule', 'Продукт'),
				'product'              => Yii::t('NotifierModule', 'Продукт'),
				'product_quantity'     => Yii::t('NotifierModule', 'Количество'),
				'product_availability' => Yii::t('NotifierModule', 'Доступность'),
				'name'                 => Yii::t('NotifierModule', 'Название'),
				'email'                => Yii::t('NotifierModule', 'Email'),
				'totalEmails'          => Yii::t('NotifierModule', 'Количество подписчиков')
			);
		}

		public function getTotalEmails()
		{
			return ProductNotifications::model()->countByAttributes(array(
				'product_id' => $this->product_id
			));
		}

		/**
		 * Retrieves a list of models based on the current search/filter conditions.
		 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
		 */
		public function search()
		{
			$criteria=new CDbCriteria;
			$criteria->group = 'product_id';
			$criteria->with  = 'product';

			$criteria->compare('id',$this->id);
			$criteria->compare('product_id',$this->product_id);
			$criteria->compare('email',$this->email,true);

      if (Yii::app()->user->getIsManager()) {
        $criteria->join = "INNER JOIN StoreProduct p ON p.id = ".$this->tableAlias.".product_id";
        $criteria->addCondition("p.market_id IN (".implode(',',Yii::app()->user->getUserMarketsIds()).")");
      }

			return new CActiveDataProvider($this, array(
				'criteria'=>$criteria,
			));
		}

		/**
		 * Check if email exists in list for current product
		 */
		public function hasEmail()
		{
			return ProductNotifications::model()->countByAttributes(array(
				'email'      => $this->email,
				'product_id' => $this->product_id
			)) > 0;
		}

}