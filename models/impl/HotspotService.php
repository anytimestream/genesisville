<?php

class HotspotService {

    public static function NewSubscription() {
        try {
            $assertProperties = new AssertProperties();
            $assertProperties->addProperty($_POST, 'plan');
            $assertProperties->assert();

            $pm = PersistenceManager::NewPersistenceManager();
            $query = $pm->getQueryBuilder('Card');
            $row = $query->execute("select count(*) from " . Card::GetDSN() . " where plan = ? and status = ?", array($_POST['plan'], 0))->fetch();

            if ($row[0] == 0) {
                $_GET['view'] = 'card-not-found.php';
                return;
            }

            $query = $pm->getQueryBuilder('Plan');
            $plans = $query->executeQuery("select name,description,amount from " . Plan::GetDSN() . " where name = ?", array($_POST['plan']), 0, 1);
            $_GET['plan'] = $plans[0];

            $_GET['phone'] = '';
            $_GET['phone2'] = '';
            $_GET['email'] = '';

            if (isset($_POST['phone'])) {
                $_GET['phone'] = $_POST['phone'];
                $_GET['phone2'] = $_POST['phone2'];
                $_GET['email'] = $_POST['email'];
            }

            if (!isset($_POST['email'])) {
                $_GET['view'] = 'subscription-form.php';
                return;
            }
            if (strlen($_POST['phone']) < 11 || substr($_POST['phone'], 0, 1) != "0" || !is_numeric($_POST['phone']) || $_POST['phone'] != $_POST['phone2'] || !preg_match('/^([*+!.&#$¦\'\\%\/0-9a-z^_`{}=?~:-]+)@(([0-9a-z-]+\.)+[0-9a-z]{2,4})$/i', $_POST['email'])) {
                $_GET['view'] = 'subscription-form.php';
                return;
            }

            $cardOrder = new CardOrder();
            $cardOrder->setValue('plan', $plans[0]->getValue('name'));
            $cardOrder->setValue('email', $_POST['email']);
            $cardOrder->setValue('phone', $_POST['phone']);
            $pm->save($cardOrder);

            PhoneNumberService::DoInsert($cardOrder->getValue('phone'), 'Hotspot', 'Hotspot');

            $_GET['card-order'] = $cardOrder;

            $_GET['view'] = 'payment-form.php';
        } catch (Exception $e) {
            $_GET['error'] = $e->getMessage();
            $_GET['view'] = 'error.php';
        }
    }

    public static function NewSubscription2() {
        try {
            $assertProperties = new AssertProperties();
            $assertProperties->addProperty($_POST, 'plan');
            $assertProperties->assert();

            $pm = PersistenceManager::NewPersistenceManager();
            $query = $pm->getQueryBuilder('Card');
            $row = $query->execute("select count(*) from " . Card::GetDSN() . " where plan = ? and status = ?", array($_POST['plan'], 0))->fetch();

            if ($row[0] == 0) {
                $_GET['view'] = 'card-not-found.php';
                return;
            }

            $query = $pm->getQueryBuilder('Plan');
            $plans = $query->executeQuery("select name,description,amount from " . Plan::GetDSN() . " where name = ?", array($_POST['plan']), 0, 1);
            $_GET['plan'] = $plans[0];

            $_GET['phone'] = '';
            $_GET['phone2'] = '';
            $_GET['email'] = '';

            if (isset($_POST['phone'])) {
                $_GET['phone'] = $_POST['phone'];
                $_GET['phone2'] = $_POST['phone2'];
                $_GET['email'] = $_POST['email'];
            }

            if (!isset($_POST['email'])) {
                $_GET['view'] = 'subscription-form.php';
                return;
            }
            if (strlen($_POST['phone']) < 11 || substr($_POST['phone'], 0, 1) != "0" || !is_numeric($_POST['phone']) || $_POST['phone'] != $_POST['phone2'] || !preg_match('/^([*+!.&#$¦\'\\%\/0-9a-z^_`{}=?~:-]+)@(([0-9a-z-]+\.)+[0-9a-z]{2,4})$/i', $_POST['email'])) {
                $_GET['view'] = 'subscription-form.php';
                return;
            }

            $paymentGatewayTransaction = new PaymentGatewayTransaction();
            $paymentGatewayTransaction->setValue('source', "CardOrder");
            $paymentGatewayTransaction->setValue('amount', $plans[0]->getValue('amount'));

            $cardOrder = new CardOrder();
            $cardOrder->setValue('plan', $plans[0]->getValue('name'));
            $cardOrder->setValue('email', $_POST['email']);
            $cardOrder->setValue('phone', $_POST['phone']);
            $cardOrder->setValue('transaction_id', $paymentGatewayTransaction->getValue('id'));
            $pm->save($paymentGatewayTransaction);
            $pm->save($cardOrder);

            PhoneNumberService::DoInsert($cardOrder->getValue('phone'), 'Hotspot', 'Hotspot');

            $_GET['card-order'] = $cardOrder;

            $_GET['view'] = 'payment-form3.php';
        } catch (Exception $e) {
            $_GET['error'] = $e->getMessage();
            $_GET['view'] = 'error.php';
        }
    }

