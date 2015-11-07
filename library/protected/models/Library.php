<?php

/**
 * This is the model class for table "library".
 *
 * The followings are the available columns in table 'library':
 * @property integer $id
 * @property integer $book_id
 * @property string $introduction_date
 * @property integer $copy
 *
 * The followings are the available model relations:
 * @property Book $book
 * @property Book $copy0
 */
class Library extends CActiveRecord
{
	public $itemOut = 1;
	public $copyIn = 1;
	public $book_name;
	/**
	 * @return string the associated database table name
	 */

	public function tableName()
	{
		return 'library';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('book_id, copy', 'numerical', 'integerOnly'=>true),
			array('introduction_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, book_id, introduction_date, copy, copyIn, itemOut, book_name', 'safe', 'on'=>'search'),
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
			'book' => array(self::BELONGS_TO, 'Book', 'book_id'),
			'item' => array(self::HAS_MANY, 'Item', 'book_id'),
			'copy' => array(self::BELONGS_TO, 'Book', 'copy'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'book_id' => 'Book',
			'introduction_date' => 'Introduction Date',
			'copy' => 'Copy',
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
		$criteria->with=array(
			'book',
		);

		$criteria->compare('t.id',$this->id);
		$criteria->compare('book.book_name',$this->book_name, true);
		$criteria->compare('t.book_id',$this->book_id);
		$criteria->compare('t.introduction_date',$this->introduction_date,true);
		$criteria->compare('t.copy',$this->copy);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Library the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
