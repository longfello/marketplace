<?php

Yii::import('application.modules.users.models.TrackerThemes');

/**
 * This is the model class for table "TrackerMessages".
 *
 * The followings are the available columns in table 'TrackerMessages':
 * @property string $id
 * @property string $theme_id
 * @property string $text
 * @property string $created
 * @property string $is_user
 */
class TrackerMessages extends BaseModel
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'TrackerMessages';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('text', 'required'),
			array('id, theme_id', 'length', 'max'=>20),
			array('created', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, theme_id, text, created, is_user', 'safe', 'on'=>'search'),
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
      'theme'=>array(self::BELONGS_TO, 'TrackerThemes', 'theme_id')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'theme_id' => 'Тема',
			'text' => 'Текст',
			'created' => 'Created',
      'is_user' => 'Добавлено пользователем'
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
		$criteria->compare('theme_id',$this->theme_id,true);
		$criteria->compare('text',$this->text,true);
		$criteria->compare('created',$this->created,true);
		$criteria->compare('is_user',$this->is_user,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TrackerMessages the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

  public function afterSave()
  {
    if($this->isNewRecord)
    {
      $this->theme->status = 'new';
      $this->theme->last_msg = new CDbExpression('NOW()');
      $this->theme->save();
    }

    return parent::afterSave();
  }
}
