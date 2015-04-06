<?php
require 'require-admin.php';
require 'models/impl/AgentService.php';

$views['agents'] = 'views/backend/prepaid/agents.php';
$views['agent-reload'] = 'views/backend/prepaid/agent-reload.php';
$views['agent-new'] = 'views/backend/prepaid/agent-new.php';

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'reload':
            AgentService::GetAgents();
            require $views['agent-reload'];
            break;
        case 'update':
            AgentService::DoUpdate();
            require $views['agent-reload'];
            break;
        case 'new':
            require $views['agent-new'];
            break;
        case 'insert':
            AgentService::DoInsert();
            require $views['agent-reload'];
            break;
        case 'delete':
            AgentService::DoDelete();
            break;
    }
    return;
}

AgentService::GetAgents();
require $views['agents'];
?>
