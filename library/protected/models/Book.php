<?php

/**
 * This is the model class for table "book".
 *
 * The followings are the available columns in table 'book':
 * @property integer $id
 * @property string $book_name
 * @property integer $publisher_id
 * @property integer $copy
 *
 * The followings are the available model relations:
 * @property Publisher $publisher
 * @property BookAuthor[] $bookAuthors
 * @property BookCategory[] $bookCategories
 * @property Library[] $libraries
 * @property Library[] $libraries1
 */
class Book extends CActiveRecord
{
    public $book_name;
    public $reader_name;
    public $author_name;
    public $author_id;
    public $publisher_name;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'book';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('publisher_id, copy', 'numerical', 'integerOnly' => true),
            array('book_name', 'length', 'max' => 50),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, book_name, publisher_id, publisher_name, author_id, author_name, reader_name, copy', 'safe', 'on' => 'search'),
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
            'publisher' => array(self::BELONGS_TO, 'Publisher', 'publisher_id'),
            'bookAuthors' => array(self::HAS_MANY, 'BookAuthor', 'book_id'),
            'bookCategories' => array(self::HAS_MANY, 'BookCategory', 'book_id'),
            'library' => array(self::HAS_ONE, 'Library', 'book_id'),
            'libraries1' => array(self::HAS_MANY, 'Library', 'copy'),
            'authors' => array(self::MANY_MANY, 'Author', 'book_author(book_id, author_id)'),
            'category' => array(self::MANY_MANY, 'Category', 'book_category(book_id, category_id)'),
            'item' => array(self::HAS_MANY, 'Item', 'book_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'book_name' => 'Book Name',
            'author_id' => 'Author id',
            'author_name' => 'Author',
            'publisher_id' => 'Publisher',
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
    public function RelatedAuthorName()
    {
//		//EITHER THIS
//		$out=CHtml::listData($this->authors,'id','author_name');
//		return implode(',', $out);
//
//		 OR THIS. BOTH WORK.
        foreach ($this->authors as $author) {

            $author_name[] = "$author->author_name";
            }
            return implode(',<br />', $author_name);

    }
    public function relatedReaders()
    {
//		$model=Book::model()->findByPk($id);

        foreach ($this->item as $loans) {

            foreach ($loans->loans as $loan) {
//                var_dump($loan);die('dsa');
                $reader_name[] = CHtml::link($loan->reader->reader_name, Yii::app()->createUrl("reader/readersLoans/",array("id"=>$loan->reader->id)));
            }
        }
        return implode('<br/> ', $reader_name);


    }
    public function search()
    {

        $criteria = new CDbCriteria;
        $criteria->with = array(
            'publisher',
            'authors' => array(
                'together' => true,
            ),

        );
        $criteria->compare('t.id', $this->id);
        $criteria->compare('t.book_name', $this->book_name, true);
        $criteria->compare('authors.author_name', $this->author_name, true);
        $criteria->compare('publisher.publisher_name', $this->publisher_name, true);

        return new CActiveDataProvider(get_class($this), array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Book the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * Crete a method (non static) that returns a list of books
     * that actually are available to be loaned (Library.Copies > 0)
     */
    public function findAvailableBooks (){

        $criteria = new CDbCriteria();
        $criteria->with = array('library'); // will join in the Library table
        $criteria->addCondition('library.Copy > 0');

        $books = Book::model()->findAll($criteria);

        return $books;


    }
    public function bookNameLink()
    {
       return  CHtml::link($this->book_name, Yii::app()->createUrl("book/relatedReaders/",array("id"=>$this->id)));
	}

}
