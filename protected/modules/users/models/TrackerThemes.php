<?php

Yii::import('application.modules.users.models.User');
Yii::import('application.modules.users.models.TrackerMessages');

/**
 * This is the model class for table "TrackerThemes".
 *
 * The followings are the available columns in table 'TrackerThemes':
 * @property string $id
 * @property string $user_id
 * @property string $name
 * @property string $created
 * @property string $last_msg
 * @property string $status
 */
class TrackerThemes extends BaseModel
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'TrackerThemes';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, name', 'required'),
			array('user_id', 'length', 'max'=>20),
			array('name', 'length', 'max'=>100),
			array('status', 'length', 'max'=>10),
			array('created, last_msg', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, user_id, name, created, last_msg, status', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
      'messages'       => array(self::HAS_MANY, 'TrackerMessages', 'theme_id', 'order'=>'created DESC',),
      'user'=>array(self::BELONGS_TO, 'User', 'user_id')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'user_id' => 'Пользователь',
			'name' => 'Название',
			'created' => 'Дата создания',
			'last_msg' => 'Дата последнего сообщения',
			'status' => 'Статус',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('created',$this->created,true);
		$criteria->compare('last_msg',$this->last_msg,true);
		$criteria->compare('status',$this->status,true);

    $sort=new CSort;
    $sort->defaultOrder = $this->getTableAlias().'.last_msg DESC';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TrackerThemes the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
