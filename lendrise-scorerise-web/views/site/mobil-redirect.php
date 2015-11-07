<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $paymentUrl string */
/* @var $objPmReqCard app\components\mobilPay\Payment\Request\Mobilpay_Payment_Request_Card */
?>

<form name="frmPaymentRedirect" method="post" action="<?= $paymentUrl; ?>">
    <input type="hidden" name="env_key" value="<?= $objPmReqCard->getEnvKey(); ?>"/>
    <input type="hidden" name="data" value="<?= $objPmReqCard->getEncData(); ?>"/>

    <p><?= Yii::t('app', 'Vei redirectat catre pagina de plati securizata a mobilpay.ro') ?></p>

    <p><input type="image" src="images/mobilpay.gif"/></p>
</form>
<script type="text/javascript" language="javascript">
    window.setTimeout(document.frmPaymentRedirect.submit(), 5000);
</script>