    public static function AcceptPayment() {
        try {

            $assertProperties = new AssertProperties();
            $assertProperties->addProperty($_POST, 'transaction_id');
            $assertProperties->assert();

            $json = file_get_contents('https://voguepay.com/?v_transaction_id=' . $_POST['transaction_id'] . '&type=json');
            //MailService::SendError("Hotspot - Accept Payment JSON", $json);
            $transaction = json_decode($json, true);
            if ($transaction['total'] == 0)
                throw new Exception('Invalid Total');

            $pm = PersistenceManager::NewPersistenceManager();
            $cardOrder = $pm->getObjectById('CardOrder', $transaction['merchant_ref']);
            if ($cardOrder == null) {
                throw new Exception("Order not found");
            }

            if (strlen($cardOrder->getValue('transaction_id')) > 0) {
                throw new Exception("Already completed transaction");
            }

            $plan = $pm->getObjectByColumn('Plan', 'name', $cardOrder->getValue('plan'));
            if ($plan == null) {
                throw new Exception("Order not found");
            }

            $query = $pm->getQueryBuilder('Card');
            $sql = " select * from " . Card::GetDSN() . " where plan = ? and status = ?";
            $cards = $query->executeQuery($sql, array($plan->getValue('name'), 0), 0, 1);
            if ($cards->count() == 0) {
                throw new Exception("Card not found");
            }
            $card = $cards[0];

            $pm->beginTransaction();

            $payment = new Payment();
            $payment->setValue('amount', $plan->getValue('amount'));
            $payment->setValue('status', 1);
            $payment->setValue('type', 'Hotspot');
            $payment->setValue('method', 'ePayment');
            $payment->setValue('related_to', $cardOrder->getValue('id'));
            $pm->save($payment);

            $cardOrder->setValue('transaction_id', $transaction['transaction_id']);
            $cardOrder->setValue('method', $transaction['method']);
            $cardOrder->setValue('referrer', $transaction['referrer']);
            $successful = false;
            if ($transaction['total'] == $plan->getValue('amount') && $transaction['status'] == 'Approved') {
                $cardOrder->setValue('status', 3);
                $successful = true;
            } else if ($transaction['total'] == $plan->getValue('amount') && $transaction['status'] == 'Disputed') {
                $cardOrder->setValue('status', 1);
            } else if ($transaction['total'] == $plan->getValue('amount') && $transaction['status'] == 'Failed') {
                $cardOrder->setValue('status', 2);
            }
            $pm->save($cardOrder);

            if ($successful) {
                $card->setValue('transaction_id', $pm->getObjectById('Payment', $payment->getValue('id'))->getValue('transaction_id'));
                $card->setValue('status', 1);
                $pm->save($card);
            }

            $pm->commit();

            if ($successful) {
                SMSService::SendHotspotTicketByOrderId($cardOrder->getValue('id'), true);
            }

            $_GET['view'] = 'payment-accepted.php';
        } catch (Exception $e) {
            $_GET['error'] = $e->getMessage();
            MailService::SendError("Hotspot - Accept Payment", $e->getMessage());
        }
    }

