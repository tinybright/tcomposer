<body ng-app="<?=Yii::app()->name.'App'?>">
<div class="header" ng-class="'show'">
    <div class="logo pull-left">
        <a href="<?=Yii::app()->createUrl('/gen/default/index')?>" class="link-logo">
            <img src="<?=Images::LOGO?>" class="img-logo img-responsive">
        </a>
    </div>
    <div class="user pull-right">
        <div class="user-infos">
            <div class="avatar">
                <img ng-src="{{(Session.user.avatar) ? Session.user.avatar : '<?=Images::DEFAULT_AVATAR?>'}}" class="img-responsive img-avatar">
            </div>
            <div class="user-name text-limit">{{Session.user.name}} <span class="caret"></span></div>
        </div>
        <ul class="umenu-list list-unstyled">
            <li class="umenu" ng-repeat="umenu in RMC.menuList[RMC.menuList.length-1][2]" ng-click="RMC.active=RMC.menuList.length-1;RMC.activeSubMenu($index);">
                <div class="icon-umenu">
                    <img ng-src="{{umenu[1][2]}}" class="img-icon img-responsive">
                    <img ng-src="{{umenu[1][1]}}" class="img-icon-active img-responsive">
                </div>
                <div class="text-umenu">{{umenu[0]}}</div>
            </li>
        </ul>
    </div>
    <ul class="nav-list list-inline pull-right">
        <li class="nav-header" ng-repeat="menu in RMC.menuList" ng-if="!menu[3]" ng-click="RMC.activeMenu($index)" ng-class="{'active' : $index==RMC.active}">
            <div class="icon-nav">
                <img ng-src="{{menu[1][0]}}" class="img-icon img-responsive">
                <img ng-src="{{menu[1][1]}}" class="img-icon-active img-responsive">
            </div>
            <div class="text-nav">{{menu[0]}}</div>
        </li>
    </ul>
</div>
<div class="main-body">
    <div class="side-bar pull-left" ng-class="'show'" ng-style="{'min-height' : RMC.getSidebarMinHeight()}">
        <ul class="sidenav-list list-unstyled">
            <li class="nav-side" ng-repeat="submenu in RMC.menuList[RMC.active][2]" ng-if="!submenu[3]" ng-class="{'active' : $index==RMC.subActive}" ng-click="RMC.activeSubMenu($index)">
                <div class="icon-nav">
                    <img ng-src="{{submenu[1][0]}}" class="img-icon img-responsive">
                    <img ng-src="{{submenu[1][1]}}" class="img-icon img-icon-active img-responsive">
                </div>
                <div class="text-nav">{{submenu[0]}}</div>
            </li>
            <li class="nav-side nav-placeholder"></li>
            <li class="nav-side nav-placeholder-bottom"></li>
        </ul>
    </div>
    <div class="main-content scroll-cotainer-x" ng-view></div>
    <div class="loading-page" ng-show="routeLoading">
        <span class="ani-wheeling glyphicon glyphicon-refresh"></span>
    </div>
</div>
<div id="odate4clone" class="odate-picker">
    <div class="line-year text-center">
        <div class="to-left glyphicon glyphicon-menu-left pull-left"></div>
        <div class="year pull-left"></div>
        <div class="to-right glyphicon glyphicon-menu-right pull-left"></div>
    </div>
    <div class="line-select">
        <ul class="month-list list-inline">
            <li class="month">
                <div class="text-month">1月</div>
            </li>
            <li class="month">
                <div class="text-month">2月</div>
            </li>
            <li class="month">
                <div class="text-month">3月</div>
            </li>
            <li class="month">
                <div class="text-month">4月</div>
            </li>
            <li class="month">
                <div class="text-month">5月</div>
            </li>
            <li class="month">
                <div class="text-month">6月</div>
            </li>
            <li class="month">
                <div class="text-month">7月</div>
            </li>
            <li class="month">
                <div class="text-month">8月</div>
            </li>
            <li class="month">
                <div class="text-month">9月</div>
            </li>
            <li class="month">
                <div class="text-month">10月</div>
            </li>
            <li class="month">
                <div class="text-month">11月</div>
            </li>
            <li class="month">
                <div class="text-month">12月</div>
            </li>
        </ul>
    </div>
</div>
</body>
<script type="text/javascript">
    <?
    $json = 'null';
    if(!Yii::app()->user->isGuest){
        $user = User::model()->findByPk(Yii::app()->user->id);
        if($user && !$user->deleted){
            $attrs = BaseUtil::obj2Array($user);
            /*$attrs['avatar'] = ImageUtil::getAvatar(@$user->avatar);*/
            $json = json_encode($attrs);
        }else{
            $user = new User();
            $user->realname = "s";
            $user->id = 999;
        }
    }else{
        $user = new User();
        $user->realname = "s";
        $user->id = 999;
    }
    ?>
    var __USER = <?=$json?>;
    var __ENDPOINT = "<?=AliyunUtil::$endpoint?>";
    var __ACTION_VERB = <?=json_encode(RightUtil::$ACTION_VERB)?>;
</script>