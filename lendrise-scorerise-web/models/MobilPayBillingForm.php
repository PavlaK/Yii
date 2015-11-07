<?php
/**
 * Created by PhpStorm.
 * User: Nicolae Alcea
 * Date: 2015-08-06
 * Time: 10:56 AM
 */

namespace app\models;


use Yii;
use yii\base\Model;

class MobilPayBillingForm extends Model
{
    const PERSOANA_FIZICA = 'person';
    const PERSOANA_JURIDICA = 'company';

    public $type;
    public $firstName;
    public $lastName;
    public $address;
    public $email;
    public $mobilePhone;
    public $fiscal_number;     // cnp
    public $identity_number;   // serie ci
    public $country;
    public $county;
    public $city;
    public $zip_code;
    public $bank;
    public $iban;
    public $coin;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // firstName, lastName, email, are required
            [['firstName', 'lastName', 'email'], 'required'],
            // email has to be a valid email address
            ['email', 'email'],
            [
                [
                    'firstName',
                    'lastName',
                    'address',
                    'mobilePhone',
                    'country',
                    'county',
                    'city',
                    'zip_code',
                    'bank',
                    'iban'
                ],
                'string',
                'max' => 255
            ],
            [['fiscal_number', 'identity_number'], 'string', 'max' => 50],
            // type
            ['type', 'in', 'range' => [self::PERSOANA_FIZICA, self::PERSOANA_JURIDICA]],
            [['coin'], 'safe'],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'type' => Yii::t('app', 'Type'),
            'firstName' => Yii::t('app', 'First name'),
            'lastName' => Yii::t('app', 'Last name'),
            'address' => Yii::t('app', 'Address'),
            'email' => Yii::t('app', 'Email'),
            'mobilePhone' => Yii::t('app', 'Mobile phone'),
            'fiscal_number' => Yii::t('app', 'Fiscal number'),
            'identity_number' => Yii::t('app', 'Identity number'),
            'country' => Yii::t('app', 'Country'),
            'county' => Yii::t('app', 'County'),
            'city' => Yii::t('app', 'City'),
            'zip_code' => Yii::t('app', 'Zip code'),
            'bank' => Yii::t('app', 'Bank'),
            'iban' => Yii::t('app', 'IBAN'),
            'coin' => Yii::t('app', 'Coin'),
        ];
    }

}