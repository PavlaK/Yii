<?php

/**
 * This is the model class for table "loan".*
 * The followings are the available columns in table 'loan':
 * @property integer $id
 * @property integer $reader_id
 *
 * The followings are the available model relations:
 * @property LoanItem[] $loanItems

 * @property Item[] $items
 */
class Loan extends CActiveRecord
{
	public $reader_name;
	public $id;
	public $book_id;
	public $book_name;
	public $total;
	public $totalCount;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'loan';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('reader_id', 'numerical', 'integerOnly'=>true),
			array('due_date,borrow_date', 'type', 'type' => 'date', 'message' => '{attribute}: is not a date!', 'dateFormat' => 'yyyy-MM-dd'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, reader_id, borrow_date, book_id, book_name, due_date, status, loan_copy, reader_name', 'safe', 'on'=>'search'),

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

			'reader' => array(self::BELONGS_TO, 'Reader', 'reader_id'),
			'loanItems' => array(self::HAS_MANY, 'LoanItem', 'loan_id'),
			'itemsLoan' => array(self::HAS_MANY, 'LoanItem', 'item_id'),
			'items' => array(self::MANY_MANY, 'Item', 'loan_item(loan_id, item_id)'),

		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Loan ID',
			'reader_id' => 'Reader ID',
			'reader_name' => 'Reader name',
			'book_name' => 'Book',
			'book_id' => 'Book',
			'item_id' => 'Item Id',
			'borrow_date' => 'Borrow Date',
			'due_date' => 'Due Date'
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


	public function beforeSave() {

		if ($this->isNewRecord)
			$this->borrow_date = new CDbExpression('NOW()');

		$this->status = 1;
		$areAllBooksReturned = true;
		foreach ($this->items as $item) {
			if ($item->status == 1) {
				$areAllBooksReturned = false;
			}
		}
		if ($areAllBooksReturned){
			$this->status = 2;
		}
		return parent::beforeSave();
	}

	protected function afterSave()
	{
		foreach ($this->items as $item) {
			$library = $item->book->library;
			if ($item->status == 1){
			$library->copy -=  1;}
			else {
			$library->copy += 1;
			}
			$library->save();
		 }
		return parent::afterSave();

//		var_dump(implode(', ', $copy));die('dsa');
	}

	public function RelateBookName ()
	{
		foreach ($this->items as $item) {

			$book_name[] = CHtml::link($item->book->book_name, Yii::app()->createUrl("book/relatedReaders/",array("id"=>$item->book_id)));
		}
		return implode(', ', $book_name);
	}


	public function search()
	{
		$criteria=new CDbCriteria;
		$criteria->with=array(
			'reader',
			'items' => array(
				'together' => true,
			),
		);

		$criteria->compare('t.id',$this->id);
		$criteria->compare('item.book_id',$this->book_id);
		$criteria->compare('t.reader_id',$this->reader_id);
		$criteria->compare('reader.reader_name', $this->reader_name);
		$criteria->compare('t.status',$this->status);;
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,

		));
	}
	public function topBooks(){

		$sql = 'SELECT book.id, book_name, COUNT(*)  AS total
     FROM loan
 	INNER JOIN loan_item ON loan.id = loan_item.loan_id
 	 INNER JOIN item ON item.id = loan_item.item_id
 	  INNER JOIN book ON book.id = item.book_id
    GROUP BY book_name
    ORDER BY total DESC
    LIMIT 5;';

		$rawData = Yii::app()->db->createCommand($sql);
		return new CSqlDataProvider($rawData, array(
			'sort' => array(
				'attributes' => array(
					'book.id',
					'book_name',
					'total'
				),
				'defaultOrder' => array(
					'total' => CSort::SORT_ASC, //default sort value
				),
			),
		));
	}


	public function topReaders() {
	$sql = 'SELECT reader.id,reader.reader_name, COUNT(book_id)  AS totalCount
     FROM item
 	 INNER JOIN loan_item ON item.id = loan_item.item_id
 	 INNER JOIN loan ON loan_item.loan_Id = loan.id
 	 INNER JOIN reader ON loan.reader_id= reader.id

   GROUP BY reader.reader_name
   ORDER BY totalCount DESC
   LIMIT 5;';

		$rawData = Yii::app()->db->createCommand($sql);

		return new CSqlDataProvider($rawData, array(
			'sort' => array(
				'attributes' => array(
					'id',
					'reader.reader_name',
					'totalCount'
				),
				'defaultOrder' => array(
					'totalCount' => CSort::SORT_ASC,
				),
			),

		));
//
//		$criteria = new CDbCriteria;
////		$criteria->compare('items.book.book_name',$this->book_name);
//		$criteria->with = array('reader');
//		$criteria->select="t.reader_id,(SELECT COUNT(reader_id) FROM loan) AS total";
//		$criteria->together = true;
////		$criteria->select = 'items.book.book_name';
////		$criteria->select ='*, loan.id, book_nae, COUNT(*) AS total';
////		$criteria->join = 'INNER JOIN items ON loan.id = loan_item.loan_id
//// 	 						INNER JOIN item ON item.id = loan_item.item_id
//// 	 						 INNER JOIN book ON book.id = item.book_id';
//		$criteria->group = 'reader_name';
//		$criteria->order = 'total';
//		return new CActiveDataProvider( 'Loan', array(
//			'criteria'=>$criteria,
//			'sort'=>array(
//				'attributes'=>array(
//
//					'*',
//				),
//			),
//		));
	}

	public function readerNameLink()
	{
		return  CHtml::link($this->reader->reader_name, Yii::app()->createUrl("reader/readersLoans/",array("id"=>$this->reader->id)));
	}
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Loan the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	public function behaviors() {
		return array('EAdvancedArBehavior' => array(
			'class' => 'application.extensions.EAdvancedArBehavior'));
	}


	public function validate($attributes = null, $clearErrors = true) {
		$isValid = parent::validate($attributes, $clearErrors);
		if ($attributes == null) {
			foreach ($this->items as $item) {
				if ($item->validate() == false) {
					$isValid = false;
					$this->addErrors($item->getErrors());
				}
			}
		}
		return $isValid;
	}


	public function save($runValidation = true, $attributes = null) {
		if ($runValidation) {
			if ($this->validate($attributes) == false)
				return false;
		}
		foreach ($this->items as $item) {
			$item->save(false);
		}
		return parent::save(false, $attributes);
	}

}
