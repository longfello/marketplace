<?php

/**
 * This is the model class for table "GearmanTasks".
 *
 * The followings are the available columns in table 'GearmanTasks':
 * @property string $slug
 * @property string $tid
 * @property int $saveStatus
 * @property string $status
 * @property int $created
 */
class GearmanTasks extends BaseModel
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'GearmanTasks';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('slug, tid', 'required'),
			array('slug, tid', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('slug, tid', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'slug' => Yii::t('Gearman', 'Псевдоним задачи'),
			'tid' => Yii::t('Gearman', 'Внутренний идентификатор задачи'),
			'saveStatus' => Yii::t('Gearman', 'Не удалять после завершения'),
			'status' => Yii::t('Gearman', 'Статус задачи'),
			'created' => Yii::t('Gearman', 'Когда создана задача'),
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

		$criteria->compare('slug',$this->slug,true);
		$criteria->compare('tid',$this->tid,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * @return CDbConnection the database connection used for this class
	 */
	public function getDbConnection()
	{
		return Yii::app()->db_import;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return GearmanTasks the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
