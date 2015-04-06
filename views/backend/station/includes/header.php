<div id="header">
    <span class="name"></span>
    <ul class="menubar">
        <li><a class="<?php echo getCurrentTab('b', 'hotspot', true) ?>" href="<?php echo CONTEXT_PATH; ?>/backend/hotspot/orders">Hotspot</a></li>
        <li><a class="<?php echo getCurrentTab('b', 'station', true) ?>" href="<?php echo CONTEXT_PATH; ?>/backend/station/orders">Station</a></li>
        <li><a class="<?php echo getCurrentTab('b', 'hotel', true) ?>" href="<?php echo CONTEXT_PATH; ?>/backend/hotel/orders">Hotel</a></li>
        <li><a class="<?php echo getCurrentTab('b', 'prepaid', true) ?>" href="<?php echo CONTEXT_PATH; ?>/backend/prepaid/orders">Prepaid Users</a></li>
        <li><a class="<?php echo getCurrentTab('b', 'plantation-city', true) ?>" href="<?php echo CONTEXT_PATH; ?>/backend/plantation-city/orders">Plantation City</a></li>
        <li><a class="<?php echo getCurrentTab('b', 'management', true) ?>" href="<?php echo CONTEXT_PATH; ?>/backend/management/users">Management</a></li>
    </ul>
    <div class="clear"></div>
    <span class="username">Logged in as Admin
        <a href="<?php echo CONTEXT_PATH . '/login' ?>">Logout</a>
        <a href="<?php echo CONTEXT_PATH . '/change-password' ?>">Change Password</a>
    </span>
</div>