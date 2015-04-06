<?php

$pm = PersistenceManager::getConnection();
$query = $pm->getQueryBuilder('CardOrder');
$sql= "select c.id,c.transaction_id,c.referrer,c.method,c.status,c.merchant_reference,c.response_code,c.response_description,c.creation_date,p.amount from ".CardOrder::GetDSN()." as c inner join ".Plan::GetDSN()." as p on c.plan = p.name";

$cardOrders = $query->executeQuery($sql, array(), 0, 100000);

$pm->beginTransaction();

for($i = 0; $i < $cardOrders->count(); $i++){
    $paymentGatewayTransaction = new PaymentGatewayTransaction();
    if(strlen($cardOrders[$i]->getValue('transaction_id')) > 0){
        $paymentGatewayTransaction->setValue('id', $cardOrders[$i]->getValue('transaction_id'));
    }
    else{
        //$paymentGatewayTransaction->setValue('id', $cardOrders[$i]->getValue('id'));
    }
    $paymentGatewayTransaction->setValue('source', 'CardOrder');
    $paymentGatewayTransaction->setValue('amount', $cardOrders[$i]->getValue('amount'));
    $paymentGatewayTransaction->setValue('referrer', $cardOrders[$i]->getValue('referrer'));
    $paymentGatewayTransaction->setValue('method', $cardOrders[$i]->getValue('method'));
    $paymentGatewayTransaction->setValue('status', $cardOrders[$i]->getValue('status'));
    $paymentGatewayTransaction->setValue('merchant_reference', $cardOrders[$i]->getValue('merchant_reference'));
    $paymentGatewayTransaction->setValue('response_code', $cardOrders[$i]->getValue('response_code'));
    $paymentGatewayTransaction->setValue('response_description', $cardOrders[$i]->getValue('response_description'));
    $paymentGatewayTransaction->setValue('creation_date', $cardOrders[$i]->getValue('creation_date'));
    $pm->save($paymentGatewayTransaction);
    
    $cardOrder = $pm->getObjectById('CardOrder', $cardOrders[$i]->getValue('id'));
    $cardOrder->setValue('transaction_id', $paymentGatewayTransaction->getValue('id'));
    $pm->save($cardOrder);
}

$pm->commit();

echo "Done";


