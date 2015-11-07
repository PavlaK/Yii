<?php

/**
 * This is the model class for table "reader".
 *
 * The followings are the available columns in table 'reader':
 * @property integer $id
 * @property string $reader_name
 * @property string $birth_date
 * @property string $email
 * @property integer $occupation_id
 *
 * The followings are the available model relations:
 * @property Occupation $occupation
 */
class Reader extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'reader';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('occupation_id', 'numerical', 'integerOnly'=>true),
			array('reader_name', 'length', 'max'=>50),
			array('email', 'length', 'max'=>20),
			array('birth_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, reader_name, birth_date, email, occupation_id', 'book_name', 'safe', 'on'=>'search'),
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
			'occupation' => array(self::BELONGS_TO, 'Occupation', 'occupation_id'),
			'loan' => array(self::HAS_MANY, 'Loan', 'reader_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'reader_name' => 'Reader Name',

			'birth_date' => 'Birth Date',
			'email' => 'Email',
			'occupation_id' => 'Occupation',
			'loan_id' => 'Loan Id',
		);
	}
//	public function RelateBookName ()
//	{
//		foreach ($this->loan as $loans) {
//
//			$book_name[] = $loans->item->book->book_name;
//
//			return implode(', ', $book_name);
////			var_dump(implode(', ', $book_name));die('dsa');
//		}
//
//	}

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
//		$criteria->with=array(
//			'loan' => array(
//				'together' => true,
//			),
//		);

		$criteria->compare('t.id',$this->id);
		$criteria->compare('t.reader_name',$this->reader_name,true);
//		$criteria->compare('loan.id',$this->id,true);
//		$criteria->compare('loan.book_name',$this->book_name,true);
		$criteria->compare('t.birth_date',$this->birth_date,true);
		$criteria->compare('t.email',$this->email,true);
		$criteria->compare('t.occupation_id',$this->occupation_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Reader the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
