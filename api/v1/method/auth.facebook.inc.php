<?php

/*!
 * QA Script v2.0
 *
 * http://ifsoft.com.ua, http://ifsoft.co.uk
 * qascript@ifsoft.co.uk
 *
 * Copyright 2012-2015 Demyanchuk Dmitry (https://vk.com/dmitry.demyanchuk)
 */

if (!empty($_POST)) {

    $clientId = isset($_POST['clientId']) ? $_POST['clientId'] : 0;

    $facebookId = isset($_POST['facebookId']) ? $_POST['facebookId'] : 0;

    $gcm_regId = isset($_POST['gcm_regId']) ? $_POST['gcm_regId'] : '';

    $gcm_regId = helper::clearText($gcm_regId);
    $gcm_regId = helper::escapeText($gcm_regId);

    $clientId = helper::clearInt($clientId);

    $facebookId = helper::clearText($facebookId);
    $facebookId = helper::escapeText($facebookId);

    $access_data = array("error" => true,
                         "error_code" => ERROR_UNKNOWN);

    $helper = new helper($dbo);

    $accountId = $helper->getUserIdByFacebook($facebookId);

    if ($accountId != 0) {

        $auth = new auth($dbo);
        $access_data = $auth->create($accountId, $clientId);

        if ($access_data['error'] === false) {

            $account = new account($dbo, $accountId);
            $access_data['account'] = array();

            array_push($access_data['account'], $account->get());

            if (strlen($gcm_regId) != 0) {

                $account->setGCM_regId($gcm_regId);
            }
        }
    }

    echo json_encode($access_data);
    exit;
}