    public static function AcceptPayment2() {
        $_GET['err'] = "";
        try {

            $assertProperties = new AssertProperties();
            $assertProperties->addProperty($_POST, 'gtpay_tranx_id');
            $assertProperties->addProperty($_POST, 'gtpay_tranx_amt');
            $assertProperties->assert();

            $json = file_get_contents(GTPAY_TRANS_STATUS . 'mertid=1410&amount=' . $_POST['gtpay_tranx_amt'] . '&tranxid=' . $_POST['gtpay_tranx_id'] . '&hash=' . hash("sha512", "1410" . $_POST['gtpay_tranx_id'] . GTPAY_HASH_KEY));
            $transaction = json_decode($json, true);
            $_GET['err'] = $json;

            $pm = PersistenceManager::NewPersistenceManager();
            $paymentGatewayTransaction = $pm->getObjectById("PaymentGatewayTransaction", $_POST['gtpay_tranx_id']);

            if ($paymentGatewayTransaction != null && strcmp($paymentGatewayTransaction->getValue('source'), "PrepaidOrder") == 0) {
                PrepaidOrderService::SubscriptionNotify($paymentGatewayTransaction, $transaction);
                exit;
            } else {
                
                if(strlen($paymentGatewayTransaction->getValue('response_code')) > 0){
                    throw new Exception("Already completed transaction");
                }
                
                $cardOrder = $pm->getObjectByColumn('CardOrder', 'transaction_id', $_POST['gtpay_tranx_id']);
                if ($cardOrder == null) {
                    throw new Exception("Order not found");
                }

                $plan = $pm->getObjectByColumn('Plan', 'name', $cardOrder->getValue('plan'));
                if ($plan == null) {
                    throw new Exception("Order not found");
                }

                $query = $pm->getQueryBuilder('Card');
                $sql = " select * from " . Card::GetDSN() . " where plan = ? and status = ?";
                $cards = $query->executeQuery($sql, array($plan->getValue('name'), 0), 0, 1);
                if ($cards->count() == 0) {
                    throw new Exception("Card not found");
                }
                $card = $cards[0];

                $pm->beginTransaction();

                $payment = new Payment();
                $payment->setValue('amount', $plan->getValue('amount'));
                $payment->setValue('status', 1);
                $payment->setValue('type', 'Hotspot');
                $payment->setValue('method', 'ePayment');
                $payment->setValue('related_to', $cardOrder->getValue('id'));
                $pm->save($payment);

                $paymentGatewayTransaction->setValue('merchant_reference', $transaction['MerchantReference']);
                $paymentGatewayTransaction->setValue('response_code', $transaction['ResponseCode']);
                $paymentGatewayTransaction->setValue('response_description', $transaction['ResponseDescription']);
                
                $successful = false;
                $_GET['transaction'] = $transaction;
                $_GET['card-order'] = $cardOrder;
                if (strcasecmp($transaction['ResponseCode'], "00") == 0) {
                    if (strcasecmp(number_format($transaction['Amount'], 2), number_format($plan->getValue('amount') * 100, 2)) == 0) {
                        $paymentGatewayTransaction->setValue('status', 3);
                        $successful = true;
                        $message = "Welcome to GenesisVille Hotspot Service.\n\n";
                        $message .= "Your Transaction was successful.\n";
                        $message .= "Your Hotspot Ticket has been sent to your Phone (" . $_GET['card-order']->getValue('phone') . ").\n";
                        $message .= "Transaction Reference: " . $cardOrder->getValue('id') . ".\n\n";
                        $message .= "Thanks for your patronage.\n";
                        MailService::SendMail("Your Transaction", $message, $cardOrder->getValue('email'));
                        $message = "Hotspot Ticket Sold.\n\n";
                        $message .= "Hotspot Ticket has been sent to: " . $_GET['card-order']->getValue('phone') . ".\n";
                        $message .= "Transaction Reference: " . $cardOrder->getValue('transaction_id') . ".\n\n";
                        MailService::SendMail("GenesisWireless", $message, "genesisville@yahoo.com");
                        $_GET['view'] = 'payment-accepted2.php';
                    } else {
                        $message = "Welcome to GenesisVille Hotspot Service.\n\n";
                        $message .= "Your Transaction was not successful.\n";
                        $message .= "Reason: Invalid Amount.\n";
                        $message .= "Transaction Reference: " . $cardOrder->getValue('transaction_id') . ".\n\n";
                        $message .= "Thanks for your patronage.\n";
                        MailService::SendMail("Your Transaction", $message, $cardOrder->getValue('email'));
                        $paymentGatewayTransaction->setValue('status', 2);
                        $_GET['view'] = 'payment-error.php';
                    }
                } else {
                    $message = "Welcome to GenesisVille Hotspot Service.\n\n";
                    $message .= "Your Transaction was not successful.\n";
                    $message .= "Reason: " . $transaction["ResponseDescription"] . ".\n";
                    $message .= "Transaction Reference: " . $cardOrder->getValue('transaction_id') . ".\n\n";
                    $message .= "Thanks for your patronage.\n";
                    MailService::SendMail("Your Transaction", $message, $cardOrder->getValue('email'));
                    $_GET['view'] = 'payment-rejected.php';
                    $paymentGatewayTransaction->setValue('status', 2);
                }

                $pm->save($paymentGatewayTransaction);

                if ($successful) {
                    $card->setValue('transaction_id', $pm->getObjectById('Payment', $payment->getValue('id'))->getValue('transaction_id'));
                    $card->setValue('status', 1);
                    $pm->save($card);
                }

                $pm->commit();

                if ($successful) {
                    SMSService::SendHotspotTicketByOrderId($cardOrder->getValue('id'), true);
                }
            }
        } catch (Exception $e) {
            $_GET['error'] = $e->getMessage();
            $_GET['view'] = 'payment-error.php';
            MailService::SendError("Hotspot - Accept Payment", $e->getMessage() . $_GET['err']);
            $message = "Welcome to GenesisVille Hotspot Service.\n\n";
            $message .= "Your Transaction was not successful.\n";
            $message .= "Reason: Internal Error.\n";
            $message .= "Transaction Reference: " . $_POST['gtpay_tranx_id'] . ".\n\n";
            $message .= "Thanks for your patronage.\n";
            MailService::SendMail("Your Transaction", $message, "chat4zeal@yahoo.com");
        }
    }

}
