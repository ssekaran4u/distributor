<div id="preloader"><div id="status"><div class="spinner"></div></div></div> 
<?php  $_types =  $_SESSION['type']; ?>
<div class="left side-menu">
    <div class="_lgo">
        <a href="dashboard" class="logo">
            <img src="assets/images/logo.png" style="max-width: 80%; margin-left: 25px;">
        </a>
    </div>
    <div class="sidebar-inner slimscrollleft">
        <div id="sidebar-menu">
            <ul>
                <li class="menu-title">Main</li>
                <li>
                    <a href="dashboard" class="waves-effect">
                        <i class="dripicons-device-desktop"></i>
                        <span> Dashboard </span>
                    </a>
                </li>
                <?php if($_types==1): ?>

                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect">
                        <i class="fa fa-star"></i>
                        <span> Masters 
                            <span class="pull-right">
                                <i class="mdi mdi-chevron-right"></i>
                            </span> 
                        </span>
                    </a>
                    <ul class="list-unstyled">
                        <li><a href="manage-state">State Master</a></li>
                        <li><a href="manage-city">City Master</a></li>
                    </ul>
                </li>

                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect">
                        <i class="dripicons-view-list"></i>
                        <span> Catalog 
                            <span class="pull-right">
                                <i class="mdi mdi-chevron-right"></i>
                            </span> 
                        </span>
                    </a>
                    <ul class="list-unstyled">
                        <li><a href="manage-category">Categories</a></li>
                        <li><a href="manage-brand">Brand</a></li>
                        <li><a href="manage-items">Products</a></li>
                    </ul>
                </li>

                <!-- <li>
                    <a class="waves-effect" href="manage-shop" ><i class="dripicons-graph-line"></i><span> Shop </span></a>
                </li> -->
                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect">
                        <i class="fa fa-cloud-download"></i>
                        <span> Inventory 
                            <span class="pull-right">
                                <i class="mdi mdi-chevron-right"></i>
                            </span> 
                        </span>
                    </a>
                    <ul class="list-unstyled">
                        <li><a href="create-inventory">Create Inventory</a></li>
                        <li><a href="import-inventory">Import Inventory</a></li>
                        <li><a href="manage-inventory">Manage Inventory</a></li>
                    </ul>
                </li>
                
                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect"><i class="fa fa-address-book-o"></i> <span> Distributors  <span class="pull-right"><i class="mdi mdi-chevron-right"></i></span> <span id="_nadge" class="badge badge-pill badge-success pull-right"></span></span> </a>
                    <ul class="list-unstyled">
                        <li><a href="distributor">Add new distributor</a></li>
                        <li><a href="manage-distributors">Manage distributors</a></li>
                        <li><a href="distributor-invoice">Add Invoice</a></li>
                        <li><a href="distributor-invoice-list">Invoice List</a></li>
                        <li><a href="distributor-return">Add Sales Return</a></li>
                        <li><a href="distributor-return-list">List Sales Return</a></li>
                        <li><a href="distributor-payment">Payment</a></li>
                    </ul>
                </li>
                <?php endif; 
                if($_types==1 || $_types==2):
                ?>
                <li>
                    <a class="waves-effect" href="manage-dealer" ><i class="dripicons-user-group"></i><span> Dealer </span></a>
                </li>

                <li>
                    <a class="waves-effect" href="manage-sales-executive" ><i class="fa fa-user-circle-o"></i><span> Sales Excutive </span></a>
                </li>

                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect">
                        <i class="fa fa-tags"></i>
                        <span> Price Master 
                            <span class="pull-right">
                                <i class="mdi mdi-chevron-right"></i>
                            </span> 
                        </span>
                    </a>
                    <ul class="list-unstyled">
                        <li><a href="distributor-price-list">Distributors Price Master</a></li>
                        <li><a href="dealer-price-list">Dealer Price Master</a></li>
                    </ul>
                </li>

                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect">
                        <i class="fa fa-shopping-cart"></i>
                        <span> Sales 
                            <span class="pull-right">
                                <i class="mdi mdi-chevron-right"></i>
                            </span> 
                        </span>
                    </a>
                    <ul class="list-unstyled">
                        <li><a href="create-sales">Add Invoice</a></li>
                        <li><a href="manage-sales">List Invoice</a></li>
                    </ul>
                </li>

                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect">
                        <i class="fa fa-retweet"></i>
                        <span> Sales Return 
                            <span class="pull-right">
                                <i class="mdi mdi-chevron-right"></i>
                            </span> 
                        </span>
                    </a>
                    <ul class="list-unstyled">
                        <li><a href="create-return">Add Sales Return</a></li>
                        <li><a href="manage-return">List Sales Return</a></li>
                    </ul>
                </li>
                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect">
                        <i class="fa fa-cog"></i>
                        <span> Service 
                            <span class="pull-right">
                                <i class="mdi mdi-chevron-right"></i>
                            </span> 
                        </span>
                    </a>
                    <ul class="list-unstyled">
                        <li><a href="create-service">Create Service</a></li>
                        <li><a href="manage-service">Manage Service</a></li>
                    </ul>
                </li>
                <li>
                    <a class="waves-effect" href="manage-payment" ><i class="fa fa-credit-card-alt"></i><span> Payment </span></a>
                </li>

                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect">
                        <i class="fa fa-line-chart"></i>
                        <span> Report 
                            <span class="pull-right">
                                <i class="mdi mdi-chevron-right"></i>
                            </span> 
                        </span>
                    </a>
                    <ul class="list-unstyled">
                        <li><a href="manage-purchase-report">Purchase Report</a></li>
                        <li><a href="manage-distributor-sale-report">Distributor Sales Report</a></li>
                        <!-- <li><a href="manage-date-wise-report">Distributors Payment Report</a></li>
                        <li><a href="manage-date-wise-report">Dealer Payment Report</a></li> -->
                        <!-- <li><a href="manage-serial-no-report">Serial No Wise Report</a></li> -->
                    </ul>
                </li>
                
               <!--  <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect">
                        <i class="dripicons-graph-line"></i>
                        <span> Report 
                            <span class="pull-right">
                                <i class="mdi mdi-chevron-right"></i>
                            </span> 
                        </span>
                    </a>
                    <ul class="list-unstyled">
                        <li><a href="manage-purchase-report">Purchase Report</a></li>
                        <li><a href="manage-sale-report">Sales Report</a></li>
                    </ul>
                </li> -->

                <li>
                    <a class="waves-effect" href="change-password" ><i class="fa fa-key"></i><span> Change Password </span></a>
                </li>
                <?php endif; ?>
                <li class="has_sub">
                    <a class="waves-effect logout"><i class="dripicons-copy"></i><span> Logout </span></a>
                </li>
            </ul>
        </div>
        <div class="clearfix"></div>
    </div>
</div>

